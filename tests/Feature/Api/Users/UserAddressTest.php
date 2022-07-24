<?php

namespace Tests\Feature\Api\Users;

use App\Models\User;
use App\Models\Address;
use Tests\Feature\Api\ApiCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAddressTest extends ApiCase
{

    const BASE_PATH = '/api/users/{user_id}/addresses';


    /**
     * A basic feature test example.
     *
     * @group user-address
     * @return void
     */
    public function test_add_user_address() : void
    {
        
        $user = $this->actingAsClient();
        
        $address = Address::factory()->create(['created_by' => $user->id]);

        $response = $this->postJson($this->getBasePath($user), [
            'name' => 'Home',
            'default' => false,
            'address_id' => $address->id
        ]);

        $response->assertOk()->assertJsonFragment([
            'name' => 'Home',
            'default' => false
        ])->assertJsonStructure([
            'id'
        ]);

        $content = json_decode($response->getContent(), true);

        $this->assertTrue($content['address']['id'] === $address->id);

    }

    /**
     * Test la mise à jour de l'adresse de l'utilisateur
     *
     * @group user-address
     * @return void
     */
    public function test_update_address() : void {

        $user = $this->actingAsClient();

        $address = Address::factory()->create(['created_by' => $user->id]);

        $user->addresses()->attach($address, [
            'name' => 'Home',
            'default' => true,
            'timezone' => 'FR'
        ]);


        $response = $this->putJson($this->getBasePath($user) . "/{$address->id}", [
            'name' => 'Office',
            'default' => false
        ]);

        $response->assertNoContent();

        $updated_address = $user->addresses()->whereAddressId($address->id)->first();

        $this->assertTrue($address->id === $updated_address->id);
        $this->assertFalse($updated_address->pivot->default);
        $this->assertTrue($updated_address->pivot->name === 'Office');

    }


    /**
     * Test la mise à jour d'une adresse d'un utilisateur par goodfood
     *
     * @group user-address
     * @return void
     */
    public function test_update_address_by_goodfood() : void {

        $user = $this->actingAsClient();

        $address = Address::factory()->create(['created_by' => $user->id]);

        $user->addresses()->attach($address, [
            'name' => 'Home',
            'default' => true,
            'timezone' => 'FR'
        ]);

        $this->actingAsGoodFood();

        $response = $this->putJson($this->getBasePath($user) . "/{$address->id}", [
            'name' => 'Office',
            'default' => false
        ]);

        $response->assertNoContent();

        $updated_address = $user->addresses()->whereAddressId($address->id)->first();

        $this->assertTrue($address->id === $updated_address->id);
        $this->assertFalse($updated_address->pivot->default);
        $this->assertTrue($updated_address->pivot->name === 'Office');

    }

    /**
     * Test le détachement de l'adresse du compte de l'utilisateur
     *
     * @group user-address
     * @return void
     */
    public function test_delete_address_from_user() : void {

        $user = $this->actingAsClient();

        $address = Address::factory()->create(['created_by' => $user->id]);

        $user->addresses()->attach($address, [
            'name' => 'Home',
            'default' => true,
            'timezone' => 'FR'
        ]);

        $response = $this->delete($this->getBasePath($user) . "/{$address->id}");

        $response->assertNoContent();

        $user_address = $user->addresses()->whereAddressId($address->id)->first();

        $this->assertNull($user_address);

    }

    /**
     * Test le détachement de l'adresse du compte de l'utilisateur par goodfood
     *
     * @group user-address
     * @return void
     */
    public function test_delete_address_from_user_by_goodfood() : void {

        $user = $this->actingAsClient();

        $address = Address::factory()->create(['created_by' => $user->id]);

        $user->addresses()->attach($address, [
            'name' => 'Home',
            'default' => true,
            'timezone' => 'FR'
        ]);

        $this->actingAsGoodFood();

        $response = $this->delete($this->getBasePath($user) . "/{$address->id}");

        $response->assertNoContent();

        $user_address = $user->addresses()->whereAddressId($address->id)->first();

        $this->assertNull($user_address);

    }


    /**
     * Test la récupération des adresses des utilisateurs
     *
     * @group user-address
     * @return void
     */
    public function test_get_user_addresses() {

        $user = $this->actingAsClient();

        $addresses = Address::factory()->count(2)->create(['created_by' => $user->id]);

        $user->addresses()->attach([
            $addresses[0]->id => ['name' => 'Home', 'default' => false, 'timezone' => 'FR'],
            $addresses[1]->id => ['name' => 'Office', 'default' => true, 'timezone' => 'EN'],
        ]);

        $response = $this->get($this->getBasePath($user));

        $response->assertOk()->assertJsonCount(2);

    }


    /**
     * Retourne le base path de l'API
     *
     * @param User $user
     * @return string
     */
    private function getBasePath(User $user) : string {

        return str_replace('{user_id}', $user->id, self::BASE_PATH);

    }  

}
