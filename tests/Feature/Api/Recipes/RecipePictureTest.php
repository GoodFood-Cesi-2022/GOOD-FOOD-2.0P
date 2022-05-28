<?php

namespace Tests\Feature\Api\Recipes;

use App\Models\File;
use App\Models\Recipe;
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
     * Remplace l'ID de l'URI par l'ID passé
     *
     * @param integer|null $recipe_id
     * @return string
     */
    private static function getBasePath(?int $recipe_id) : string {
        return str_replace('{recipe_id}', $recipe_id, self::BASE_PATH);
    }


}
