<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrgUnitParam;

class OrgUnitParamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            ['slug' => 'org-name', 'name' => 'Наименование организации', 'data_type' => 'string'],
            ['slug' => 'org-fullname', 'name' => 'Наименование организации (полное)', 'data_type' => 'string'],
            ['slug' => 'org-regnum', 'name' => 'Регистрационный номер', 'data_type' => 'string'],
            ['slug' => 'orgunit-name', 'name' => 'Наименование подразделения', 'data_type' => 'string'],
            ['slug' => 'org-address', 'name' => 'Местонахождение организации', 'data_type' => 'string'],
            ['slug' => 'orgunit-address', 'name' => 'Местонахождение подразделения', 'data_type' => 'string'],
            ['slug' => 'ogrn', 'name' => 'ОГРН', 'data_type' => 'string'],
            ['slug' => 'inn', 'name' => 'ИНН', 'data_type' => 'string'],
            ['slug' => 'kpp', 'name' => 'КПП', 'data_type' => 'string'],
            ['slug' => 'accounting', 'name' => 'Счет', 'data_type' => 'string'],
            ['slug' => 'bank-name', 'name' => 'Наименование банка', 'data_type' => 'string'],
            ['slug' => 'bank-inn', 'name' => 'ИНН Банка', 'data_type' => 'string'],
            ['slug' => 'bank-kpp', 'name' => 'КПП Банка', 'data_type' => 'string'],
            ['slug' => 'bik', 'name' => 'БИК', 'data_type' => 'string'],
            ['slug' => 'corr-accounting', 'name' => 'Корр. счет', 'data_type' => 'string'],
            ['slug' => 'site', 'name' => 'Сайт', 'data_type' => 'string'],
            ['slug' => 'phone-number', 'name' => 'Телефон', 'data_type' => 'string'],
            ['slug' => 'slug-locality', 'name' => 'Сокращение населенного пункта', 'data_type' => 'string'],
        ];

        foreach ($params as $param) {
            OrgUnitParam::create([
                'name' => $param['name'],
                'slug' => $param['slug'],
                'data_type' => $param['data_type'],
            ]);
        }

    }
}
