<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\OrgUnit;

class UserTableSeeder extends Seeder
{

    public function run()
    {
    	$user = new User();

    	$user->username = 'admin';
    	$user->password = Hash::make('admin');
    	$user->FIO = 'Admin E.K';
    	$user->orgunit_id = OrgUnit::whereNotNull('orgUnitCode')->first()->id;
    	$user->blocked = false;
    	$user->needChangePassword = false;
        $user->remember_token = Str::random(10);

    	$user->save();

        User::factory()->count(2)->create();
    }
}
