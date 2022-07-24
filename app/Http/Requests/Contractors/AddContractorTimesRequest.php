<?php

namespace App\Http\Requests\Contractors;

use Illuminate\Foundation\Http\FormRequest;

class AddContractorTimesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('add-times', $this->contractor);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rule = [
            'present',
            'nullable',
            'date_format:H:i'
        ];

        
        return get_contractor_service_days()->mapWithKeys(function($tojoin) use($rule) {
            return [collect($tojoin)->join('.') => $rule];
        })->toArray();

    }
}
