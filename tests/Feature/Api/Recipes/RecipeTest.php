<?php

namespace Tests\Feature\Api\Recipes;

use Mockery;
use Notification;
use Carbon\Carbon;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\RecipeType;
use Tests\Feature\Api\ApiCase;
use App\Notifications\NewRecipeStarAdded;
use App\Http\Resources\IngredientCollection;
use App\Mail\Recipes\NewRecipeStarAddedMail;

class RecipeTest extends ApiCase
{

    const BASE_PATH = "/api/recipes";

    /**
     * A basic feature test example.
     *
     * @group recipes
     * @return void
     */
    public function test_create_recipe() : void
    {
        
        $ingredients = Ingredient::factory()->count(2)->create();
        
        $this->actingAsContractor();

        $data = [
            'name' => "recipe 1",
            'description' => "Description de la recette 1",
            'star' => false,
            'base_price' => 10.27,
            'ingredients' => $ingredients->pluck('id')->toArray(),
            'recipe_type' => "appetizer",
        ];

        $response = $this->postJson(self::BASE_PATH, $data);

        $response->assertCreated()->assertJsonFragment([
            'name' => "recipe 1",
            'description' => "Description de la recette 1",
            'star' => false,
            'base_price' => 10.27,
            'ingredients' => (new IngredientCollection($ingredients))->toArray(request()),
            'recipe_type' => [
                'code' => "appetizer"
            ]
        ])->assertJsonStructure([
            'id',
            'created_at',
            'updated_at'
        ]);
        

    }

    /**
     * Test la création d'une recette star
     *
     * @group recipes
     * @return void
     */
    public function test_create_recette_star_mode_normal() : void {

        $ingredients = Ingredient::factory()->count(5)->create();

        $data = [
            'name' => "Recipe 2",
            'description' => "Description recipe 2",
            'star' => true,
            'base_price' => 10,
            'recipe_type' => "main_course",
            'ingredients' => $ingredients->pluck('id')->toArray()
        ];

        $this->actingAsContractor();

        $this->travelTo(today());

        $response = $this->post(self::BASE_PATH, $data);

        $response->assertForbidden();

        $user = $this->actingAsGoodFood();
        
        Notification::fake();
        
        $response = $this->post(self::BASE_PATH, $data);

        $response->assertCreated()->assertJsonFragment([
            'star' => true,
            'available_at' => Carbon::today()->add(config('recipes.delay'))->toJSON()
        ]);

        Notification::assertSentTo(
            [$user], NewRecipeStarAdded::class
        );


    }

    /**
     * Test la création d'une recette star en mode configuration
     * 
     * @group recipes
     * @return void
     */
    public function test_create_recette_star_mode_configuration() : void {

        set_app_mode('configuration');

        $this->actingAsGoodFood();

        $ingredients = Ingredient::factory()->count(2)->create();

        $data = [
            'name' => 'Recipe 3',
            'description' => 'Description recipe 3',
            'recipe_type' => 'dessert',
            'ingredients' => $ingredients->pluck('id')->toArray(),
            'star' => true,
            'base_price' => 13.98
        ];

        Notification::fake();

        $this->travelTo(today());

        $response = $this->postJson(self::BASE_PATH, $data);

        $response->assertCreated()->assertJsonFragment([
            'star' => true,
            'available_at' => today()->toJSON(),
        ]);

        Notification::assertNothingSent();

        set_app_mode('normal');

    }


    /**
     * Test le contenu du mail envoyés aux franchisés
     * A l'ajout d'une nouvelle recette star
     *
     * @group recipes
     * @group mailable 
     * @return void
     */
    public function test_mailable_new_recipe_star_content(): void {

        $recipe_type = RecipeType::first();

        $recipe = Recipe::factory()->star()->for($recipe_type, 'type')->create();

        $mailable = new NewRecipeStarAddedMail($recipe);

        $mailable->assertSeeInHtml($recipe->name);
        $mailable->assertSeeInHtml($recipe->available_at->format('d/m/Y'));


    }

    /**
     * Test le rollback de la création d'une recette
     *
     * @group recipes
     * @return void
     */
    public function test_error_new_recipe_db(): void {

        $mock = Mockery::mock(new \App\Models\Recipe);
        $mock->shouldReceive('save')->andThrow(new \Exception('any error'));
        $this->app->instance(\App\Models\Recipe::class, $mock);

        $ingredients = Ingredient::factory()->count(2)->create();
        
        $this->actingAsContractor();

        $data = [
            'name' => "recipe execption",
            'description' => "Description de la recette 1",
            'star' => false,
            'base_price' => 10.27,
            'ingredients' => $ingredients->pluck('id')->toArray(),
            'recipe_type' => "appetizer",
        ];

        $response = $this->postJson(self::BASE_PATH, $data);

        $response->assertStatus(500);

        $this->assertDatabaseMissing('recipes', [
            'name' => "recipe execption"
        ]);


    }


}
