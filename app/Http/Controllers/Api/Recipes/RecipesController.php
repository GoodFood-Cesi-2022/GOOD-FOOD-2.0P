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
use App\Http\Resources\IngredientCollection;
use App\Http\Resources\RecipeTypeCollection;
use Illuminate\Http\Response;

class RecipesController extends Controller
{
    
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
