<?php

namespace App\Http\Controllers\Api\Roles;

use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleCollection;

class RolesController extends Controller
{
    

    /**
     * Retourne tous les rôles disponible pour l'API et les assigner aux utilisateurs
     *
     * @return \App\Http\Resources\RoleCollection
     */
    public function getAll()  {

        return new RoleCollection(Role::all());

    }



}
