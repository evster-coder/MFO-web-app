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
            ['slug' => Role::ADMIN_ROLE, 'name' => 'Администратор'],
            ['slug' => Role::DIRECTOR_ROLE, 'name' => 'Директор'],
            ['slug' => Role::CASHIER_ROLE, 'name' => 'Кассир'],
            ['slug' => Role::SECURITY_ROLE, 'name' => 'Сотрудник службы безопасности'],
        ];
        foreach ($roles as $role) {
            Role::create([
                'name' => $role['name'],
                'slug' => $role['slug'],
            ]);
        }
    }
}
