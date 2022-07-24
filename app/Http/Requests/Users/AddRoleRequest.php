<?php

namespace App\Http\Requests\Users;


use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class AddRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('add-role', $this->user_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => [
                'required',
                new Enum(\App\Enums\Roles::class),
            ]
        ];
    }
}
