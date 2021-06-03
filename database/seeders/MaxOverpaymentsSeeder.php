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
    		['dateFrom' => null,
                'dateTo' => Carbon::parse('2016-03-28'),
    			 'multiplicity' => '999'],

    		['dateFrom' => Carbon::parse('2016-03-29'),
    			 'dateTo' => Carbon::parse('2016-12-31'),
    			 'multiplicity' => '4'],

      		['dateFrom' => Carbon::parse('2017-01-01'),
    			 'dateTo' => Carbon::parse('2019-01-27'),
    			 'multiplicity' => '3'],

     		['dateFrom' => Carbon::parse('2019-01-28'),
    			 'dateTo' => Carbon::parse('2019-06-30'),
    			 'multiplicity' => '2.5'],

     		['dateFrom' => Carbon::parse('2019-07-01'),
    			 'dateTo' => Carbon::parse('2019-12-31'),
    			 'multiplicity' => '2'],

     		['dateFrom' => Carbon::parse('2020-01-01'),
                 'dateTo' => null,
    			 'multiplicity' => '1.5'],
    	];

        foreach ($items as $item) {
            MaxOverpayment::create([
                'dateFrom' => $item['dateFrom'],
                'dateTo' => $item['dateTo'],
                'multiplicity' => $item['multiplicity']
            ]);  
        } 

    }
}
