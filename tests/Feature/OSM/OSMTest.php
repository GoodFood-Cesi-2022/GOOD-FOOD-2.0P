<?php

namespace Tests\Feature\OSM;

use App\Models\Address;
use App\Services\OSMService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use \OSM;

class OSMTest extends TestCase
{
    /**
     * Test l'indisponibilité du service de géocoding
     *
     * @group OSM
     * @return void
     */
    public function test_service_unaivalble()
    {
        
        Http::fake([
            config('osm.uri') . '/*' => Http::response([], 404)
        ]);

        $this->assertFalse(OSM::transformAddressToGeocoding(Address::factory()->make()));

    }

    /**
     * Test la validaité de l'URL 
     *
     * @group OSM
     * @return void
     */
    public function test_service_osm_uri_is_valid() {

        $class = new \ReflectionClass(\App\Services\OSMService::class);

        $method = $class->getMethod('getApiUri');

        $method->setAccessible(true);

        $instance = new OSMService;

        $res = $method->invokeArgs($instance, []);

        $this->assertIsString(filter_var($res, FILTER_VALIDATE_URL));

    }

}
