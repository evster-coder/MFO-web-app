<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Permission;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // создать связи между ролями и правами
        foreach(Role::all() as $role) {
            if ($role->slug == 'admin' || $role->slug == 'director') { // для роли супер-админа все права
                foreach (Permission::all() as $perm) {
                    $role->permissions()->attach($perm->id);
                }
            }
            if ($role->slug == 'cashier') { // для обычного пользователя совсем чуть-чуть
                    $role->permissions()->attach(Permission::where('slug','change-curr-orgunit')->first()->id);
                    $role->permissions()->attach(Permission::where('slug', 'view-users')->first()->id);
                    $role->permissions()->attach(Permission::where('slug', 'view-clientforms')->first()->id);
                    $role->permissions()->attach(Permission::where('slug', 'add-clientform')->first()->id);
                }
        }
    }
}
