<?php
namespace App\Http\Controllers\Api\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleCollection;
use App\Http\Requests\DetachRoleRequest;
use App\Http\Requests\Users\AddRoleRequest;

class UserRolesController extends Controller {

    /**
     * Ajoute un rôle à l'utilisateur
     *
     * @param AddRoleRequest $request
     * @return Response
     */
    public function addRole(AddRoleRequest $request) : Response {

        $role = Role::whereCode($request->safe()->code)->first();

        $request->user_id->roles()->syncWithoutDetaching([$role->id]);

        return response('', 204);

    }

    /**
     * Retourne la liste des roles de l'utilisateur 
     *
     * @param Request $request
     * @return RoleCollection
     */
    public function getRoles(Request $request) : RoleCollection {

        $this->authorize('view-roles', $request->user_id);

        return new RoleCollection($request->user_id->roles);

    } 

    /**
     * Détache un rôle de l'utilisateur
     *
     * @param DetachRoleRequest $request
     * @return Response
     */
    public function detachRole(DetachRoleRequest $request) : Response {

        $request->user_id->roles()->detach($request->role->id);
        
        return response('', 204);
    }


}