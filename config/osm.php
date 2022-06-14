<?php


return [


    /*
    |--------------------------------------------------------------------------
    | API SCHEME
    |--------------------------------------------------------------------------
    |
    | SCHEME OSM to open street map
    |
    */
    'scheme' => env('OSM_SCHEME', 'https'),

    /*
    |--------------------------------------------------------------------------
    | OSM URI
    |--------------------------------------------------------------------------
    |
    | URI of open street map API
    |
    */
    'uri' => env('OSM_URI', 'nominatim.openstreetmap.org'),

];