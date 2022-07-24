<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\IngredientType::class => \App\Policies\IngredientTypePolicy::class,
        \App\Models\Ingredient::class => \App\Policies\IngredientPolicy::class,
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
