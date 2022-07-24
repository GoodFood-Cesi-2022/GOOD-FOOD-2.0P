<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Email;
use App\Models\Address;
use App\Models\ContractorTime;
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
            'max_delivery_radius' => $this->faker->numberBetween(1, 30),
            'email_id' => Email::factory()->create()->id,
            'created_by' => User::factory(),
            'timezone' => 'FR',
            'address_id' => Address::factory()->latlon(),
            'owned_by' => User::factory()
        ];
    }

}
