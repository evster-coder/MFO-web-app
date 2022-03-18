<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\OrgUnit;

class UserTableSeeder extends Seeder
{
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const NAME = 'name';

    const DEFAULT_ADMIN = [
        self::USERNAME => 'admin',
        self::PASSWORD => 'admin',
        self::NAME => 'Admin E.K',
    ];

    const DEFAULT_DIRECTOR = [
        self::USERNAME => 'director_user',
        self::PASSWORD => 'director',
        self::NAME => 'Director D.Y',
    ];

    const DEFAULT_CASHIER = [
        self::USERNAME => 'cashier_user',
        self::PASSWORD => 'cashier',
        self::NAME => 'Cashier D.Y',
    ];

    const DEFAULT_SECURITY = [
        self::USERNAME => 'security_user',
        self::PASSWORD => 'security',
        self::NAME => 'Security D.Y',
    ];

    public function run()
    {
        /** @var OrgUnit $orgUnit */
        $orgUnit = OrgUnit::whereIsRoot()->first();


        $user = new User();

        $user->username = self::DEFAULT_ADMIN[self::USERNAME];
        $user->password = Hash::make(self::DEFAULT_ADMIN[self::PASSWORD]);
        $user->full_name = self::DEFAULT_ADMIN[self::NAME];
        $user->org_unit_id = $orgUnit->id;
        $user->blocked = false;
        $user->need_change_password = false;
        $user->remember_token = Str::random(10);

        $user->save();

        $directorUser = new User();

        $directorUser->username = self::DEFAULT_DIRECTOR[self::USERNAME];
        $directorUser->password = Hash::make(self::DEFAULT_DIRECTOR[self::PASSWORD]);
        $directorUser->full_name = self::DEFAULT_DIRECTOR[self::NAME];
        $directorUser->org_unit_id = $orgUnit->children()->first()->id;
        $directorUser->blocked = false;
        $directorUser->need_change_password = false;
        $directorUser->remember_token = Str::random(10);

        $directorUser->save();


        $cashier = new User();

        $cashier->username = self::DEFAULT_CASHIER[self::USERNAME];
        $cashier->password = Hash::make(self::DEFAULT_CASHIER[self::PASSWORD]);
        $cashier->full_name = self::DEFAULT_CASHIER[self::NAME];
        $cashier->org_unit_id = $orgUnit->children()->first()->children()->first()->id;
        $cashier->blocked = false;
        $cashier->need_change_password = false;
        $cashier->remember_token = Str::random(10);

        $cashier->save();


        $security = new User();

        $security->username = self::DEFAULT_SECURITY[self::USERNAME];
        $security->password = Hash::make(self::DEFAULT_SECURITY[self::PASSWORD]);
        $security->full_name = self::DEFAULT_SECURITY[self::NAME];
        $security->org_unit_id = $orgUnit->children()->first()->children()->first()->id;
        $security->blocked = false;
        $security->need_change_password = false;
        $security->remember_token = Str::random(10);

        $security->save();

    }
}
