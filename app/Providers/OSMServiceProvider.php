<?php

namespace App\Providers;

use App\Services\OSMService;
use Illuminate\Support\ServiceProvider;

class OSMServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('osmservice', function() {
            return new OSMService;
        });
    }
}
