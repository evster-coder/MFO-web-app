<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\OrgUnit;

class OrgUnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$orgunit = new OrgUnit();

		$orgunit->orgUnitCode = "OU-10";
		$orgunit->hasDictionaries = true;

    	$orgunit->save();

        OrgUnit::factory()->count(2)->create();

    }
}
