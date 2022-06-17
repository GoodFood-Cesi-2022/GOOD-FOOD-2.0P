<?php

namespace App\Http\Requests\Users;

use App\Models\UserAddress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddUserAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('add', UserAddress::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => [
                'required',
                'string',
                'max:100'
            ],
            "default" => [
                'required',
                'boolean'
            ],
            "address_id" => [
                'required',
                Rule::exists('addresses', 'id'),
                Rule::unique('user_addresses', 'address_id')->where(fn($query) => $query->where('user_id', $this->user()->id)),
            ]
        ];
    }
}
