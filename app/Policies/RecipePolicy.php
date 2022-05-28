<?php

namespace App\Policies;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecipePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the current user can view recipes
     *
     * @param User $user
     * @return boolean
     */
    public function viewAny(User $user) : bool {
        return $user->hasOneOfRoles([
            Roles::goodfood->value,
            Roles::contractor->value
        ]);
    }

    /**
     * Determine if user can create recipe
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user) : bool {

        return $user->hasOneOfRoles([
            Roles::goodfood->value,
            Roles::contractor->value,
        ]);

    }

    /**
     * Determine if the user can star a recipe
     *
     * @param User $user
     * @return boolean
     */
    public function star(User $user) : bool {

        return $user->hasRole(Roles::goodfood->value);
    
    }

    /**
     * Determine if the user can unstar a recipe
     *
     * @param User $user
     * @return boolean
     */
    public function unstar(User $user) : bool {

        return $user->hasRole(Roles::goodfood->value);

    }

}
