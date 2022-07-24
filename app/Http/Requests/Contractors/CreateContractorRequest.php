<?php

namespace App\Http\Requests\Contractors;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

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
            ],
            'owned_by' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
            ]
        ];
    }


    /**
     * Add more verifications
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator) : void
    {
        $validator->after(function (Validator $validator) {

            $owner = User::findOrFail($this->owned_by);

            if (!$owner->hasOneOfRoles([
                Roles::goodfood->value,
                Roles::contractor->value
            ])) {
                $validator->errors()->add('owned_by', 'The owner must be a contractor or an administrator');
            }
        });
    }


}
