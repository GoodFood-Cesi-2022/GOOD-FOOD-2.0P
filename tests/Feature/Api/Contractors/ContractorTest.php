<?php

namespace Tests\Feature\Api\Contractors;

use App\Enums\Roles;
use App\Models\Role;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Address;
use App\Models\Contractor;
use App\Models\Email;
use App\Models\RecipeType;
use Tests\Feature\Api\ApiCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ContractorTest extends ApiCase
{

    const BASE_PATH = "/api/contractors";

    /**
     * Test la création d'un franchisé
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

    /**
     * Test la fonctionnalité de lier des recettes au franchisé
     *
     * @group contractor
     * @return void
     */
    public function test_add_recipes_to_contractor() : void {

        $user = $this->actingAsContractor();

        $contractor = Contractor::factory()->create([
            'owned_by' => $user->id
        ]);

        $recipes = Recipe::factory()->count(10)->create([
            'recipe_type_id' => RecipeType::first()->id,
            'created_by' => $user->id,
        ]);

        $data = $recipes->map(function($recipe) {
            return [
                'price' => 10.11,
                'recipe_id' => $recipe->id
            ];
        });

        $response = $this->postJson(self::BASE_PATH . "/{$contractor->id}/recipes", ['recipes' => $data->toArray()]);

        $response->assertCreated()->assertJsonCount(10)->assertJsonStructure([
            '*' => [
                'price',
                'recipe' => [
                    'id'
                ]
            ]
        ]);

    }

    /**
     * Test la fonctionnalité pour retrouver les franchisés
     *
     * @group contractor
     * @return void
     */
    public function test_retreive_contractors() : void {

        $this->actingAsContractor();

        $contractors = Contractor::factory()->count(14)->create();

        $user = $this->actingAsClient();

        $response = $this->get(self::BASE_PATH);

        $response->assertOk()->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'phone',
                    'email',
                    'max_delivery_radius'
                ]
            ]
        ])->assertJsonCount(14, 'data');

    }


    /**
     * Test le filtre de pos pour limiter les résultats
     * des franchises 
     *
     * @group contractor
     * @return void
     */
    public function test_retreive_contractors_by_lon_lat() : void {

        $contractors = Contractor::factory()->count(20)->create();

        $address = Address::factory()->create([
            'lat' => 45.2062175,
            'lon' => 5.7847415
        ]);

        $contractor = Contractor::factory()->create([
            'address_id' => $address->id
        ]);

        $query = http_build_query([
            'pos' => [
                'lat' => 45.2094406,
                'lon' => 5.779301
            ]
        ]);

        $this->actingAsClient();

        $response = $this->get(self::BASE_PATH . "?$query");

        $response->assertOk()->assertJsonCount(1, 'data');

        $data = json_decode($response->getContent(), true);

        $this->assertTrue($data['data'][0]['id'] === $contractor->id);

    }


    /**
     * Test la fonctionnalité pour rechercher dans les franchisés 
     *
     * @group contractor
     * @return void
     */
    public function test_retreive_contractors_by_search_query() : void {

        $contractors = Contractor::factory()->count(15)->create();

        $email = Email::create([
            'email' => "09090909@uniquetest.frgt"
        ]);

        $contractor = Contractor::factory()->create([
            'name' => "IOIO987654IOIO",
            'phone' => "+3367000000",
            'email_id' => $email->id
        ]);

        $this->actingAsClient();

        // name
        $query = http_build_query([
            'search' => '987654'   
        ]);

        $response = $this->get(self::BASE_PATH . "?$query");

        $response->assertOk()->assertJsonCount(1, 'data');

        // phone
        $query = http_build_query([
            'search' => "000000"
        ]);

        $response = $this->get(self::BASE_PATH . "?$query");

        $response->assertOk()->assertJsonCount(1, 'data');

        // email
        $query = http_build_query([
            'search' => '@uniquetest.fr'
        ]);

        $response = $this->get(self::BASE_PATH . "?$query");

        $response->assertOk()->assertJsonCount(1, 'data');


    }

    /**
     * Test la récupération des recettes du franchisés
     *
     * @group contractor
     * @return void
     */
    public function test_retreive_contractor_recipes() : void {

        $contractors = Contractor::factory()->count(10)->create();

        foreach($contractors as $contractor) {

            $recipes = Recipe::factory()->count(3)->create([
                'recipe_type_id' => RecipeType::first()->id
            ]);

            $contractor->recipes()->sync($recipes->mapWithKeys(function($recipe) {
                return [
                    $recipe->id => [
                        'price' => 67
                    ]
                ];
            }));

        }

        $contractor = Contractor::factory()->create();

        $recipes = Recipe::factory()->count(5)->create([
            'recipe_type_id' => RecipeType::first()->id
        ]);

        $contractor->recipes()->sync($recipes->mapWithKeys(function($recipe) {
            return [
                $recipe->id => [
                    'price' => 10
                ]
            ];
        }));

        $this->actingAsClient();

        $response = $this->get(self::BASE_PATH . "/{$contractor->id}/recipes");

        $response->assertOk()->assertJsonCount(5, 'data')->assertJsonStructure([
            'data' => [
                '*' => [
                    'price',
                    'recipe' => [
                        'id'
                    ]
                ]
            ]
        ]);

    }

    /**
     * Test la mise à jour du prix de la recette d'un franchisé
     *
     * @group contractor
     * @return void
     */
    public function test_update_recipe_contractor() : void {

        $user = $this->actingAsContractor();

        $contractor = Contractor::factory()->create([
            'owned_by' => $user->id
        ]);

        $recipes = Recipe::factory()->count(5)->create([
            'recipe_type_id' => RecipeType::first()->id
        ]);

        $recipe_to_update = Recipe::factory()->create([
            'recipe_type_id' => RecipeType::first()->id
        ]);

        $contractor->recipes()->sync($recipes->mapWithKeys(function($recipe) {
            return [
                $recipe->id => [
                    'price' => 89
                ]
                ];
        }));

        $contractor->recipes()->attach([
            $recipe_to_update->id => [
                'price' => 9
            ]
        ]);

        $response = $this->putJson(self::BASE_PATH . "/{$contractor->id}/recipes/{$recipe_to_update->id}", [
            'price' => 22
        ]);

        $response->assertNoContent();

        $this->assertDatabaseHas('contractor_recipes', [
            'contractor_id' => $contractor->id,
            'recipe_id' => $recipe_to_update->id,
            'price' => 22.00
        ]);




    }

    /**
     * Test de la suppression du catalogue d'une recette du franchisé
     *
     * @group contractor
     * @return void
     */
    public function test_delete_recipe_contractor() : void {

        $user = $this->actingAsContractor();

        $contractor = Contractor::factory()->create([
            'owned_by' => $user->id
        ]);

        $recipes = Recipe::factory()->count(5)->create([
            'recipe_type_id' => RecipeType::first()->id
        ]);

        $recipe_to_update = Recipe::factory()->create([
            'recipe_type_id' => RecipeType::first()->id
        ]);

        $contractor->recipes()->sync($recipes->mapWithKeys(function($recipe) {
            return [
                $recipe->id => [
                    'price' => 89
                ]
                ];
        }));

        $contractor->recipes()->attach([
            $recipe_to_update->id => [
                'price' => 9
            ]
        ]);

        $response = $this->delete(self::BASE_PATH . "/{$contractor->id}/recipes/{$recipe_to_update->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('contractor_recipes', [
            'contractor_id' => $contractor->id,
            'recipe_id' => $recipe_to_update->id
        ]);
    }


}
