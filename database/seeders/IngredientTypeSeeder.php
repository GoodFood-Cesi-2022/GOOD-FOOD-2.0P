<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\IngredientType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            IngredientType::firstOrCreate(['code' => Str::slug($type)],[
                'name' => $type,
                'description' => $description
            ]);
        }
    }
}
