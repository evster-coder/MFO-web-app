<?php

namespace App\Exports;

use App\Models\Client;
use App\Models\OrgUnit;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClientsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
	use Exportable;

	protected $surname;
	protected $name;
	protected $patronymic;
	protected $birthDate;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($surname, $name,
										$patronymic, $birthDate)
    {
    	$this->surname = $surname;
    	$this->name = $name;
    	$this->patronymic = $patronymic;
    	$this->birthDate = $birthDate;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        $orgUnit = OrgUnit::find(session('orgUnit'))->getDictsOrgUnit();

        $clients = Client::where('org_unit_id', $orgUnit->id)
                    ->where('surname', 'like', '%'.$this->surname.'%')
                    ->where('name', 'like', '%'.$this->name.'%')
                    ->where('birth_date', 'like', '%'.$this->birthDate.'%');

        if($this->patronymic == "")
            $clients = $clients->where(function($query){
                $query->where('patronymic', 'like', "%%")->orWhereNull('patronymic');
            });
        else
            $clients = $clients->where('patronymic', 'like', '%'.$this->patronymic.'%');

        return $clients->orderBy('surname');
    }

    public function map($client): array
    {
        return [
            $client->surname,
            $client->name,
            $client->patronymic,
            $client->birth_date,
        ];
    }

    public function headings(): array
    {
        return [
            'Фамилия',
            'Имя',
            'Отчество',
            'Дата рождения',
        ];
    }


}
