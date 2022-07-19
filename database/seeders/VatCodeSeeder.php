<?php

namespace Database\Seeders;

use App\Models\VatCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VatCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

        $codes = [
            'be' => [
                'percentage' => 12 
            ],
            'lu' => [
                'percentage' => 3
            ],
            'fr' => [
                'percentage' => 5.5 
            ]
        ];

        foreach($codes as $code => $value) {

            VatCode::updateOrCreate([
                'code' => $code
            ], [
                'percentage' => $value['percentage']
            ]);

        }


    }
}
