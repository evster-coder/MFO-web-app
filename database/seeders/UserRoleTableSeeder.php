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
        foreach (User::all() as $user) {
            foreach (Role::all() as $role) {
                if ($user->id == 1 && $role->slug == Role::ADMIN_ROLE) { // один супер-админ
                    $user->roles()->attach($role->id);
                }
                if ($user->id == 2 && $role->slug == Role::DIRECTOR_ROLE) { // директор
                    $user->roles()->attach($role->id);
                }
                if ($user->id == 3 && $role->slug == Role::CASHIER_ROLE) { // кассиры
                    $user->roles()->attach($role->id);
                }
                if ($user->id == 4 && $role->slug == Role::SECURITY_ROLE) { // служба безопасности
                    $user->roles()->attach($role->id);
                }
            }
        }
    }
}
