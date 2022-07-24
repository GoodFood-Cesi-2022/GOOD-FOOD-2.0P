<?php

namespace Tests\Feature\Api\Users;

use App\Enums\Roles;
use App\Models\Role;
use App\Models\User;
use Tests\Feature\Api\ApiCase;

class UserRoleTest extends ApiCase {

    const BASE_PATH = '/api/users';

    /**
     * Path with user
     *
     * @var string
     */
    protected string $path;

    /**
     * User
     *
     * @var User
     */
    protected User $user;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->path = self::BASE_PATH . '/' . $this->user->id . '/roles';
    }

    /**
     * Test retreive roles for users
     * 
     * @group user_role
     * @return void
     */
    public function test_retreive_roles_for_one_user() : void {
        
        $this->user->roles()->attach(Role::whereCode(Roles::user->value)->first()->id);

        $this->actingAsGoodFood();

        $response = $this->get($this->path);

        $response->assertStatus(200)->assertJson([
            '0' => [
                'code' => 'user'
            ]
        ]);

    }

    /**
     * Test ajout d'un role à l'utilisateur
     *
     * @group user_role
     * @return void
     */
    public function test_add_role_to_one_user() : void {
        
        $user = User::factory()->create();

        $this->actingAsGoodFood();

        $response = $this->postJson(self::BASE_PATH . '/' . $user->id . '/roles', [
            'code' => 'contractor'
        ]);

        $response->assertStatus(204);

        $this->assertTrue($user->roles()->whereCode(Roles::contractor->value)->count() === 1);

    }

    /**
     * Test la suppresiion d'un role à l'utilisateur
     *
     * @group user_role
     * @return void
     */
    public function test_detach_role_to_one_user() : void {
        
        $user = User::factory()->create();

        $role_id = Role::whereCode(Roles::contractor->value)->first()->id;

        $user->roles()->attach($role_id);

        $this->actingAsGoodFood();

        $response = $this->deleteJson(self::BASE_PATH . '/' . $user->id . '/roles/' . $role_id);

        $response->assertStatus(204);

        $this->assertTrue($user->roles()->whereCode(Roles::contractor->value)->count() === 0);

    }

    /**
     * test le cas où pas de rôle passé pour vérification
     *
     * @group user_role
     * @return void
     */
    public function test_has_one_of_roles_empty() {

        $user = User::factory()->create();

        $role_id = Role::whereCode(Roles::contractor->value)->first()->id;

        $user->roles()->attach($role_id);

        $this->assertFalse($user->hasOneOfRoles([]));

    }

    /**
     * Test le chargment des relations par query parameter
     *
     * @group user_role
     * @return void
     */
    public function test_user_relation_load() : void {

        $this->actingAsGoodFood();

        $user = User::factory()->create();

        $role_id = Role::whereCode(Roles::contractor->value)->first()->id;

        $user->roles()->attach($role_id);

        $response = $this->get(self::BASE_PATH . "/{$user->id}?includes[]=roles");

        $response->assertOk();

        $this->assertRelationIsPresent($response, 'roles');


    }

    /**
     * Test les accès en fonction des rôles
     *
     * @group user_role
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
        $uri = str_replace(':user', $user->id, $uri);
        $uri = str_replace(':role', 'contractor', $uri);

        $this->actingLike($role);

        $response = $this->json($verb, $uri, $data);

        $response->assertStatus($expected_status);

    }

    /**
     * Tous les endpoitns users à tester
     *
     * @return array
     */
    protected function enpointDataProvider() : array {

        return [
            ['GET', self::BASE_PATH . '/:user/roles', 403, 'contractor'],
            ['POST', self::BASE_PATH . '/:user/roles', 403, 'contractor'],
            ['DELETE', self::BASE_PATH . '/:user/roles/:role', 403, 'contractor'],
            ['GET', self::BASE_PATH . '/:user/roles', 403, 'user'],
            ['POST', self::BASE_PATH . '/:user/roles', 403, 'user'],
            ['DELETE', self::BASE_PATH . '/:user/roles/:role', 403, 'user'],
        ];

    }




    
}