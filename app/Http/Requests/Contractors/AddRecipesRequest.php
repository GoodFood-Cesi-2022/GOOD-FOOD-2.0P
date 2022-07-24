<?php

namespace App\Http\Requests\Contractors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddRecipesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('add-recipes', $this->contractor);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'recipes' => [
                'required',
                'array'
            ],
            'recipes.*.recipe_id' => [
                'required',
                'integer',
                Rule::exists('recipes', 'id'),
                Rule::unique('contractor_recipes')->where(fn ($query) => $query->where('contractor_id', $this->contractor->id))
            ],
            'recipes.*.price' => [
                'required',
                'numeric'
            ]
        ];
    }
}
