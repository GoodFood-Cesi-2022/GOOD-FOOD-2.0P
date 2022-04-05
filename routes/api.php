<?php

use App\Http\Controllers\Api\Users\UserRolesController;
use App\Http\Controllers\Api\Users\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ["auth:api", "verified"]], function() {


    Route::get('tmp', function() {
        return response(['alive' => true]);
    });


    Route::group(['prefix' => 'users'], function() {
        
        Route::get('', [\App\Http\Controllers\Api\Users\UsersController::class, 'getAllUsers']);

        Route::post('', [\App\Http\Controllers\Api\Users\UsersController::class, 'createUser']);

        Route::group(['prefix' => "{user_id}"], function() {

            Route::get('', [\App\Http\Controllers\Api\Users\UsersController::class, 'getUser']);

            Route::group(['prefix' => "roles"], function() {

                Route::get('', [\App\Http\Controllers\Api\Users\UserRolesController::class, 'getRoles']);
                Route::post('',[\App\Http\Controllers\Api\Users\UserRolesController::class, 'addRole']);
                Route::delete('{role}', [\App\Http\Controllers\Api\Users\UserRolesController::class, 'detachRole']);
                

            });


        });

    });

    Route::group(['prefix' => "roles"], function() {

        Route::get('', [\App\Http\Controllers\Api\Roles\RolesController::class, 'getAll']);

    });

});
