<?php

namespace App\Http\Requests\Files;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;

class AddFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('upload', File::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'filename' => [
                'required',
                'file',
                'mimes:jpeg,jpg,png,bmp,pdf',
                'max:8000' // max 8Mo
            ],
            'name' => [
                'required',
                'string',
                'max:150'
            ]
        ];
    }
}
