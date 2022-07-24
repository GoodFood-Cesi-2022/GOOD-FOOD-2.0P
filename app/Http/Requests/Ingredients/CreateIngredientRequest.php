<?php

namespace App\Http\Requests\Ingredients;

use App\Models\Ingredient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateIngredientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Ingredient::class);
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
                'max:200'
            ],
            'allergen' => [
                'required',
                'boolean'
            ],
            'types' => [
                'nullable',
                'array'
            ],
            'types.*' => [
                'string',
                Rule::exists('ingredient_types', 'code')
            ]
        ];
    }
}
