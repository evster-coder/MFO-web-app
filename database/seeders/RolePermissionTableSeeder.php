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
        foreach (Role::all() as $role) {
            if ($role->slug == Role::ADMIN_ROLE) { // для роли супер-админа все права
                foreach (Permission::all() as $perm) {
                    $role->permissions()->attach($perm->id);
                }
            }

            if ($role->slug == Role::DIRECTOR_ROLE) {
                $perms = [
                    'change-curr-orgunit',
                    'view-users',
                    'manage-users',

                    'view-clientforms',
                    'edit-clientform',

                    'view-loans',

                    'view-clients',
                    'create-client',
                    'edit-client',
                    'delete-client',

                    'view-orgunits',
                    'edit-orgunit',

                    'view-director-approvals',
                    'manage-director-approval',
                    'delete-director-approval',
                ];

                foreach ($perms as $perm) {
                    $role->permissions()->attach(Permission::where('slug', $perm)->first()->id);
                }
            }

            if ($role->slug == Role::CASHIER_ROLE) { // для обычного кассира совсем чуть-чуть
                $perms = [
                    'change-curr-orgunit',
                    'view-users',

                    'view-clients',
                    'create-client',
                    'edit-client',
                    'delete-client',

                    'view-clientforms',
                    'create-clientform',
                    'edit-clientform',

                    'view-loans',
                    'create-loan',
                ];
                foreach ($perms as $perm) {
                    $role->permissions()->attach(Permission::where('slug', $perm)->first()->id);
                }
            }

            if ($role->slug == Role::SECURITY_ROLE) {
                $perms = [
                    'change-curr-orgunit',
                    'view-users',

                    'view-clients',

                    'view-clientforms',
                    'view-loans',

                    'manage-security-approval',
                    'view-security-approvals',
                    'delete-security-approval',
                ];

                foreach ($perms as $perm) {
                    $role->permissions()->attach(Permission::where('slug', $perm)->first()->id);
                }
            }
        }
    }
}
