<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DictsData\LoanTerm;

class LoanTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['days_amount' => 15],
            ['days_amount' => 30],
        ];

        foreach ($items as $item) {
            LoanTerm::create([
                'days_amount' => $item['days_amount'],
            ]);
        }
    }
}
