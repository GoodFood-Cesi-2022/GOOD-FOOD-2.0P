<?php

namespace Tests\Feature\Api\Ingredients;

use App\Enums\Roles;
use App\Models\IngredientType;
use Tests\Feature\Api\ApiCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IngredientTypeTest extends ApiCase
{

    const BASE_PATH = '/api/ingredients/types';

    /**
     * Test la création d'un type d'ingrédient
     *
     * @group ingredient_types
     * @return void
     */
    public function test_create_on_type()
    {

        $this->actingAsGoodFood();

        $response = $this->postJson(self::BASE_PATH, [
            'name' => 'New Type',
            'description' => "-"
        ]);

        $response->assertStatus(201)->assertJson([
            'name' => "New Type",
            'code' => "new-type",
            'description' => "-"
        ]);
    }

    /**
     * Test la création d'un type d'ingrédient déjà existant
     * et son retour en erreur
     *
     * @group ingredient_types
     * @return void
     */
    public function test_create_type_allready_exists()
    {

        $this->actingAsGoodFood();
    
        IngredientType::factory()->create(['name' => "lourd", 'code' => "lourd"]);

        $response = $this->postJson(self::BASE_PATH, [
            'name' => 'lourd',
            'description' => "-"
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrorFor('name');

    }

    /**
     * Test la récupération de tous les types d'ingrédients
     *
     * @group ingredient_types
     * @return void
     */
    public function test_retreive_all_types()
    {

        IngredientType::factory()->count(20)->create();

        $this->actingAsClient();

        $response = $this->get(self::BASE_PATH);

        $response->assertStatus(200)->assertJsonStructure([
            '*' => [
                'code',
                'name',
                'description',
                'id',
                'created_at',
                'updated_at'
            ]
        ]);
        
    }


    /**
     * Test les accès en fonction des rôles
     *
     * @group ingredient_types
     * @dataProvider enpointDataProvider
     * @param string $verb
     * @param string $uri
     * @param int $expected_status
     * @param string $role
     * @param array $data
     * @return void
     */
    public function test_authorization_endpoints(string $verb, string $uri, int $expected_status, string $role, array $data = []) : void {

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
            ['POST', self::BASE_PATH, 403, Roles::user->value, ['name' => 'prout', 'description' => "fuck"]],
        ];

    }

}
