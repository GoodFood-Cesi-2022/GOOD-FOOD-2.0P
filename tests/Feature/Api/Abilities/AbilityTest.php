<?php

namespace Tests\Feature\Api\Abilities;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Api\ApiCase;

class AbilityTest extends ApiCase
{
    /**
     * Test la focntionnalité de récupérer les abilities
     *
     * @group abilities
     * @return void
     */
    public function test_user_abilities() : void {
        
        $this->actingAsGoodFood();

        $query = http_build_query([
            'abilities' => [
                'user.create',
                'ingredient.create',
                'ingredientType.create'
            ],
        ]);

        $response = $this->get("/api/users/current?${query}");

        $response->assertOk()->assertJsonFragment([
            'abilities' => [
                'user.create' => true,
                'ingredient.create' => true,
                'ingredientType.create' => true
            ]
        ]);

    }

    /**
     * Test la focntionnalité de récupérer une permission
     * qui n'existe pas
     *
     * @group abilities
     * @return void
     */
    public function test_user_ability_requested_error() : void {

        $this->actingAsGoodFood();

        $query = http_build_query([
            'abilities' => [
                'poeut.poeut'
            ],
        ]);

        $response = $this->get("/api/users/current?${query}");

        $response->assertStatus(500);


    }
}
