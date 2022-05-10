<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word(),
            'allergen' => false,
            'created_by' => User::factory(),
        ];
    }


    public function allergen() {
        return $this->state(function(array $attributes) {
            return [
                'allergen' => true
            ];
        });
    }


    public function deleted() {
        return $this->state(function(array $attributes) {
            return [
                'deleted_at' => Carbon::now()
            ];
        });
    }

}
