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
use App\Http\Resources\RecipeResource;
use App\Mail\Recipes\NewRecipeStarAddedMail;
use App\Models\User;
use App\Notifications\DeleteRecipe;
use Illuminate\Http\Request;

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
        
        $user = $this->actingAsContractor();

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
            'ingredients' => json_decode((new IngredientCollection($ingredients))->toJson(), true),
            'recipe_type' => [
                'code' => "appetizer"
            ]
        ])->assertJsonStructure([
            'id',
            'created_at',
            'updated_at'
        ]);

        $recipe_id = json_decode($response->content(), true)['id'];

        $recipe = Recipe::find($recipe_id);

        $this->assertTrue($user->id === $recipe->created_by);
        

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

    /**
     * Test pour récupérer toutes les recettes
     *
     * @group recipes
     * @return void
     */
    public function test_retreive_recipes() : void {

        $recipe_type = RecipeType::first();

        Recipe::factory()->count(10)->for($recipe_type, 'type')->create();

        $this->actingAsContractor();

        $response = $this->get(self::BASE_PATH);

        $response->assertOk();

    }

    /**
     * Test le filtrage sur le endpointget
     *
     * @group recipes
     * @return void
     */
    public function test_retreive_search_insensitive() : void {

        $recipe_type = RecipeType::first();

        Recipe::factory()->count(10)->for($recipe_type, 'type')->create();

        $recipe = Recipe::factory()->for($recipe_type, 'type')->create([
            'name' => "cAn searCHable CONTENt"
        ]);

        $this->actingAsContractor();

        $search = 'CaN SEARchABLE contenT';

        $response = $this->get(self::BASE_PATH . "?name=$search");

        $response->assertOk()->assertJsonCount(1, 'data')->assertJsonFragment(['id' => $recipe->id]);

    }

    /**
     * Test la mise en recette star d'une recette basique
     *
     * @group recipes
     * @return void
     */
    public function test_make_a_basic_recipe_to_star_conf_mode() : void {

        $this->actingAsGoodFood();

        $recipe_type = RecipeType::first();

        $recipe = Recipe::factory()->for($recipe_type, 'type')->create();

        set_app_mode('configuration');

        Notification::fake();

        $response = $this->postJson(self::BASE_PATH . "/{$recipe->id}/star");

        $response->assertNoContent();

        $recipe = $recipe->refresh();

        $this->assertTrue($recipe->star);
        
        Notification::assertNothingSent();

    }

    /**
     * Test la mise en recette star d'une recette basique
     *
     * @group recipes
     * @return void
     */
    public function test_make_a_basic_recipe_to_star_conf_normal() : void {

        $user = $this->actingAsGoodFood();

        $recipe_type = RecipeType::first();

        $recipe = Recipe::factory()->for($recipe_type, 'type')->create();

        set_app_mode('normal');

        Notification::fake();

        $response = $this->postJson(self::BASE_PATH . "/{$recipe->id}/star");

        $response->assertNoContent();

        $recipe = $recipe->refresh();

        $this->assertTrue($recipe->star);
        
        Notification::assertSentTo(
            [$user], NewRecipeStarAdded::class
        );

    }

    
    /**
     * Test la mise en recette basique d'une recette star
     *
     * @group recipes
     * @return void
     */
    public function test_make_a_star_request_to_basic() : void {

        $this->actingAsGoodFood();

        $recipe_type = RecipeType::first();

        $recipe = Recipe::factory()->star()->for($recipe_type, 'type')->create();

        $response = $this->postJson(self::BASE_PATH . "/{$recipe->id}/unstar");

        $response->assertNoContent();

        $recipe = $recipe->refresh();

        $this->assertFalse($recipe->star);

    }


    /**
     * Test la mise en recette basique d'une recette star
     *
     * @group recipes
     * @return void
     */
    public function test_retreive_recipe_types() : void {

        $this->actingAsClient();

        $response = $this->get(self::BASE_PATH . "/types");

        $response->assertOk()->assertJsonStructure([
            '*' => [
                'code'
            ]
        ]);

    }


    /**
     * Test la mise en recette basique d'une recette star
     *
     * @group recipes
     * @return void
     */
    public function test_retreive_recipe_ingredients() : void {

        $recipe_type = RecipeType::first();

        $ingredients = Ingredient::factory()->count(5);

        $recipe = Recipe::factory()
                    ->star()
                    ->for($recipe_type, 'type')
                    ->has($ingredients, 'ingredients')
                    ->create();

        $this->actingAsClient();

        $response = $this->get(self::BASE_PATH . "/{$recipe->id}/ingredients");

        $response->assertOk()->assertJsonCount(5);

        $content = collect(json_decode($response->content(), true));

        $ingredients = $recipe->refresh()->ingredients;

        $this->assertTrue($content->pluck('id')->toArray() === $ingredients->pluck('id')->toArray());


    }

    /**
     * Test la modification d'une recette basique
     *
     * @group recipes
     * @return void
     */
    public function test_update_recipe_basic_infos() : void {

        $user = $this->actingAsContractor();

        $recipe_type = RecipeType::whereCode('main_course')->first();

        $ingredients = Ingredient::factory()->count(5);

        $recipe = Recipe::factory()
                        ->for($recipe_type, 'type')
                        ->has($ingredients, 'ingredients')
                        ->for($user, 'createdBy')
                        ->create();

        $new_data = [
            'name' => "Super gâteau au chocolat",
            'description' => "Nouvelle recette avec encore + de chocolat",
            'base_price' => 6.90,
            'star' => false
        ];

        Notification::fake();

        $response = $this->putJson(self::BASE_PATH . "/{$recipe->id}", 
            array_merge($new_data, [
                'recipe_type' => 'dessert',
                'ingredients' => $recipe->ingredients->pluck('id')->toArray()
            ])
        );

        $response->assertNoContent();

        $recipe = $recipe->refresh();

        $this->assertEqualsCanonicalizing($recipe->only([
            'name', 'description', 'base_price', 'star'
        ]), $new_data);

        $this->assertTrue($recipe->type->code === 'dessert');

        Notification::assertNothingSent();

    }

    /**
     * test le passage et le dispatch de la notification d'une recette
     * basique en recette star
     *
     * @group recipes
     * @return void
     */
    public function test_update_basic_recipe_to_star_recipe() : void {

        $user = $this->actingAsGoodFood();

        $recipe_type = RecipeType::whereCode('main_course')->first();

        $ingredients = Ingredient::factory()->count(5);

        $recipe = Recipe::factory()
                        ->for($recipe_type, 'type')
                        ->has($ingredients, 'ingredients')
                        ->for($user, 'createdBy')
                        ->create();

        $new_data = [
            'name' => "Super gâteau au chocolat",
            'description' => "Nouvelle recette avec encore + de chocolat",
            'base_price' => 6.90,
            'star' => true
        ];

        Notification::fake();

        $response = $this->putJson(self::BASE_PATH . "/{$recipe->id}", 
            array_merge($new_data, [
                'recipe_type' => 'dessert',
                'ingredients' => $recipe->ingredients->pluck('id')->toArray()
            ])
        );

        $response->assertNoContent();

        Notification::assertSentTo([$user], NewRecipeStarAdded::class);

    }

    /**
     * Test la politique de mise à jour d'une recette
     *
     * @group recipes
     * @return void
     */
    public function test_update_policy() : void {

        $user = $this->actingAsContractor();

        $user_random = User::factory()->create();

        $recipe_type = RecipeType::whereCode('main_course')->first();

        $recipe = Recipe::factory()
                        ->for($recipe_type, 'type')
                        ->create();

        $new_data = [
            'name' => "Super gâteau au chocolat",
            'description' => "Nouvelle recette avec encore + de chocolat",
            'base_price' => 6.90,
            'star' => false
        ];

        $recipe->setCreatedBy($user_random);
        $recipe->save();

        $response = $this->putJson(self::BASE_PATH . "/{$recipe->id}", 
            array_merge($new_data, [
                'recipe_type' => 'dessert',
                'ingredients' => []
            ])
        );

        $response->assertForbidden();

        $recipe->setCreatedBy($user);
        $recipe->save();

        $response = $this->putJson(self::BASE_PATH . "/{$recipe->id}", 
            array_merge($new_data, [
                'star' => true,
                'recipe_type' => 'dessert',
                'ingredients' => []
            ])
        );

        $response->assertForbidden();

        $this->actingAsGoodFood();

        $response = $this->putJson(self::BASE_PATH . "/{$recipe->id}", 
            array_merge($new_data, [
                'recipe_type' => 'dessert',
                'ingredients' => []
            ])
        );

        $response->assertNoContent();


    }

    /**
     * Test l'ajour d'un ingrédient à la recette
     *
     * @group recipes
     * @return void
     */
    public function test_update_recipe_add_ingredient() : void {

        $user = $this->actingAsContractor();

        $recipe_type = RecipeType::whereCode('main_course')->first();

        $ingredients = Ingredient::factory()->count(5);

        $recipe = Recipe::factory()
                        ->for($recipe_type, 'type')
                        ->has($ingredients, 'ingredients')
                        ->create();

        $new_data = [
            'name' => "Super gâteau au chocolat",
            'description' => "Nouvelle recette avec encore + de chocolat",
            'base_price' => 6.90,
            'star' => false
        ];

        $new_ingredient = Ingredient::factory()->create();

        $response = $this->putJson(self::BASE_PATH . "/{$recipe->id}", 
            array_merge($new_data, [
                'recipe_type' => 'dessert',
                'ingredients' => array_merge([$new_ingredient->id], $recipe->ingredients->pluck('id')->toArray())
            ])
        );

        $response->assertNoContent();

        $recipe = $recipe->refresh();
        $this->assertTrue($recipe->ingredients->where('id', $new_ingredient->id)->first() instanceof Ingredient);



    }

    /**
     * Test la suppression d'un ingrédient
     * 
     * @group recipes
     * @return void
     */
    public function test_update_recipe_remove_ingredient() : void {

        $user = $this->actingAsContractor();

        $recipe_type = RecipeType::whereCode('main_course')->first();

        $ingredients = Ingredient::factory()->count(5);

        $recipe = Recipe::factory()
                        ->for($recipe_type, 'type')
                        ->has($ingredients, 'ingredients')
                        ->create();

        $new_data = [
            'name' => "Super gâteau au chocolat",
            'description' => "Nouvelle recette avec encore + de chocolat",
            'base_price' => 6.90,
            'star' => false
        ];

        $recipe_ingredients = $recipe->ingredients->pluck('id')->toArray();

        $old_ingredients_id = array_pop($recipe_ingredients);

        $response = $this->putJson(self::BASE_PATH . "/{$recipe->id}", 
            array_merge($new_data, [
                'recipe_type' => 'dessert',
                'ingredients' => $recipe_ingredients
            ])
        );

        $response->assertNoContent();

        $recipe = $recipe->refresh();
        $this->assertTrue($recipe->ingredients->where('id', $old_ingredients_id)->first() === null);

    }

    /**
     * Test la levée d'une exception lors de la modification d'une recette
     *
     * @group recipes
     * @return void
     */
    public function test_recipe_update_rollback() {

        $this->actingAsGoodFood();

        $recipe_type = RecipeType::whereCode('main_course')->first();

        $ingredients = Ingredient::factory()->count(5);

        $recipe = Recipe::factory()
                        ->for($recipe_type, 'type')
                        ->has($ingredients, 'ingredients')
                        ->create();

        $mock = Mockery::mock(\App\Models\Ingredient::class);
        $mock->shouldReceive('get')->andThrow(new \Exception('any error'));
        $this->app->instance(\App\Models\Ingredient::class, $mock);

        $new_data = [
            'name' => "Super gâteau au chocolat",
            'description' => "Nouvelle recette avec encore + de chocolat",
            'base_price' => 6.90,
            'star' => false
        ];

        $recipe_ingredients = $recipe->ingredients->pluck('id')->toArray();

        $response = $this->putJson(self::BASE_PATH . "/{$recipe->id}",
            array_merge($new_data, [
                'recipe_type' => 'dessert',
                'ingredients' => $recipe_ingredients
            ])
        );

        $response->assertStatus(500);

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'name' => $recipe->name,
            'description' => $recipe->description,
            'star' => $recipe->star
        ]);

    }
    
    /**
     * Test la supression d'une recette basique
     *
     * @group recipes
     * @return void
     */
    public function test_delete_basic_recipe() : void {

        $user = $this->actingAsContractor();

        $recipe_type = RecipeType::whereCode('main_course')->first();

        $ingredients = Ingredient::factory()->count(5);

        $recipe = Recipe::factory()
                        ->for($recipe_type, 'type')
                        ->has($ingredients, 'ingredients')
                        ->create();

        Notification::fake();

        $response = $this->delete(self::BASE_PATH . "/{$recipe->id}");

        $response->assertNoContent();

        $recipe = $recipe->refresh();

        $this->assertTrue($recipe->trashed_at->equalTo(Carbon::today()->add(config('recipes.delay_before_delete'))));
        $this->assertNull($recipe->deleted_at);

        Notification::assertSentTo([$user], DeleteRecipe::class);

    }

    /**
     * Test la suppression d'une recette en mode normal
     *
     * @group recipes
     * @return void
     */
    public function test_delete_recipe_conf_mode() : void {

        set_app_mode('configuration');

        $this->actingAsContractor();

        $recipe_type = RecipeType::whereCode('main_course')->first();

        $ingredients = Ingredient::factory()->count(5);

        $recipe = Recipe::factory()
                        ->for($recipe_type, 'type')
                        ->has($ingredients, 'ingredients')
                        ->create();

        Notification::fake();

        $response = $this->delete(self::BASE_PATH . "/{$recipe->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted($recipe);

        Notification::assertNothingSent();
        
    }

    /**
     * Test qu'une recette créé par un franchisé peut-être supprimé par
     * un admin goodfood
     *
     * @group recipes
     * @return void
     */
    public function test_recipe_can_be_deleted_by_admin() {

        set_app_mode('configuration');

        $this->actingAsContractor();

        $recipe_type = RecipeType::whereCode('main_course')->first();

        $ingredients = Ingredient::factory()->count(5);

        $recipe = Recipe::factory()
                        ->for($recipe_type, 'type')
                        ->has($ingredients, 'ingredients')
                        ->create();

        $this->actingAsGoodFood();

        $response = $this->delete(self::BASE_PATH . "/{$recipe->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted($recipe);


    }

    /**
     * Test la suppression en soft delete des recettes marquées en trashed
     *
     * @group recipes
     * @return void
     */
    public function test_command_prune_recipes() : void {

        $this->actingAsContractor();

        $recipe_type = RecipeType::whereCode('main_course')->first();
        $ingredients = Ingredient::factory()->count(5);

        $trashed_at = Carbon::today()->add(config('recipes.delay_before_delete'));

        $recipes = Recipe::factory()->for($recipe_type, 'type')->count(10)->create();

        $recipes_to_trash = Recipe::factory()
                            ->for($recipe_type, 'type')
                            ->has($ingredients, 'ingredients')
                            ->toTrashAt($trashed_at)
                            ->count(5)
                            ->create();

        $this->travelTo($trashed_at);

        $this->artisan('recipes:delete')->assertExitCode(0);

        $recipes->each(function($recipe) {
            $this->assertDatabaseHas('recipes', [
                'id' => $recipe->id,
                'trashed_at' => null,
                'deleted_at' => null
            ]);
        });

        $recipes_to_trash->each(function($recipe) {
            $this->assertSoftDeleted($recipe);
        });



    }

    /**
     * test la récupération d'une recette
     *
     * @group recipes
     * @return void
     */
    public function test_retreive_recipe() : void {

        $recipe_type = RecipeType::whereCode('main_course')->first();
        $ingredients = Ingredient::factory()->count(5);

        $recipe = Recipe::factory()->for($recipe_type, 'type')->has($ingredients, 'ingredients')->create();

        $this->actingAsClient();

        $response = $this->get(self::BASE_PATH . "/{$recipe->id}");

        $response->assertOk()->assertJsonFragment([
            'id' => $recipe->id
        ]);


    }

}
