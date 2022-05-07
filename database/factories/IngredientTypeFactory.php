<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->word();
        return [
            'code' => Str::slug($name),
            'name' => $name,
            'description' => $this->faker->sentence()
        ];
    }

}
