<?php

namespace Tests\Feature\Api\Recipes;

use App\Models\File;
use App\Models\Recipe;
use App\Models\RecipePicture;
use App\Models\RecipeType;
use Tests\Feature\Api\ApiCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipePictureTest extends ApiCase
{
    
    const BASE_PATH = "/api/recipes/{recipe_id}/pictures";


    /**
     * Test l'attachement d'un fichier comme photo de la recette
     *
     * @group recipe-pictures
     * @return void
     */
    public function test_attach_picture_to_recipe() : void {

        $this->actingAsGoodFood();

        $recipe_type = RecipeType::first();

        $recipe = Recipe::factory()->for($recipe_type, 'type')->create();

        $file = File::factory()->image()->create();

        $response = $this->postJson($this->getBasePath($recipe->id), [
            'file_uuid' => $file->uuid
        ]);

        $response->assertNoContent();

        $recipe = $recipe->refresh();

        $this->assertTrue($recipe->pictures->first()->id === $file->id);

        $file = File::factory()->image()->create();

        $response = $this->postJson($this->getBasePath($recipe->id), [
            'file_uuid' => $file->uuid
        ]);

        $response->assertNoContent();

        $recipe = $recipe->refresh();

        $this->assertTrue($recipe->pictures()->count() === 2);

    }


    /**
     * Test les règles d'attachements
     *
     * @group recipe-pictures
     * @return void
     */
    public function test_attach_picture_policies() : void {

        $this->actingAsContractor();

        $recipe_type = RecipeType::first();

        $recipe = Recipe::factory()->for($recipe_type, 'type')->create();

        $file = File::factory()->image()->create();

        $response = $this->postJson($this->getBasePath($recipe->id), [
            'file_uuid' => $file->uuid
        ]);

        $response->assertNoContent();

        $this->actingAsGoodFood();

        $file = File::factory()->image()->create();

        $response = $this->postJson($this->getBasePath($recipe->id), [
            'file_uuid' => $file->uuid
        ]);

        $response->assertNoContent();

        $this->actingAsGoodFood();

        $recipe_type = RecipeType::first();

        $recipe = Recipe::factory()->for($recipe_type, 'type')->create();

        $file = File::factory()->image()->create();

        $response = $this->postJson($this->getBasePath($recipe->id), [
            'file_uuid' => $file->uuid
        ]);

        $response->assertNoContent();

        $this->actingAsContractor();

        $file = File::factory()->image()->create();

        $response = $this->postJson($this->getBasePath($recipe->id), [
            'file_uuid' => $file->uuid
        ]);

        $response->assertForbidden();
    }

    /**
     * Test le détachement d'une photo de la recette
     *
     * @group recipe-pictures
     * @return void
     */
    public function test_detach_picture() : void {

        $this->actingAsContractor();

        $recipe_type = RecipeType::first();

        $files = File::factory()->image()->count(2);

        $recipe = Recipe::factory()
                    ->has($files, 'pictures')
                    ->for($recipe_type, 'type')
                    ->create();

        $pictures = $recipe->pictures;

        $response = $this->deleteJson($this->getBasePath($recipe->id) . '/' . $pictures[1]->uuid);

        $response->assertNoContent();

        
        $this->assertDatabaseHas('recipe_pictures', [
            'recipe_id' => $recipe->id,
            'file_id' => $pictures[0]->id
        ]);

        $this->assertDatabaseMissing('recipe_pictures', [
            'recipe_id' => $recipe->id,
            'file_id' => $pictures[1]->id
        ]);

    }


    /**
     * Test les règles de détachement
     *
     * @group recipe-pictures
     * @return void
     */
    public function test_detach_picture_policies() : void {

        $user = $this->actingAsGoodFood();

        $recipe_type = RecipeType::first();

        $files = File::factory()->image()->count(1);

        $recipe = Recipe::factory()
                    ->has($files, 'pictures')
                    ->for($recipe_type, 'type')
                    ->for($user, 'createdBy')
                    ->create();

        $this->actingAsContractor();

        $pictures = $recipe->pictures;

        $response = $this->deleteJson($this->getBasePath($recipe->id) . '/' . $pictures[0]->uuid);

        $response->assertForbidden();
        

    }


    /**
     * Test les règles de vérification de détachement
     *
     * @group recipe-pictures
     * @return void
     */
    public function test_detach_picture_file_not_attached_to_recipe() : void {

        $this->actingAsGoodFood();

        $recipe_type = RecipeType::first();

        $files = File::factory()->image()->count(1);

        $recipe = Recipe::factory()
                    ->has($files, 'pictures')
                    ->for($recipe_type, 'type')
                    ->create();

        $file_not_exist = File::factory()->image()->create();

        $response = $this->deleteJson($this->getBasePath($recipe->id) . '/' . $file_not_exist->uuid);

        $response->assertUnprocessable()->assertJsonValidationErrorFor('picture');


    }


    /**
     * Remplace l'ID de l'URI par l'ID passé
     *
     * @param integer|null $recipe_id
     * @return string
     */
    private static function getBasePath(?int $recipe_id) : string {
        return str_replace('{recipe_id}', $recipe_id, self::BASE_PATH);
    }


}
