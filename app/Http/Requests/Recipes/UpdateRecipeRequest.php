<?php

namespace App\Http\Requests\Recipes;

use App\Http\Requests\Recipes\AddRecipeRequest;

class UpdateRecipeRequest extends AddRecipeRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->recipe);
    }

    
}
