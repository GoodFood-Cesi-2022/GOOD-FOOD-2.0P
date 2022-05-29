<?php

use Illuminate\Support\Facades\URL;
use Illuminate\Routing\UrlGenerator;


if(!function_exists('url_cdn')) {

    /**
     * Generate a url for the cdn.
     *
     * @param  string|null  $path
     * @param  mixed  $parameters
     * @param  bool|null  $secure
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function url_cdn($path = null, $parameters = [], $secure = null) {

        URL::forceRootUrl(\Config::get('app.cdn_url'));

        if (is_null($path)) {
            return app(UrlGenerator::class);
        }

        return app(UrlGenerator::class)->to($path, $parameters, $secure);

    }

}