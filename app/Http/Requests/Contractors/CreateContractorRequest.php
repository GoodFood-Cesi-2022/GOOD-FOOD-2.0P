<?php

namespace App\Http\Requests\Contractors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateContractorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', \App\Models\Contractor::class);
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
            'phone' => [
                'string'
            ],
            'address_id' => [
                Rule::exists('addresses', 'id')
            ],
            'max_delivery_radius' => [
                'required',
                'integer'
            ],
            'email' => [
                'required',
                'string',
                'email'
            ]
        ];
    }
}
