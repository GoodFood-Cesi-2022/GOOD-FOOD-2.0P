<?php

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


    Route::get('ping', function() {
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


            Route::group(['prefix' => 'addresses'], function() {

                Route::get('', [\App\Http\Controllers\Api\Users\UserAddressesController::class, 'all']);
                Route::post('', [\App\Http\Controllers\Api\Users\UserAddressesController::class, 'add']);

                Route::group(['prefix' => '{address}'], function() {
                    Route::put('', [\App\Http\Controllers\Api\Users\UserAddressesController::class, 'update']);
                    Route::delete('', [\App\Http\Controllers\Api\Users\UserAddressesController::class, 'delete']);
                });

            });


        });

    });

    Route::group(['prefix' => "roles"], function() {

        Route::get('', [\App\Http\Controllers\Api\Roles\RolesController::class, 'getAll']);

    });

    Route::group(['prefix' => 'ingredients'], function() {

        Route::get('', [\App\Http\Controllers\Api\Ingredients\IngredientController::class, 'all']);
        Route::post('', [\App\Http\Controllers\Api\Ingredients\IngredientController::class, 'create']);

        Route::group(['prefix' => 'types'], function(){
            Route::get('', [\App\Http\Controllers\Api\Ingredients\IngredientTypeController::class, 'all']);
            Route::post('', [\App\Http\Controllers\Api\Ingredients\IngredientTypeController::class, 'create']);
        });

        Route::group(['prefix' => '{ingredient}'], function() {
            Route::put('', [\App\Http\Controllers\Api\Ingredients\IngredientController::class, 'update']);
            Route::delete('', [\App\Http\Controllers\Api\Ingredients\IngredientController::class, 'delete']);
        });

        
    });


    Route::group(['prefix' => 'files'], function() {

        Route::post('', [\App\Http\Controllers\Api\FilesController::class, 'upload']);

    });


    Route::group(['prefix' => 'recipes'], function() {

        Route::get('', [\App\Http\Controllers\Api\Recipes\RecipesController::class, 'all']);
        Route::post('', [\App\Http\Controllers\Api\Recipes\RecipesController::class, 'add']);

        Route::group(['prefix' => 'types'], function() {
            Route::get('', [\App\Http\Controllers\Api\Recipes\RecipesController::class, 'getTypes']);
        });


        Route::group(['prefix' => '{recipe}'], function() {
            Route::get('', [\App\Http\Controllers\Api\Recipes\RecipesController::class, 'find']);
            Route::put('', [\App\Http\Controllers\Api\Recipes\RecipesController::class, 'update']);
            Route::delete('', [\App\Http\Controllers\Api\Recipes\RecipesController::class, 'delete']);
            Route::post('star', [\App\Http\Controllers\Api\Recipes\RecipesController::class, 'star']);
            Route::post('unstar', [\App\Http\Controllers\Api\Recipes\RecipesController::class, 'unstar']);
            Route::get('ingredients', [\App\Http\Controllers\Api\Recipes\RecipesController::class, 'getIngredients']);
            
            Route::group(['prefix' => 'pictures'], function() {
                Route::get('', [\App\Http\Controllers\Api\Recipes\RecipePicturesController::class, 'getPictures']);
                Route::post('', [\App\Http\Controllers\Api\Recipes\RecipePicturesController::class, 'attach']);

                Route::group(['prefix' => '{picture}'], function() {
                    Route::delete('', [\App\Http\Controllers\Api\Recipes\RecipePicturesController::class, 'detach']);
                });

                
            });

        });

    });

    Route::group(['prefix' => 'addresses'], function() {

        Route::post('', [\App\Http\Controllers\Api\Addresses\AddressController::class, 'create']);

        Route::group(['prefix' => '{address}'], function() {
            Route::put('', [\App\Http\Controllers\Api\Addresses\AddressController::class, 'update']);
            Route::delete('', [\App\Http\Controllers\Api\Addresses\AddressController::class, 'delete']);
        });

    });


    Route::group(['prefix' => 'contractors'], function() {

        Route::post('', [\App\Http\Controllers\Api\Contractors\ContractorController::class, 'create']);
        Route::get('', [\App\Http\Controllers\Api\Contractors\ContractorController::class, 'all']);

        Route::group(['prefix' => '{contractor}'], function() {

            Route::put('', [\App\Http\Controllers\Api\Contractors\ContractorController::class, 'update']);
            Route::delete('', [\App\Http\Controllers\Api\Contractors\ContractorController::class, 'delete']);

            Route::group(['prefix' => 'recipes'], function() {
                Route::get('', [\App\Http\Controllers\Api\Contractors\ContractorController::class, 'getRecipes']);
                Route::post('', [\App\Http\Controllers\Api\Contractors\ContractorController::class, 'addRecipes']);

                Route::group(['prefix' => '{recipe}'], function() {
                    Route::put('', [\App\Http\Controllers\Api\Contractors\ContractorController::class, 'updateRecipe']);
                    Route::delete('', [\App\Http\Controllers\Api\Contractors\ContractorController::class, 'deleteRecipe']);
                });

            });

            Route::group(['prefix' => 'times'], function() {
                Route::get('', [\App\Http\Controllers\Api\Contractors\ContractorController::class, 'getTimes']);
                Route::post('', [\App\Http\Controllers\Api\Contractors\ContractorController::class, 'addTimes']);
                Route::put('', [\App\Http\Controllers\Api\Contractors\ContractorController::class, 'updateTimes']);
            });


        });


    });


});
