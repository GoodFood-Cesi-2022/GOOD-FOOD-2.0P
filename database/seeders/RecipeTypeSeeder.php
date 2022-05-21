<?php

namespace Database\Seeders;

use App\Models\RecipeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecipeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

        $types = [
            'appetizer',
            'main_course',
            'dessert'
        ];


        foreach($types as $code) {
            RecipeType::firstOrCreate(compact('code'));
        }


    }
}
