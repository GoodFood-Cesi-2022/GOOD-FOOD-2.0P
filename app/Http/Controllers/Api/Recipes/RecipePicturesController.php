<?php

namespace App\Http\Controllers\Api\Recipes;

use App\Models\File;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Recipes\AttachPictureRequest;
use App\Http\Requests\Recipes\DetachPictureRequest;

class RecipePicturesController extends Controller
{
 
    /**
     * Attache un fichier comme photo de la recette
     *
     * @param AttachPictureRequest $request
     * @return Response
     */
    public function attach(AttachPictureRequest $request) : Response {

        $file = File::whereUuid($request->file_uuid)->first();

        $request->recipe->pictures()->attach($file->id);

        return response('', 204);

    }

    /**
     * Détache un fichier de la recette
     *
     * @param DetachPictureRequest $request
     * @return Response
     */
    public function detach(DetachPictureRequest $request) : Response {

        $request->recipe->pictures()->detach($request->picture->id);

        return response('', 204);

    }



}
