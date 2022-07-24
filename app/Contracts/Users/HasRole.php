<?php

namespace App\Contracts\Users;

interface HasRole {

    /**
     * Determine si l'utilisateur a le role
     *
     * @param string $code
     * @return boolean
     */
    public function hasRole(string $code) : bool;

    /**
     * Retourne tous les rôles de l'utilsateur
     * Eloquent relationship
     */
    public function roles();

    /**
     * Determine si l'utilisateur à un des rôles passés en paramètre
     *
     * @param array $codes
     * @return boolean
     */
    public function hasOneOfRoles(array $codes) : bool;

    /**
     * Determine si l'utilisateur à tous les rôles passés en paramètre
     *
     * @param array $codes
     * @return boolean
     */
    public function hasAllRoles(array $codes) : bool;


}
