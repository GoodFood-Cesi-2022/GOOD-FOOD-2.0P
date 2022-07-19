<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', \App\Models\Order::class);
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
            'recipes.*' => [
                'array'
            ],
            'recipes.*.id' => [
                'required',
                'integer',
                Rule::exists('contractor_recipes', 'recipe_id')->where(function($query) {
                    return $query->whereContractorId($this->contractor->id);
                })
            ],
            'recipes.*.comment' => [
                'sometimes',
                'string'
            ],
            'recipes.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ],
            'address_id' => [
                'required',
                'integer',
                Rule::exists('user_addresses', 'address_id')->where(function($query) {
                    return $query->whereUserId($this->user()->id);
                })
            ]
        ];
    }
}
