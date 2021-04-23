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
                $slugs = ['change-curr-orgunit'];
                    $role->permissions()->attach(Permission::where('slug', $slugs[0])->first()->id);
                }
        }
    }
}
