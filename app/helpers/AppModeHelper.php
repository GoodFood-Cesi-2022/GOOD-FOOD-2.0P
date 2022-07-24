<?php

use Illuminate\Support\Facades\Cache;

if(!function_exists('get_app_mode')) {

    /**
     * Retourne le mode de fonctionnement de l'application
     *
     * @return string
     */
    function get_app_mode() : string {
        return Cache::get(config('app.mode'), 'normal');
    }

}


if(!function_exists('set_app_mode')) {

    /**
     * Set le mode de l'application
     *
     * @param string $mode
     * @return void
     */
    function set_app_mode(string $mode) : void {
        Cache::set(config('app.mode'), $mode);
    }

}


if(!function_exists('app_has_mode')) {

    /**
     * Determine si l'application a un mode
     *
     * @return boolean
     */
    function app_has_mode() : bool {
        return Cache::has(config('app.mode'));
    }

}



if(!function_exists('app_mode_configuration')) {

    /**
     * Determine si l'application est en mode configuration
     *
     * @return boolean
     */
    function app_mode_configuration() : bool {
        return get_app_mode() === "configuration";
    }

}


if(!function_exists('app_mode_normal')) {

    /**
     * Determine si l'application est en mode normal
     *
     * @return boolean
     */
    function app_mode_normal() : bool {
        return get_app_mode() === "normal";
    }

}