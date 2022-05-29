<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Storage;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word() . '.' . $this->faker->fileExtension(),
            'path' => "/void",
            'size' => 0
        ];
    }


    /**
     * Génére un fichier physique aléatoire
     *
     * @param integer $size
     * @param string $disk
     * @return void
     */
    public function random(int $size = 10000, string $disk = 'files') {

        return $this->state(function(array $attributes) use($size, $disk) {
        
            Storage::persistentFake($disk);
            
            $file = UploadedFile::fake()->create($attributes['name'], $size);

            return [
                'path' => $file->path(),
                'size' => $size
            ];

        });

    }


    /**
     * Génére un fichier physique de type image
     *
     * @param integer $size
     * @param string $disk
     * @return void
     */
    public function image(int $width = 100, int $height = 100, string $disk = 'files') {

        return $this->state(function(array $attributes) use($width, $height, $disk) {
        
            Storage::persistentFake($disk);
            
            $file = UploadedFile::fake()->image($this->faker->word . '.jpg', $width, $height)->size(100);

            return [
                'path' => $file->path(),
                'size' => $file->getSize()
            ];

        });

    }

    /**
     * Utilise une image réel créer le model
     *
     * @param string $disk
     * @return void
     */
    public function realImage(string $disk = '') {

        return $this->state(function(array $attributes) use($disk) {
        
            Storage::persistentFake($disk);

            $name = Str::random();

            $path = storage_path('app/files/') . "$name.jpg";

            copy(base_path('tests/Resources/tmp.jpg'), $path);

            return [
                'path' => "files/$name.jpg",
                'size' => 1000
            ];

        });

    }


}
