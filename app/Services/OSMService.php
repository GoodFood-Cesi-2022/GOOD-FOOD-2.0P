<?php

namespace App\Services;

use App\Models\Address;
use Http;
use Illuminate\Http\Client\Response;
use ReflectionClass;

class OSMService {


    /**
     * Transforme une adresse en coordonnées lat, lon via le service OpenStreetMap
     *
     * @param Address $address
     * @return array|false
     */
    public function transformAddressToGeocoding(Address $address) : array|false {

        $q = http_build_query([
            'street' => "{$address->first_line}",
            'city' => $address->city,
            'postalcode' => $address->zip_code,
            'country' => $address->country, 
            'format' => 'json',
            'polygon' => 1,
            'addressdetails' => 1
        ]);

        try {
            $response = Http::retry(3, 100)->withHeaders([
                'Accept' => 'application/json'
            ])->get(OSMService::getApiUri() . "/search?{$q}");
    
            if($response->successful() && !empty($response->json())) {
                return $response->json();
            }
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }
        

        return false;

    }


    /**
     * Retourne l'URI de l'API OSM
     *
     * @return string
     */
    private static function getApiUri() : string {

        return config('osm.scheme') . '://' . config('osm.uri');

    }


}