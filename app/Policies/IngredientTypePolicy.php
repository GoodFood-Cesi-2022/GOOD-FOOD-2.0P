<?php

namespace App\Policies;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IngredientTypePolicy
{
    use HandlesAuthorization;

    
    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user) : bool
    {
        return $user->hasOneOfRoles([
            Roles::goodfood->value,
            Roles::contractor->value
        ]);
    }

    /**
     * Determine whether the user can view any ingredients types 
     *
     * @param User $user
     * @return boolean
     */
    public function viewAny(User $user) : bool
    {
        return true;
    }



}
