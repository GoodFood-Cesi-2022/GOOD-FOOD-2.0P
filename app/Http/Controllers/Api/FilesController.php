<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Files\AddFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Storage;

class FilesController extends Controller
{
    
    /**
     * Upload a new file in the system
     *
     * @param AddFileRequest $request
     * @return JsonResponse
     */
    public function upload(AddFileRequest $request, File $file_model) : JsonResponse {

        if($request->file('filename') &&
            $request->file('filename')->isValid() && 
                $path = $request->file('filename')->store('files')) {

            try {

                $file = $file_model->create([
                    'name' => $request->name,
                    'path' => $path,
                    'size' => $request->file('filename')->getSize()
                ]);

            }catch(\Exception $e) {
                Storage::delete($path);
                throw $e;
            }

            return (new FileResource($file))->response()->setStatusCode(201);

        }

        abort(400);

    }


}
