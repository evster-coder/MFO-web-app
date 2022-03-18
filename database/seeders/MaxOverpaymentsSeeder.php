<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaxOverpayment;
use Carbon\Carbon;

class MaxOverpaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'date_from' => null,
                'date_to' => Carbon::parse('2016-03-28'),
                'multiplicity' => '999',
            ],

            [
                'date_from' => Carbon::parse('2016-03-29'),
                'date_to' => Carbon::parse('2016-12-31'),
                'multiplicity' => '4',
            ],

            [
                'date_from' => Carbon::parse('2017-01-01'),
                'date_to' => Carbon::parse('2019-01-27'),
                'multiplicity' => '3',
            ],

            [
                'date_from' => Carbon::parse('2019-01-28'),
                'date_to' => Carbon::parse('2019-06-30'),
                'multiplicity' => '2.5',
            ],

            [
                'date_from' => Carbon::parse('2019-07-01'),
                'date_to' => Carbon::parse('2019-12-31'),
                'multiplicity' => '2',
            ],

            [
                'date_from' => Carbon::parse('2020-01-01'),
                'date_to' => null,
                'multiplicity' => '1.5',
            ],
        ];

        foreach ($items as $item) {
            MaxOverpayment::create([
                'date_from' => $item['date_from'],
                'date_to' => $item['date_to'],
                'multiplicity' => $item['multiplicity'],
            ]);
        }
    }
}
