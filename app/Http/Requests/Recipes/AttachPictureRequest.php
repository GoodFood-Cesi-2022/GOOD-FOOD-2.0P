<?php

namespace App\Http\Requests\Recipes;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AttachPictureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return $this->user()->can('attach-picture', $this->recipe);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'file_uuid' => [
                'required',
                'uuid',
                Rule::exists('files', 'uuid'),
            ]
        ];
    }


}
