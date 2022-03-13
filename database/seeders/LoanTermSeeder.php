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
            ['daysAmount' => 15],
            ['daysAmount' => 30],
        ];

        foreach ($items as $item) {
            LoanTerm::create([
                'daysAmount' => $item['daysAmount'],
            ]);
        }
    }
}
