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

        $this->call(OrgUnitParamSeeder::class);
        $this->command->info('Таблица параметров подразделений успешно заполнена!');

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

        $this->call(InterestRateSeeder::class);
        $this->command->info('Таблица процентная ставка успешно заполнена!');


        $this->call(LoanTermSeeder::class);
        $this->command->info('Таблица сроки займа успешно заполнена!');


        $this->call(SenioritySeeder::class);
        $this->command->info('Таблица стаж работы успешно заполнена!');


        $this->call(MaritalStatusSeeder::class);
        $this->command->info('Таблица семейные положения успешно заполнена!');

        $this->call(MaxOverpaymentsSeeder::class);
        $this->command->info('Таблица Максимальных переплат заполнена!');

    }
}
