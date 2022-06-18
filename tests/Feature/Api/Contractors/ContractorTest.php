<?php

namespace Tests\Feature\Api\Contractors;

use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Api\ApiCase;


class ContractorTest extends ApiCase
{

    const BASE_PATH = "/api/contractors";

    /**
     * A basic feature test example.
     *
     * @group contractor
     * @return void
     */
    public function test_create_contractor() : void 
    {
        
        $contractor = $this->actingAsContractor();

        $address = Address::factory()->for($contractor, 'createdBy')->create();

        $data = [
            'name' => "Contractor02",
            'phone' => "+338989898989",
            'email' => "contractor02@example.com",
            'max_delivery_radius' => 27,
            'address_id' => $address->id
        ];

        $this->actingAsGoodFood();

        $response = $this->postJson(self::BASE_PATH, $data);

        unset($data['address_id']);

        $response->assertCreated()->assertJsonFragment($data)->assertJsonStructure([
            'id',
            'created_at',
            'updated_at'
        ]);

        $content = json_decode($response->getContent(), true);

        $this->assertTrue($address->id === $content['address']['id']);

    }
}
