<?php

namespace App\Policies;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can upload file
     *
     * @param User $user
     * @return bool
     */
    public function upload(User $user) : bool {

        return $user->hasOneOfRoles([
            Roles::goodfood->value,
            Roles::contractor->value
        ]);

    }

}
