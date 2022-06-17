<?php

namespace App\Http\Requests\Users;


class UpdateUserAddressRequest extends AddUserAddressRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->address);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        unset($rules['address_id']);

        return $rules;
    }
}
