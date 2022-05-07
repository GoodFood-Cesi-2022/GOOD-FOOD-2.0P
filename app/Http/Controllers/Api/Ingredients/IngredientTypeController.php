<?php

namespace App\Http\Controllers\Api\Ingredients;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ingredients\CreateIngredientTypeRequest;
use App\Http\Resources\IngredientTypeCollection;
use App\Http\Resources\IngredientTypeResource;
use App\Models\IngredientType;

class IngredientTypeController extends Controller
{
    
    /**
     * Créé un nouveau type d'ingrédient dans le système
     *
     * @param CreateIngredientTypeRequest $request
     * @return IngredientTypeResource
     */
    public function create(CreateIngredientTypeRequest $request) : IngredientTypeResource {

        $ingredient_type = new IngredientType($request->only(['name', 'description']));

        $ingredient_type->code = $request->name;

        $ingredient_type->save();

        return new IngredientTypeResource($ingredient_type);

    }


    /**
     * Récupére tous les types d'ingrédients
     *
     * @return IngredientTypeCollection
     */
    public function all() : IngredientTypeCollection {

        $this->authorize('view-any', IngredientType::class);

        $ingredient_types = IngredientType::all();

        return new IngredientTypeCollection($ingredient_types);

    }

    

}
