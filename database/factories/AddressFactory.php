<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_line' => $this->faker->streetAddress,
            'second_line' => $this->faker->state,
            'city' => $this->faker->city,
            'zip_code' => $this->faker->postcode,
            'country' => $this->faker->country
        ];
    }

    /**
     * Ajoute la longitute et la latitude
     *
     * @return @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function latlon() : \Illuminate\Database\Eloquent\Factories\Factory {

        return $this->state(function (array $attributes) : array {
            return [
                'lat' => $this->faker->latitude,
                'lon' => $this->faker->longitude
            ];
        });

    }


}
