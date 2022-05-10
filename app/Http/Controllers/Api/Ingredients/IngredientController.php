<?php

namespace App\Http\Controllers\Api\Ingredients;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Models\IngredientType;
use App\Http\Controllers\Controller;
use App\Http\Resources\IngredientResource;
use App\Http\Resources\IngredientCollection;
use App\Http\Requests\Ingredients\CreateIngredientRequest;
use App\Http\Requests\Ingredients\UpdateIngredientRequest;
use Illuminate\Http\Response as HttpResponse;
use DB;

class IngredientController extends Controller
{
    
    /**
     * Create a new ingredient in the system
     *
     * @param CreateIngredientRequest $request
     * @return IngredientResource
     */
    public function create(CreateIngredientRequest $request, Ingredient $ingredient_model) : IngredientResource {

        DB::beginTransaction();

        try {

            $ingredient = $ingredient_model->create($request->only(['name', 'allergen']));

            $ingredient_types = IngredientType::whereIn('code', $request->types)->get();

            $ingredient->types()->sync($ingredient_types->pluck('id')->toArray());

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            
            throw $e;
        
        }

        return new IngredientResource($ingredient);
    }
    
    
    /**
     * Retourne l'ensemble des ingrédients paginés par 25
     *
     * @return IngredientCollection
     */
    public function all() : IngredientCollection {

        $this->authorize('view-any', Ingredient::class);

        $ingredients = Ingredient::paginate(25);

        return new IngredientCollection($ingredients);
    }


    /**
     * Permet de mettre à jour un ingrédient
     *
     * @param UpdateIngredientRequest $request
     * @return IngredientResource
     */
    public function update(UpdateIngredientRequest $request, Ingredient $ingredient) : HttpResponse {

        $ingredient->update($request->only(['name', 'allergen']));

        $ingredient_types = IngredientType::whereIn('code', $request->types)->get();

        $ingredient->types()->sync($ingredient_types->pluck('id')->toArray());

        return response('', 204);

    }

    /**
     * Supprime un ingrédient
     *
     * @param Request $request
     * @param Ingredient $ingredient
     * @return HttpResponse
     */
    public function delete(Request $request, Ingredient $ingredient) : HttpResponse {

        $this->authorize('delete', $ingredient);

        $ingredient->delete();

        return response('', 204);

    }



}
