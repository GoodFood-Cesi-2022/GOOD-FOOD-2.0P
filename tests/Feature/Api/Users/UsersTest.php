<?php

namespace Tests\Feature\Api\Users;

use App\Enums\Roles;
use App\Models\User;
use Tests\Feature\Api\ApiCase;
use Illuminate\Testing\Fluent\AssertableJson;

class UsersTest extends ApiCase {

    const BASE_PATH = '/api/users';


    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->count(50)->create();        
    }

    /**
     * Test la récupération des users
     *
     * @group users
     * @return void
     */
    public function test_retreive_all_users() : void {

        $this->actingAsGoodFood();

        $response = $this->get(self::BASE_PATH);

        $response->assertStatus(200)->assertJsonCount(50, 'data');        

    }


    /**
     * Test la récupération d'un utilisateur
     *
     * @group users
     * @return void
     */
    public function test_retreive_one_user() : void {
        
        $user = User::factory()->create();

        $this->actingAsGoodFood();

        $response = $this->get(self::BASE_PATH . '/' . $user->id);

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $user->id
                ]);
        
    }

    /**
     * Test la récupération de l'utilisateur courant
     *
     * @group users
     * @dataProvider roleDataProvider
     * @param string $code
     * @return void
     */
    public function test_retreive_current_user(string $code) : void {
        
        $user = $this->actingLike($code);

        $response = $this->get(self::BASE_PATH . '/current');

        $response->assertStatus(200)->assertJsonFragment([
            'id' => $user->id
        ]);

    }


    /**
     * Test la création d'un utilisateur interne
     *
     * @group users
     * @return void
     */
    public function test_create_user() : void {
        
        $this->actingAsGoodFood();

        $data = [
            'firstname' => 'risotto',
            'lastname' => 'miam',
            'phone' => '0635406120',
            'email' => 'miam@example.com'
        ];

        $response = $this->postJson(self::BASE_PATH, $data);

        $response->assertStatus(201)
                ->assertJson($data)
                ->assertJson(fn (AssertableJson $json) =>
                    $json->has('id')->etc()
                );

        $body = json_decode($response->getContent(), true);

        $user = User::find($body['id']);

        $this->assertTrue(is_string($user->confirmable_token));

    }

    /**
     * Test les accès en fonction des rôles
     *
     * @group users
     * @dataProvider enpointDataProvider
     * @param string $verb
     * @param string $uri
     * @param int $expected_status
     * @param string $role
     * @param array $data
     * @return void
     */
    public function test_authorization_endpoints(string $verb, string $uri, int $expected_status, string $role, array $data = []) : void {

        $user = User::factory()->create();

        $this->actingLike($role);

        $response = $this->json($verb, str_replace(':user', $user->id, $uri), $data);

        $response->assertStatus($expected_status);

    }

    /**
     * Tous les endpoitns users à tester
     *
     * @return array
     */
    protected function enpointDataProvider() : array {

        return [
            ['GET', self::BASE_PATH, 403, Roles::contractor->value],
            ['GET', self::BASE_PATH . '/:user' , 403, Roles::contractor->value],
            ['POST', self::BASE_PATH, 403, Roles::contractor->value],
            ['GET', self::BASE_PATH, 403, Roles::user->value],
            ['GET', self::BASE_PATH . '/:user' , 403, Roles::user->value],
            ['POST', self::BASE_PATH, 403, Roles::user->value],
        ];

    }


}