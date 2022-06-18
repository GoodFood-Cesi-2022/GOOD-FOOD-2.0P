<?php

namespace App\Policies;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContractorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine si l'utilisateur courant peut créer un franchisé
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user) : bool {
        return $user->hasRole(Roles::goodfood->value);
    }

}
