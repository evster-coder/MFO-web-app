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
            ['slug' => 'manage-users', 'name' => 'Управление пользователями'],
            ['slug' => 'create-user', 'name' => 'Создание пользователя'],
            ['slug' => 'edit-user', 'name' => 'Редактирование пользователя'],
            ['slug' => 'delete-user', 'name' => 'Удаление пользователя'],

            ['slug' => 'change-curr-orgunit', 'name' => 'Изменить текущее подразделение'],
            ['slug' => 'manage-orgunits', 'name' => 'Управление подразделениями'],
            ['slug' => 'create-orgunits', 'name' => 'Создание подразделения'],
            ['slug' => 'edit-orgunits', 'name' => 'Редактирование подразделения'],
            ['slug' => 'delete-orgunits', 'name' => 'Удаление подразделения'],

            ['slug' => 'assign-role', 'name' => 'Назначение роли для пользователя'],

            ['slug' => 'manage-clients', 'name' => 'Управление клиентами'],
            ['slug' => 'create-clients', 'name' => 'Создание клиента'],
            ['slug' => 'edit-clients', 'name' => 'Редактирование клиента'],
            ['slug' => 'delete-clients', 'name' => 'Удаление клиента'],

        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'slug' => $permission['slug'],
            ]);        
        }
    }
}
