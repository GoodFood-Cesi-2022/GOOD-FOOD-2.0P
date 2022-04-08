<?php

namespace Tests\Feature\Api\Roles;

use App\Http\Resources\RoleCollection;
use App\Models\Role;
use Tests\Feature\Api\ApiCase;

class RoleTest extends ApiCase {

    const BASE_PATH = '/api/roles';

    /**
     * Test la récupération des rôles
     *
     * @group roles
     * @return void
     */
    public function test_retreive_all_roles() : void {

        $this->actingAsClient();

        $response = $this->get(self::BASE_PATH);

        $response->assertStatus(200)->assertJsonStructure([
            '*' => [
                'code'
            ]
        ]);

    }


}