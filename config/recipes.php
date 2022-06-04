<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Recipe delay before apply
    |--------------------------------------------------------------------------
    |
    | This delay determine how time a new recipe star is added to the catalog
    | for clients. This delay is only added when the app is running on normal mode,
    | if the app is running in configuration mode then the recipe will be added 
    | immediatly.
    |
    */
    'delay' => env('RECIPE_DELAY', '20d'),



    /*
    |--------------------------------------------------------------------------
    | Recipe delay before delete
    |--------------------------------------------------------------------------
    |
    | This delay determine how time a recipe will be deleted when app is in 
    | normal mode. When app is running in configuration mode the recipe will be
    | delete immediatly and no notification will be sent
    |
    */
    'delay_before_delete' => env('RECIPE_DELETE_DELAY', '15d'),


];