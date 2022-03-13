<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DictsData\MaritalStatus;

class MaritalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'Женат / замужем'],
            ['name' => 'Гражданский брак'],
            ['name' => 'Холост / не замужем'],
            ['name' => 'Вдовец / вдова'],

        ];

        foreach ($items as $item) {
            MaritalStatus::create([
                'name' => $item['name'],
            ]);
        }
    }
}
