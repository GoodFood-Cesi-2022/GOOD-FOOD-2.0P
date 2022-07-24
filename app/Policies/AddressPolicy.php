<?php

namespace App\Policies;

use App\Enums\Roles;
use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;


    /**
     * Determine si l'utilisateur peut créer une adresse
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user) : bool {

        return $user->hasOneOfRoles([
            Roles::goodfood->value,
            Roles::contractor->value,
            Roles::user->value
        ]);

    }


    /**
     * Determine si l'utilisateur courant peut mettre à jour l'adresse
     *
     * @param User $user
     * @param Address $address
     * @return boolean
     */
    public function update(User $user, Address $address) : bool {

        return $user->hasRole(Roles::goodfood->value) ?: $address->isCreatedBy($user);

    }


    /**
     * Determine si l'utilisateur peut supprimer l'adresse
     *
     * @param User $user
     * @param Address $address
     * @return boolean
     */
    public function delete(User $user, Address $address) : bool {

        return $user->hasRole(Roles::goodfood->value) ?: $address->isCreatedBy($user);

    }


}
