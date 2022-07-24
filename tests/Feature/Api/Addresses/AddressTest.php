<?php

namespace Tests\Feature\Api\Addresses;

use App\Models\Address;
use Tests\Feature\Api\ApiCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends ApiCase
{

    const BASE_PATH = '/api/addresses';

    /**
     * Test la création d'une recette.
     *
     * @group addresses
     * @return void
     */
    public function test_create_address() : void
    {
        $this->actingAsClient();

        $data = [
            'first_line' => "9 rue des tests",
            'second_line' => "Résidence code coverage",
            'city' => "Chauvine",
            'zip_code' => "62000",
            'country' => "FRANCE"
        ];

        Http::fake([
            config('osm.uri') . '/*' => Http::response([
                [
                    'lat' => "1.9483722",
                    'lon' => "-0.9877728"
                ]
            ], 200)
        ]);

        $response = $this->postJson(self::BASE_PATH, $data);

        $response->assertCreated()->assertJsonFragment($data)->assertJsonStructure([
            'lat',
            'lon',
            'id'
        ]);

    }

    /**
     * Test la mise à jour de l'adresse
     *
     * @group addresses
     * @return void
     */
    public function test_update_address() : void {

        $user = $this->actingAsClient();

        $address = Address::factory()->create([
            'created_by' => $user->id
        ]);

        $new_data = [
            'first_line' => "9 Bis chemin de la loose",
            'second_line' => null,
            'city' => "Meylan",
            'zip_code' => "38240",
            'country' => "France"
        ];

        Http::fake([
            config('osm.uri') . "/*" => Http::response([
                [
                    'lat' => "0.827828",
                    'lon' => "1.929292"
                ]
            ])
        ]);

        $response = $this->putJson(self::BASE_PATH . "/{$address->id}", $new_data);

        $response->assertOk()->assertJsonFragment($new_data);


    }

    /**
     * Test la suppression d'une adresse
     *
     * @group addresses
     * @return void
     */
    public function test_delete_address() : void {

        $user = $this->actingAsClient();

        $address = Address::factory()->create([
            'created_by' => $user->id
        ]);

        $response = $this->deleteJson(self::BASE_PATH . "/{$address->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id
        ]);


    }


    /**
     * Test si un utilisateur avec le rôle goodfood peut 
     * modifier une adresse d'une autre personne
     *
     * @group addresses
     * @return void
     */
    public function test_goodfood_can_edit_another_address() : void {

        $user = $this->actingAsClient();

        $address = Address::factory()->create([
            'created_by' => $user->id
        ]);

        $this->actingAsGoodFood();

        Http::fake([
            config('osm.uri') . "/*" => Http::response([
                [
                    'lat' => "0.909009",
                    'lon' => "-1.23233323"
                ]
            ])
        ]);

        $response = $this->putJson(self::BASE_PATH . "/{$address->id}", [
            'first_line' => "9 rue jsi",
            'second_line' => "BAT A",
            'city' => "Grenoble",
            'zip_code' => "90990",
            'country' => "France"
        ]);

        $response->assertOk();

    }

    /**
     * Test si un utilisateur avec le rôle goodfood peut supprimer
     * une adresse d'un autre utilisateur
     * 
     * @group addresses
     * @return void
     */
    public function test_goodfood_can_delete_another_address() : void {

        $user = $this->actingAsClient();

        $address = Address::factory()->create([
            'created_by' => $user->id
        ]);

        $this->actingAsGoodFood();

        $response = $this->deleteJson(self::BASE_PATH . "/{$address->id}");

        $response->assertNoContent();


    }
}
