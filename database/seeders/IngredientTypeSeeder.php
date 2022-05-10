<?php

namespace Database\Seeders;

use App\Models\IngredientType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'poissons' => "",
            'viandes' => "",
            'légumes' => "",
            'fruits' => "",
            'fromages' => "",
            'laitages' => ""
        ];

        foreach($types as $type => $description) {
            IngredientType::firstOrCreate([
                'name' => $type,
                'code' => $type,
                'description' => $description
            ]);
        }
    }
}
