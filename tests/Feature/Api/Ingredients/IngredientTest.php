<?php

namespace Tests\Feature\Api\Ingredients;

use Mockery;
use App\Enums\Roles;
use App\Models\Ingredient;
use App\Models\IngredientType;
use Tests\Feature\Api\ApiCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IngredientTest extends ApiCase
{

    const BASE_PATH = "/api/ingredients";

    /**
     * Test la création d'un ingredient
     *
     * @group ingredients
     * @return void
     */
    public function test_create_ingredient() : void {

        $this->actingAsContractor();

        $response = $this->postJson(self::BASE_PATH, [
            'name' => "ingtest",
            'allergen' => false,
            'types' => [
                'viandes',
                'poissons'
            ]
        ]);

        $response->assertCreated()->assertJson([
            'name' => "ingtest",
            'allergen' => false,
            'types' => [
                [
                    "code" => "poissons"
                ],
                [
                    "code" => "viandes"
                ]
            ]
        ]);

    }


    /**
     * Test la création d'un ingrédient allergène
     * 
     * @group ingredients 
     * @return void
     */
    public function test_create_allergen_ingredient() : void {

        $this->actingAsContractor();

        $response = $this->postJson(self::BASE_PATH, [
            'name' => 'je pique',
            'allergen' => true,
            'types' => []
        ]);

        $response->assertCreated()->assertJson([
            'name' => 'je pique',
            'allergen' => true
        ]);

    }

    /**
     * test l'erreur de traitement sur la création d'un ingredient avec un ingredient qui n'existe pas
     *
     * @group ingredients
     * @return void
     */
    public function test_create_ingredient_type_doesnt_exists() : void {

        $this->actingAsContractor();

        $response = $this->postJson(self::BASE_PATH, [
            'name' => "err",
            'allergen' => false,
            'types' => [
                "je_n_existe_pas"
            ]
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrorFor('types.0');

    }

    /**
     * Test l'appartenance de l'ingrédient à l'utilisateur logger
     *
     * @group ingredients
     * @return void
     */
    public function test_belonging_logged_user() : void {

        $user = $this->actingAsContractor();

        $response = $this->postJson(self::BASE_PATH, [
            'name' => "ingredientisownedby",
            'allergen' => true,
            'types' => []
        ]);

        $response->assertCreated();

        $data = json_decode($response->getOriginalContent(), true);

        $ingredient = Ingredient::find($data['id']);

        $this->assertTrue($ingredient->isCreatedBy($user->id));

        $this->assertTrue($ingredient->createdBy->id === $user->id);

    }


    /**
     * Test transaction
     *
     * @group ingredients
     * @return void
     */
    public function test_create_fail_in_saving() : void {

        $this->actingAsContractor();

        $mock = Mockery::mock(new \App\Models\Ingredient);
        $mock->shouldReceive('create')->andThrow(new \Exception('any error'));
        $this->app->instance(\App\Models\Ingredient::class, $mock);

        $response = $this->postJson(self::BASE_PATH, [
            'name' => 'thrown exeception',
            'allergen' => false,
            'types' => []
        ]);

        $this->assertDatabaseMissing('ingredients', [
            'name' => 'thrown exception'
        ]);

        $response->assertStatus(500);

    }

    /**
     * Test la récupération de tous les ingrédients
     *
     * @group ingredients
     * @return void
     */
    public function test_all_ingredients() {

        $this->actingAsContractor();

        $response = $this->get(self::BASE_PATH);

        $response->assertStatus(200)->assertJsonStructure([
            'data',
            'links',
            'meta'
        ]);

    }


    /**
     * test update
     *
     * @group ingredients
     * @return void
     */
    public function test_update_ingredient() : void {

        $user = $this->actingAsContractor();

        $ingredient = Ingredient::factory([
            'created_by' => $user->id
        ])->create();

        $response = $this->putJson(self::BASE_PATH . "/{$ingredient->id}", [
            'name' => "updatedname",
            'types' => [
                'legumes',
                'viandes'
            ],
            'allergen' => true
        ]);

        $ingredient = $ingredient->refresh();

        $this->assertTrue($ingredient->name === "updatedname");
        $this->assertTrue($ingredient->allergen === true);
        $this->assertTrue($ingredient->types->contains(IngredientType::whereCode('legumes')->first()));
        $this->assertTrue($ingredient->types->contains(IngredientType::whereCode('viandes')->first()));

        $response->assertNoContent();

    }


    /**
     * Test update policy
     *
     * @group ingredients
     * @return void
     */
    public function test_update_policy() : void {

        $ingredient = Ingredient::factory()->create();

        // Change user
        $this->actingAsContractor();

        $response = $this->putJson(self::BASE_PATH . "/{$ingredient->id}", []);

        $response->assertForbidden();

        // Goodfood
        $this->actingAsGoodFood();

        $response = $this->putJson(self::BASE_PATH . "/{$ingredient->id}", [
            'name' => "auth",
            'allergen' => false,
            'types' => []
        ]);

        $response->assertNoContent();

    }

    /**
     * Test la suppression
     *
     * @group ingredients
     * @return void
     */
    public function test_delete() : void {

        $user = $this->actingAsContractor();

        $ingredient = Ingredient::factory([
            'created_by' => $user->id
        ])->create();

        $response = $this->deleteJson(self::BASE_PATH . "/{$ingredient->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted($ingredient);

    }

    /**
     * Test policy delete
     *
     * @group ingredients
     * @return void
     */
    public function test_delete_policy() : void {

        $ingredient = Ingredient::factory()->create();

        $this->actingAsContractor();

        $response = $this->deleteJson(self::BASE_PATH . "/{$ingredient->id}");

        $response->assertForbidden();

        $this->actingAsGoodFood();

        $response = $this->deleteJson(self::BASE_PATH . "/{$ingredient->id}");

        $response->assertNoContent();

    }


    /**
     * Test les accès en fonction des rôles
     *
     * @group ingredients
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

        $ingredient = Ingredient::factory()->create();

        $uri = str_replace(':ingredient', $ingredient->id, $uri);

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
            ['POST', self::BASE_PATH, 403, Roles::user->value],
            ['PUT', self::BASE_PATH . '/:ingredient', 403, Roles::user->value],
            ['DELETE', self::BASE_PATH . '/:ingredient', 403, Roles::user->value],
            ['GET', self::BASE_PATH, 403, Roles::user->value],
        ];

    }

}
