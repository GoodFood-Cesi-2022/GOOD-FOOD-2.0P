<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->text(100),
            'base_price' => $this->faker->randomFloat(2,5,30),
            'star' => false,
            'available_at' => Carbon::now(),
            'created_by' => User::factory()
        ];
    }


    /**
     * Recette star
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function star() : Factory {

        return $this->state(function(array $attributes) : array {
            return [
                'star' => true
            ];
        });

    }


    /**
     * Recette non disponible
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function notAvailable() : Factory {

        return $this->state(function (array $attributes) : array {
            return [
                'available' => Carbon::now()->addYear()
            ];
        });

    }

}
