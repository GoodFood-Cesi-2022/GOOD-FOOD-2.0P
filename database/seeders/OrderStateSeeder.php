<?php

namespace Database\Seeders;

use App\Models\OrderState;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codes = [
            'creating',
            'cooking',
            'shipping',
            'closed',
            'canceled',
        ];


        foreach($codes as $code) {
            OrderState::updateOrCreate([
                'code' => $code
            ]);
        }
    }
}
