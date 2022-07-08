<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserAddressResource;
use App\Http\Resources\UserAddressCollection;
use App\Http\Requests\Users\AddUserAddressRequest;
use App\Http\Requests\Users\UpdateUserAddressRequest;
use App\Models\UserAddress;

class UserAddressesController extends Controller
{
    
    /**
     * Attache une nouvelle adresse à l'utilisateur courant
     *
     * @param AddUserAddressRequest $request
     * @param User $user
     * @return UserAddressResource
     */
    public function add(AddUserAddressRequest $request, User $user) : UserAddressResource {

        $address = Address::findOrFail($request->address_id);

        $user->addresses()->attach($address, [
            'name' => $request->name,
            'default' => $request->default,
            'timezone' => 'FR'
        ]);

        $user_address = new UserAddress($user->addresses()->whereAddressId($address->id)->first()->pivot->toArray());

        return new UserAddressResource($user_address);

    }

    /**
     * Retourne la liste des adresses de l'utilisateur
     *
     * @param User $user
     * @return UserAddressCollection
     */
    public function all(User $user) : UserAddressCollection {

        $addresses = $user->addresses;

        $user_addresses = $addresses->map(function($address) {
            return new UserAddress($address->pivot->toArray());
        });

        return new UserAddressCollection($user_addresses);

    }

    /**
     * Détache une adresse du compte de l'utilisateur
     *
     * @param User $user
     * @param Address $address
     * @return Response
     */
    public function delete(User $user, Address $address) : Response {

        $this->authorize('delete', $address);

        $user->addresses()->detach($address->id);

        return response('', 204);

    }

    /**
     * Mise à jour des informations de l'adresse de l'utilisateur
     *
     * @param UpdateUserAddressRequest $request
     * @param User $user
     * @param Address $address
     * @return Response
     */
    public function update(UpdateUserAddressRequest $request, User $user, Address $address) : Response {

        $user->addresses()->updateExistingPivot($address->id, [
            'name' => $request->name,
            'default' => $request->default,
        ]);

        return response('', 204);

    }


}
