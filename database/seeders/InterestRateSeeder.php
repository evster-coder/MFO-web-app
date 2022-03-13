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
            ['percentValue' => 0.4],
            ['percentValue' => 0.5],
            ['percentValue' => 0.65],
            ['percentValue' => 1],
            ['percentValue' => 0.3],
            ['percentValue' => 2],
        ];

        foreach ($items as $item) {
            InterestRate::create([
                'percentValue' => $item['percentValue'],
            ]);
        }

    }
}
