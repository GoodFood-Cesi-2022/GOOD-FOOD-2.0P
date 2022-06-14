<?php

namespace App\Http\Controllers\Api\Addresses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Addresses\CreateAddressRequest;
use App\Http\Requests\Addresses\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \OSM;

class AddressController extends Controller
{
    
    /**
     * Créer une nouvelle adresse dans le système
     *
     * @param CreateAddressRequest $request
     * @return AddressResource
     */
    public function create (CreateAddressRequest $request) : AddressResource {

        $address = new Address($request->only([
            'first_line', 'second_line', 'zip_code', 'city', 'country'
        ]));

        if($osm_response = OSM::transformAddressToGeocoding($address)) {

            $address->lat = $osm_response[0]['lat'];
            $address->lon = $osm_response[0]['lon'];

        }

        $address->save();

        return new AddressResource($address);

    }

    /**
     * Mettre à jour une adresse existante
     *
     * @param UpdateAddressRequest $request
     * @return Response
     */
    public function update (UpdateAddressRequest $request) : AddressResource {

        $address = $request->address;

        $address->first_line = $request->first_line;
        $address->second_line = $request->second_line;
        $address->zip_code = $request->zip_code;
        $address->city = $request->city;
        $address->country = $request->country;

        if($osm_response = OSM::transformAddressToGeocoding($address)) {

            $address->lat = $osm_response[0]['lat'];
            $address->lon = $osm_response[0]['lon'];

        }

        $address->save();

        return new AddressResource($address);

    }

    /**
     * Supprime un adresse du système
     *
     * @param Request $request
     * @return Response
     */
    public function delete (Request $request) : Response {

        $this->authorize('delete', $request->address);

        $request->address->delete();

        return response('', 204);

    }


}
