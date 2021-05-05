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
		$orgunit1 = OrgUnit::create([
            'orgUnitCode' => "OU-10",
            'hasDictionaries' => true,
        ]);


        $orgunit2 = new OrgUnit();

        $orgunit2->orgUnitCode = "OU-12";
        $orgunit2->hasDictionaries = false;

        $orgunit2->appendToNode($orgunit1)->save();


        $orgunit3 = new OrgUnit();

        $orgunit3->orgUnitCode = "OU-14";
        $orgunit3->hasDictionaries = true;

        $orgunit3->appendToNode($orgunit1)->save();


        $orgunit4 = new OrgUnit();

        $orgunit4->orgUnitCode = "S-121";
        $orgunit4->hasDictionaries = true;

        $orgunit4->appendToNode($orgunit2)->save();


        $orgunit5 = new OrgUnit();

        $orgunit5->orgUnitCode = "G-1";
        $orgunit5->hasDictionaries = false;
        $orgunit4->appendNode($orgunit5);

    }
}
