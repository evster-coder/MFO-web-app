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


            ['slug' => 'view-clients', 'name' => 'Просмотр справочников'],
            ['slug' => 'create-client', 'name' => 'Создание клиента'],
            ['slug' => 'edit-client', 'name' => 'Редактирование клиента'],
            ['slug' => 'delete-client', 'name' => 'Удаление клиента'],


            ['slug' => 'view-clientforms', 'name' => 'Просмотр заявок'],
            ['slug' => 'create-clientform', 'name' => 'Создание заявки'],
            ['slug' => 'edit-clientform', 'name' => 'Редактирование заявки'],
            ['slug' => 'delete-clientform', 'name' => 'Удаление заявки'],

            ['slug' => 'view-loans', 'name' => 'Просмотр займов'],
            ['slug' => 'create-loan', 'name' => 'Создание займа'],
            ['slug' => 'delete-loan', 'name' => 'Удаление займа'],

            ['slug' => 'manage-datadicts', 'name' => 'Управление справочной информацией'],

            ['slug' => 'change-curr-orgunit', 'name' => 'Изменение текущего подразделения'],
            ['slug' => 'view-orgunits', 'name' => 'Просмотр структуры подразделений'],
            ['slug' => 'create-orgunit', 'name' => 'Создание подразделения'],
            ['slug' => 'edit-orgunit', 'name' => 'Редактирование подразделения'],
            ['slug' => 'delete-orgunit', 'name' => 'Удаление подразделения'],

            ['slug' => 'view-orgunit-params', 'name' => 'Просмотр параметров подразделений'],
            ['slug' => 'create-orgunit-param', 'name' => 'Создание параметра подразделения'],
            ['slug' => 'edit-orgunit-param', 'name' => 'Редактирование параметра подразделений'],
            ['slug' => 'delete-orgunit-param', 'name' => 'Удаление параметра подразделений'],

            ['slug' => 'manage-security-approval', 'name' => 'Одобрение заявок службой безопасности'],
            ['slug' => 'view-security-approvals', 'name' => 'Просмотр одобрений службы безопасности'],
            ['slug' => 'delete-security-approval', 'name' => 'Удаление одобрения службы безопасности'],

            ['slug' => 'manage-director-approval', 'name' => 'Одобрение заявок директором'],
            ['slug' => 'view-director-approvals', 'name' => 'Просмотр одобрений директора'],
            ['slug' => 'delete-director-approval', 'name' => 'Удаление одобрения директора'],


        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'slug' => $permission['slug'],
            ]);        
        }
    }
}
