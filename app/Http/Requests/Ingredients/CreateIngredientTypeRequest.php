<?php

namespace App\Http\Requests\Ingredients;

use App\Models\IngredientType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class CreateIngredientTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', IngredientType::class);
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
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
                'max:250'
            ]
        ];
    }


    /**
     * Add more rules
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator) {

        $validator->after(function(Validator $validator) {
            
            $code = Str::slug($this->name);
        
            if(IngredientType::whereCode($code)->count() > 0) {
                $validator->errors()->add('name', __('ingredients.types.validations.exists'));
            }

        });


    }

}
