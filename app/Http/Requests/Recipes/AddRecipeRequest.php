<?php

namespace App\Http\Requests\Recipes;

use App\Models\Recipe;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AddRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $can = $this->user()->can('create', Recipe::class);

        if($can && $this->star === true) {
            $can = $this->user()->can('star', Recipe::class);
        }
        
        return $can;        
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:150'
            ],
            'description' => [
                'present',
                'nullable',
                'string',
                'max:500'
            ],
            'star' => [
                'required',
                'boolean',
            ],
            'base_price' => [
                'required',
                'numeric'
            ],
            'ingredients' => [
                'required',
                'array'
            ],
            'ingredients.*' => [
                'integer',
                Rule::exists('ingredients', 'id')
            ],
            'recipe_type' => [
                'required',
                'string',
                Rule::exists('recipe_types', 'code')
            ]
        ];
    }

}
