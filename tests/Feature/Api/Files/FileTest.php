<?php

namespace Tests\Feature\Api\Files;

use Mockery;
use Storage;
use App\Enums\Roles;
use Tests\Feature\Api\ApiCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileTest extends ApiCase
{
    
    const BASE_PATH = '/api/files';

    /**
     * Test l'upload d'un fichiers dans le système
     *
     * @group files
     * @return void
     */
    public function test_upload_file() : void {

        $this->actingAsContractor();

        $file_name = "image.png";
        $file_size = 7999;
        
        $file = UploadedFile::fake()->image($file_name)->size($file_size);

        $response = $this->post(self::BASE_PATH, [
            'filename' => $file,
            'name' => $file_name
        ]);

        $response->assertCreated()->assertJson([
            'name' => $file_name,
            'size' => $file_size * 1024
        ])->assertJsonStructure([
            'uuid',
            'created_at',
            'updated_at'
        ]);

        Storage::assertExists($file->hashName('files'));

    }


    /**
     * Test du rejet d'un fichier de + de 10 Mb
     *
     * @group files
     * @return void
     */
    public function test_upload_over_limit() : void {

        $this->actingAsContractor();

        $file = UploadedFile::fake()->image('image.png')->size(10001);

        $response = $this->post(self::BASE_PATH, [
            'filename' => $file,
            'name' => "image.png"
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrorFor('filename');


    }


    /**
     * Test l'upload d'un fichier avec une extension non acceptée
     *
     * @group files
     * @return void
     */
    public function test_upload_not_accepted_format() : void {

        $this->actingAsContractor();

        $file = UploadedFile::fake()->create('file.xlsx', 5000);

        $response = $this->post(self::BASE_PATH, [
            'filename' => $file,
            'name' => 'file.xlsx'
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrorFor('filename');


    }

    /**
     * Test l'upload d'un fichier avec echec d'enregistrement en base
     * Et la supression du fichier physiquement
     *
     * @group files
     * @return void
     */
    public function test_file_deleted_if_exception_is_throw() {

        $this->actingAsContractor();

        $mock = Mockery::mock(new \App\Models\File);
        $mock->shouldReceive('create')->andThrow(new \Exception('any error'));
        $this->app->instance(\App\Models\File::class, $mock);

        $file = UploadedFile::fake()->create('doc.pdf', 5000);

        $response = $this->post(self::BASE_PATH, [
            'filename' => $file,
            'name' => 'doc.pdf'
        ]);

        $response->assertStatus(500);

        Storage::assertMissing($file->hashName('files'));

    }

    /**
     * Tets le retour en cas d'echec d'upload
     *
     * @group files
     * @return void
     */
    public function test_error_on_upload() : void {

        $this->actingAsContractor();

        $mock = Mockery::mock(new \App\Http\Requests\Files\AddFileRequest());
        $mock->shouldReceive('isValid')->andReturn(false);
        $this->app->instance(\App\Http\Requests\Files\AddFileRequest::class, $mock);

        $file = UploadedFile::fake()->create('doc.pdf', 1000);

        $response = $this->post(self::BASE_PATH, [
            'filename' => $file,
            'name' => "doc.pdf"
        ]);

        $response->assertStatus(400);


    }


    /**
     * Test les accès en fonction des rôles
     *
     * @group files
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
            ['POST', self::BASE_PATH, 403, Roles::user->value],
        ];

    }

}
