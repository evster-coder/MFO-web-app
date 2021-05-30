<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Role;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(User::all() as $user) {
            foreach(Role::all() as $role) {
                if ($user->id == 1 && $role->slug == 'admin') { // один супер-админ
                    $user->roles()->attach($role->id);
                }
                if ($user->id == 2 && $role->slug == 'director') { // директор
                    $user->roles()->attach($role->id);
                }
                if ($user->id == 3 && $role->slug == 'cashier') { // кассиры
                    $user->roles()->attach($role->id);
                }
                if ($user->id == 4 && $role->slug == 'security') { // служба безопасности
                    $user->roles()->attach($role->id);
                }
            }
        }
    }
}
