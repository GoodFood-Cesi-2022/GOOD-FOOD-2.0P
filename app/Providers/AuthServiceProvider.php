<?php

namespace App\Providers;

use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Client secret are hased in database
        if(!$this->app->environment('local')) {
            \Laravel\Passport\Passport::hashClientSecrets();
        }

        if(!$this->app->routesAreCached()) {
            \Laravel\Passport\Passport::routes();
        }
    }
}
