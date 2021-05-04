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

        $directorUser = new User();

        $directorUser->username = 'director_user';
        $directorUser->password = Hash::make('director');
        $directorUser->FIO = 'Director D.Y';
        $directorUser->orgunit_id = OrgUnit::where('hasDictionaries', false)->first()->id;
        $directorUser->blocked = false;
        $directorUser->needChangePassword = false;
        $directorUser->remember_token = Str::random(10);

        $directorUser->save();


        $cashier = new User();

        $cashier->username = 'cashier_user';
        $cashier->password = Hash::make('cashier');
        $cashier->FIO = 'Cashier D.Y';
        $cashier->orgunit_id = OrgUnit::where('hasDictionaries', false)->first()->id;
        $cashier->blocked = false;
        $cashier->needChangePassword = false;
        $cashier->remember_token = Str::random(10);

        $cashier->save();




    }
}
