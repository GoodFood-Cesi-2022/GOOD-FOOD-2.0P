<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContractorTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'monday_lunch_opened_at' => null,
            'monday_lunch_closed_at' => null,
            'monday_night_opened_at' => null,
            'monday_night_closed_at' => null,
            'tuesday_lunch_opened_at' => '12:00',
            'tuesday_lunch_closed_at' => '14:00',
            'tuesday_night_opened_at' => '19:00',
            'tuesday_night_closed_at' => '23:00',
            'wednesday_lunch_opened_at' => '12:00',
            'wednesday_lunch_closed_at' => '14:00',
            'wednesday_night_opened_at' => '19:00',
            'wednesday_night_closed_at' => '23:00',
            'thursday_lunch_opened_at' => '12:00',
            'thursday_lunch_closed_at' => '14:00',
            'thursday_night_opened_at' => '19:00',
            'thursday_night_closed_at' => '23:00',
            'friday_lunch_opened_at' => '12:00',
            'friday_lunch_closed_at' => '14:00',
            'friday_night_opened_at' => '19:00',
            'friday_night_closed_at' => '23:00',
            'saturday_lunch_opened_at' => '12:00',
            'saturday_lunch_closed_at' => '14:00',
            'saturday_night_opened_at' => '19:00',
            'saturday_night_closed_at' => '23:00',
            'sunday_lunch_opened_at' => '12:00',
            'sunday_lunch_closed_at' => '14:00',
            'sunday_night_opened_at' => '19:00',
            'sunday_night_closed_at' => '23:00',
        ];
    }
}
