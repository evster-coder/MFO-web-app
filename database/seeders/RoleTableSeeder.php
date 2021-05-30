<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['slug' => 'admin', 'name' => 'Администратор'],
            ['slug' => 'director', 'name' => 'Директор'],
            ['slug' => 'cashier', 'name' => 'Кассир'],
            ['slug' => 'security', 'name' => 'Сотрудник службы безопасности']
        ];
        foreach ($roles as $role) {
            Role::create([
                'name' => $role['name'],
                'slug' => $role['slug']
            ]);
        }
    }
}
