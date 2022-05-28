<?php

namespace App\Http\Requests\Recipes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DetachPictureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('detach-picture', $this->recipe);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * More rules
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator) {

        return $validator->after(function (Validator $validator) {

            // Check si le fichier est attaché à la recette
            if($this->recipe->pictures()->where('uuid', $this->picture->uuid)->count() <= 0) {
                $validator->errors()->add('picture', "The file is not linked to the recipe");
            }

        });

    }

}
