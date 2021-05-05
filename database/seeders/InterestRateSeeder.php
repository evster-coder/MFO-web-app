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
    		['percentValue' => 146],
    		['percentValue' => 182.5],
    		['percentValue' => 237.25],
    		['percentValue' => 365],
    		['percentValue' => 109.5],
    		['percentValue' => 730],
    	];

        foreach ($items as $item) {
            InterestRate::create([
                'percentValue' => $item['percentValue'],
            ]);        
        }

    }
}
