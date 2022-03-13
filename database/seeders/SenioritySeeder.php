<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DictsData\Seniority;

class SenioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'Менее 3 мес.'],
            ['name' => 'от 3 до 6 мес.'],
            ['name' => 'от 6 мес. до 1 года'],
            ['name' => 'от 1 до 3 лет'],
            ['name' => 'от 3 до 5 лет'],
            ['name' => 'более 5 лет'],
        ];

        foreach ($items as $item) {
            Seniority::create([
                'name' => $item['name'],
            ]);
        }
    }
}
