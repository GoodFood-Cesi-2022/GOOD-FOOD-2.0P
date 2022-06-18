<?php

namespace Database\Factories;

use App\Models\Email;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contractor>
 */
class ContractorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->catchPhrase,
            'phone' => $this->faker->phoneNumber(),
            'max_delivery_radius' => $this->faker->random_int(1, 30),
            'email_id' => Email::factory()->create()->id,
        ];
    }
}
