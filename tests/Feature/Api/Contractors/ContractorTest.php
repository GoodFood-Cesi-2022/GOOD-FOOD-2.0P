<?php

namespace Tests\Feature\Api\Contractors;

use App\Enums\Roles;
use App\Models\Role;
use App\Models\User;
use App\Models\Address;
use Tests\Feature\Api\ApiCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


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
        
        $owner = User::factory()->create();

        $owner->roles()->attach(Role::whereCode(Roles::contractor->value)->first()->id);

        $contractor = $this->actingAsContractor();

        $address = Address::factory()->for($contractor, 'createdBy')->create();

        $data = [
            'name' => "Contractor02",
            'phone' => "+338989898989",
            'email' => "contractor02@example.com",
            'max_delivery_radius' => 27,
            'address_id' => $address->id,
            'owned_by' => $owner->id
        ];

        $this->actingAsGoodFood();

        $response = $this->postJson(self::BASE_PATH, $data);

        unset($data['address_id']);
        unset($data['owned_by']);

        $response->assertCreated()->assertJsonFragment($data)->assertJsonStructure([
            'id',
            'created_at',
            'updated_at'
        ]);

        $content = json_decode($response->getContent(), true);

        $this->assertTrue($address->id === $content['address']['id']);

    }

    /**
     * Test qu'une franchise ne peut être détenu que par un franchisé ou un goodfood
     *
     * @group contractor
     * @return void
     */
    public function test_client_cant_be_owner() : void {

        $owner = User::factory()->create();

        $owner->roles()->attach(Role::whereCode(Roles::user->value)->first()->id);

        $contractor = $this->actingAsContractor();

        $address = Address::factory()->for($contractor, 'createdBy')->create();

        $data = [
            'name' => "Contractor02",
            'phone' => "+338989898989",
            'email' => "contractor02@example.com",
            'max_delivery_radius' => 27,
            'address_id' => $address->id,
            'owned_by' => $owner->id
        ];

        $this->actingAsGoodFood();

        $response = $this->postJson(self::BASE_PATH, $data);

        $response->assertUnprocessable()->assertJsonValidationErrorFor('owned_by');


    }


}
