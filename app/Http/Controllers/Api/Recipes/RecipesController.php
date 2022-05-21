<?php

namespace App\Http\Controllers\Api\Recipes;

use DB;
use Carbon\Carbon;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\RecipeType;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Http\Requests\Recipes\AddRecipeRequest;
use App\Notifications\NewRecipeStarAdded;
use Notification;

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

        if(app_mode_normal() && $recipe->star) {
            // A revoir quand les franchisés seront développés
            $contractors = [auth()->user()];
            Notification::send($contractors, new NewRecipeStarAdded($recipe));
        }

        return new RecipeResource($recipe);

    }


}
