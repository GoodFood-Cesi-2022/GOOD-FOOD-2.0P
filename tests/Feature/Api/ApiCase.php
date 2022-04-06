<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Enums\Roles;
use App\Models\Role;
use App\Models\User;
use Laravel\Passport\Passport;

class ApiCase extends TestCase {
    
    /**
     * Authentifie l'utilisateur pour l'API en tant que client
     *
     * @return User
     */
    public function actingAsClient() : User {
        return $this->actingLike(Roles::user->value);
    }

    /**
     * Authentifie l'utilisateur pour l'API en tant que fournisseur
     *
     * @return User
     */
    public function actingAsContractor() : User {
        return $this->actingLike(Roles::contractor->value);
    }

    /**
     * Authentifie l'utilisateur pour l'API en tant qu'administrateur
     *
     * @return User
     */
    public function actingAsGoodFood() : User {
        return $this->actingLike(Roles::goodfood->value);
    }


    /**
     * Authentifie l'utilisateur pour l'API en créeant un 
     * utilisateur dans BBD et en assignant le rôle passé en paramètre
     *
     * @param string $code Code du rôle
     * @return User
     */
    protected function actingLike(string $code) : User {

        $user = User::factory()->create();

        $user->roles()->attach(Role::whereCode($code)->first());

        Passport::actingAs($user);

        return $user;

    }

    /**
     * Roles data code provider
     *
     * @return array
     */
    protected function roleDataProvider() : array {

        return [
            [Roles::goodfood->value],
            [Roles::contractor->value],
            [Roles::user->value]
        ];

    }


}