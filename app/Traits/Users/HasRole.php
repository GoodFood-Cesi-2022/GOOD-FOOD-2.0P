<?php

namespace App\Traits\Users;


trait HasRole {

    /**
     * Determine si l'utilisateur a le role
     *
     * @param string $code
     * @return boolean
     */
    public function hasRole(string $code) : bool {
        return $this->roles()->whereCode($code)->count() > 0;
    }

    /**
     * Retourne tous les rôles de l'utilsateur
     * Eloquent relationship
     */
    public function roles() {
        return $this->belongsToMany(\App\Models\Role::class, 'user_roles')->withTimestamps();
    }

    /**
     * Determine si l'utilisateur à un des rôles passés en paramètre
     *
     * @param array $codes
     * @return boolean
     */
    public function hasOneOfRoles(array $codes) : bool {

        if(empty($codes)) {
            return false;
        }

        return $this->roles()->whereIn('code', $codes)->count() > 0;
    }

    /**
     * Determine si l'utilisateur à tous les rôles passés en paramètre
     *
     * @param array $codes
     * @return boolean
     */
    public function hasAllRoles(array $codes) : bool {

        return $this->roles()->whereIn('code', $codes)->count() === count($codes);

    }


}
