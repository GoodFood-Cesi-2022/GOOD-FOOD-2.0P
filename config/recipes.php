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


];