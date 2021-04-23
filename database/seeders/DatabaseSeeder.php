<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(OrgUnitTableSeeder::class);
        $this->command->info('Таблица подразделений успешно заполнена!');

        $this->call(UserTableSeeder::class);
        $this->command->info('Таблица пользователей успешно заполнена!');

        $this->call(RoleTableSeeder::class);
        $this->command->info('Таблица ролей успешно заполнена!');

        $this->call(PermissionTableSeeder::class);
        $this->command->info('Таблица прав успешно заполнена!');
        
        $this->call(UserRoleTableSeeder::class);
        $this->command->info('Таблица ролей пользователей успешно заполнена!');

        $this->call(RolePermissionTableSeeder::class);
        $this->command->info('Таблица прав ролей успешно заполнена!');
    }
}
