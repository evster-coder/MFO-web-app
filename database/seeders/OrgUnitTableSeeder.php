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
        $orgUnitRoot = OrgUnit::create([
            'org_unit_code' => "OU-10",
            'has_dictionaries' => true,
        ]);


        $orgUnit2 = new OrgUnit();

        $orgUnit2->org_unit_code = "OU-12";
        $orgUnit2->has_dictionaries = true;

        $orgUnit2->appendToNode($orgUnitRoot)->save();


        $orgUnit3 = new OrgUnit();

        $orgUnit3->org_unit_code = "OU-14";
        $orgUnit3->has_dictionaries = true;

        $orgUnit3->appendToNode($orgUnitRoot)->save();


        $orgUnit4 = new OrgUnit();

        $orgUnit4->org_unit_code = "S-121";
        $orgUnit4->has_dictionaries = true;

        $orgUnit4->appendToNode($orgUnit2)->save();


        $orgUnit5 = new OrgUnit();

        $orgUnit5->org_unit_code = "G-1";
        $orgUnit5->has_dictionaries = false;
        $orgUnit4->appendNode($orgUnit5);
    }
}
