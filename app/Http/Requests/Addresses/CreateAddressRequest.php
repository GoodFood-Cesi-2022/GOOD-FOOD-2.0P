<?php

namespace App\Http\Requests\Addresses;

use App\Models\Address;
use Illuminate\Foundation\Http\FormRequest;

class CreateAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Address::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_line' => [
                'required',
                'string'
            ],
            'second_line' => [
                'nullable',
                'string'
            ],
            'zip_code' => [
                'required',
                'string'
            ],
            'country' => [
                'required',
                'string'
            ],
            'city' => [
                'required',
                'string'
            ]
        ];
    }
}
