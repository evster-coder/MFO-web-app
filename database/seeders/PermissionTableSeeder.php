<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['slug' => 'view-users', 'name' => 'Просмотр пользователей'],
            ['slug' => 'manage-users', 'name' => 'Управление пользователями'],
            ['slug' => 'create-user', 'name' => 'Создание пользователя'],
            ['slug' => 'edit-user', 'name' => 'Редактирование пользователя'],
            ['slug' => 'delete-user', 'name' => 'Удаление пользователя'],

            ['slug' => 'assign-role', 'name' => 'Назначение роли для пользователя'],

            ['slug' => 'manage-roles', 'name' => 'Управление ролями'],
            ['slug' => 'manage-perms', 'name' => 'Управление правами'],


            ['slug' => 'view-dictionaries', 'name' => 'Просмотр справочников'],
            ['slug' => 'manage-dictionaries', 'name' => 'Управление справочниками'],
            ['slug' => 'create-clients', 'name' => 'Создание клиента'],
            ['slug' => 'edit-clients', 'name' => 'Редактирование клиента'],
            ['slug' => 'delete-clients', 'name' => 'Удаление клиента'],

            ['slug' => 'manage-datadicts', 'name' => 'Управление справочной информацией'],

            ['slug' => 'change-curr-orgunit', 'name' => 'Изменение текущего подразделения'],
            ['slug' => 'view-orgunits', 'name' => 'Просмотр структуры подразделений'],
            ['slug' => 'create-orgunit', 'name' => 'Создание подразделения'],
            ['slug' => 'edit-orgunit', 'name' => 'Редактирование подразделения'],
            ['slug' => 'delete-orgunit', 'name' => 'Удаление подразделения'],
            ['slug' => 'view-orgunits-param', 'name' => 'Просмотр параметров подразделений'],


        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'slug' => $permission['slug'],
            ]);        
        }
    }
}
