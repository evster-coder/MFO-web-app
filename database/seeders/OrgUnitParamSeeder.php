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
            ['slug' => 'org-name', 'name' => 'Наименование организации', 'dataType' => 'string'],
            ['slug' => 'org-fullname', 'name' => 'Наименование организации (полное)', 'dataType' => 'string'],
			['slug' => 'org-regnum', 'name' => 'Регистрационный номер', 'dataType' => 'string'],

			['slug' => 'orgunit-name', 'name' => 'Наименование подразделения', 'dataType' => 'string'],
			['slug' => 'org-address', 'name' => 'Местонахождение организации', 'dataType' => 'string'],
			['slug' => 'orgunit-address', 'name' => 'Местонахождение подразделения', 'dataType' => 'string'],

			['slug' => 'ogrn', 'name' => 'ОГРН', 'dataType' => 'string'],
			['slug' => 'inn', 'name' => 'ИНН', 'dataType' => 'string'],
			['slug' => 'kpp', 'name' => 'КПП', 'dataType' => 'string'],
			['slug' => 'accounting', 'name' => 'Счет', 'dataType' => 'string'],
			['slug' => 'bank-name', 'name' => 'Наименование банка', 'dataType' => 'string'],
			['slug' => 'bank-inn', 'name' => 'ИНН Банка', 'dataType' => 'string'],
			['slug' => 'bank-kpp', 'name' => 'КПП Банка', 'dataType' => 'string'],
			['slug' => 'bik', 'name' => 'БИК', 'dataType' => 'string'],
			['slug' => 'corr-accounting', 'name' => 'Корр. счет', 'dataType' => 'string'],
			['slug' => 'site', 'name' => 'Сайт', 'dataType' => 'string'],
			['slug' => 'phone-number', 'name' => 'Телефон', 'dataType' => 'string'],


        ];

        foreach ($params as $param) {
            OrgUnitParam::create([
                'name' => $param['name'],
                'slug' => $param['slug'],
                'dataType' => $param['dataType']
            ]);        
        }

    }
}
