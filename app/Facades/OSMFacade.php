<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OSMFacade extends Facade {


    protected static function getFacadeAccessor() : string
    {
        return 'osmservice';
    } 


}