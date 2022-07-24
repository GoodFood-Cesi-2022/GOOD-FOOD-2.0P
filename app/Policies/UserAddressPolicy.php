<?php

namespace App\Policies;

use App\Enums\Roles;
use App\Models\Address;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserAddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine si l'utilisateur peut ajouter une adresse à son compte
     *
     * @param User $user
     * @return boolean
     */
    public function add(User $user) : bool {

        return true;

    }

}
