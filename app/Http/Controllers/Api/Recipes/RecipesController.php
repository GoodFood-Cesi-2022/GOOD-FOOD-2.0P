<?php

namespace App\Http\Controllers\Api\Recipes;

use DB;
use Notification;
use Carbon\Carbon;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\RecipeType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Http\Resources\RecipeCollection;
use App\Notifications\NewRecipeStarAdded;
use App\Http\Requests\Recipes\AddRecipeRequest;
use App\Http\Requests\Recipes\StarRecipeRequest;
use App\Http\Requests\Recipes\UnstarRecipeRequest;
use App\Http\Requests\Recipes\UpdateRecipeRequest;
use App\Http\Resources\IngredientCollection;
use App\Http\Resources\RecipeTypeCollection;
use App\Notifications\DeleteRecipe;
use Illuminate\Http\Response;

class RecipesController extends Controller
{
    
    /**
     * Retrouve une recette
     *
     * @param Request $request
     * @param Recipe $recipe
     * @return RecipeResource
     */
    public function find(Request $request, Recipe $recipe) : RecipeResource {

        $this->authorize('view', $recipe);

        return new RecipeResource($recipe);

    }


    /**
     * Ajoute une recette dans le système
     *
     * @param AddRecipeRequest $request
     * @return RecipeResource
     */
    public function add(AddRecipeRequest $request, Recipe $recipe) : RecipeResource {


        DB::beginTransaction();

        try {

            $recipe->name = $request->safe()->name;
            $recipe->description = $request->safe()->description;
            $recipe->star = $request->safe()->star;
            $recipe->base_price = $request->safe()->base_price;
            $recipe->available_at = app_mode_configuration() ? Carbon::now() : Carbon::today()->add(config('recipes.delay')); 

            $recipe->type()->associate(RecipeType::whereCode($request->recipe_type)->first());

            $recipe->save();

            $ingredients = Ingredient::whereIn('id', $request->ingredients)->get();

            $recipe->ingredients()->sync($ingredients->pluck('id')->toArray());

            DB::commit();


        }catch(\Exception $e) {

            DB::rollBack();
            throw $e;

        }

        $this->sendNotificationRecipeStar($recipe);

        return new RecipeResource($recipe);

    }


    /**
     * Récupèrer l'ensemble des recettes de la plateformes
     *
     * @param Request $request
     * @return RecipeCollection
     */
    public function all(Request $request) : RecipeCollection {

        $this->authorize('view-any', Recipe::class);

        $recipes = Recipe::filter($request->all())->paginate('20');

        return new RecipeCollection($recipes);

    }

    /**
     * Met une recette en recette star
     *
     * @param StarRecipeRequest $request
     * @param Recipe $recipe
     * @return Response
     */
    public function star(StarRecipeRequest $request, Recipe $recipe) : Response {

        $recipe->star = true;

        $recipe->save();

        $this->sendNotificationRecipeStar($recipe);

        return response('', 204);

    }


    /**
     * Met une recette star en recette normale
     *
     * @param UnstarRecipeRequest $request
     * @param Recipe $recipe
     * @return Response
     */
    public function unstar(UnstarRecipeRequest $request, Recipe $recipe) : Response {

        $recipe->star = false;

        $recipe->save();

        return response('', 204);

    }



    /**
     * Retourne les types de recette
     *
     * @return RecipeTypeCollection
     */
    public function getTypes() : RecipeTypeCollection {

        $types = RecipeType::all();

        return new RecipeTypeCollection($types);

    }


    /**
     * Retourne la liste des ingredients
     *
     * @param Request $request
     * @return IngredientCollection
     */
    public function getIngredients(Request $request) : IngredientCollection {

        $this->authorize('view-ingredients', $request->recipe);

        $ingredients = $request->recipe->ingredients;

        return new IngredientCollection($ingredients);

    }

    /**
     * Mise à jour d'une recette
     *
     * @param UpdateRecipeRequest $request
     * @return Response
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe, Ingredient $ingredient_model) : Response {

        if($request->safe()->star) {
            $this->authorize('star', $recipe);
        }

        DB::beginTransaction();

        try {
        
            $recipe->name = $request->safe()->name;
            $recipe->description = $request->safe()->description;
            $recipe->star = $request->safe()->star;
            $recipe->base_price = $request->safe()->base_price;
            $recipe->type()->associate(RecipeType::whereCode($request->recipe_type)->first());
            $recipe->save();
            $ingredients = $ingredient_model->whereIn('id', $request->ingredients)->get();
            $recipe->ingredients()->sync($ingredients->pluck('id')->toArray());

            DB::commit();
        
        }catch(\Exception $e) {
            DB::rollback();
            throw $e;
        }

        if($recipe->wasChanged('star')) {
            $this->sendNotificationRecipeStar($recipe);
        }

        return response('', 204);


    }

    /**
     * Supprime une recette si en mode normal envoi un mail d'information
     *
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request, Recipe $recipe) : Response {

        $this->authorize('delete', $recipe);

        if(app_mode_configuration()) {
            $recipe->delete();
        }else{
            $recipe->trashed_at = Carbon::today()->add(config('recipes.delay_before_delete'));
            $recipe->save();
        }

        $this->sendNotificationRecipeDeleted($recipe);

        return response('', 204);

    }


    /**
     * Envoi une notification de suppression de la recette
     *
     * @param Recipe $recipe
     * @return void
     */
    private function sendNotificationRecipeDeleted(Recipe $recipe) : void {

        if(app_mode_normal() && $recipe->trashed_at != null) {
            // A revoir quand les franchisés seront développés
            $contractors = [auth()->user()];
            Notification::send($contractors, new DeleteRecipe($recipe));
        }


    }




    /**
     * Envoi une notification quand une recette star est créé
     *
     * @param Recipe $recipe
     * @return void
     */
    private function sendNotificationRecipeStar(Recipe $recipe) : void {

        if(app_mode_normal() && $recipe->star) {
            // A revoir quand les franchisés seront développés
            $contractors = [auth()->user()];
            Notification::send($contractors, new NewRecipeStarAdded($recipe));
        }

    } 

}
