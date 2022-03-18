<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DictsData\InterestRate;

class InterestRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['percent_value' => 0.4],
            ['percent_value' => 0.5],
            ['percent_value' => 0.65],
            ['percent_value' => 1],
            ['percent_value' => 0.3],
            ['percent_value' => 2],
        ];

        foreach ($items as $item) {
            InterestRate::create([
                'percent_value' => $item['percent_value'],
            ]);
        }

    }
}
