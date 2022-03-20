<?php

namespace App\Exports;

use App\Models\Client;
use App\Models\OrgUnit;
use Illuminate\Database\Eloquent\Builder;
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

    public function __construct(
        $surname,
        $name,
        $patronymic,
        $birthDate
    ) {
        $this->surname = $surname;
        $this->name = $name;
        $this->patronymic = $patronymic;
        $this->birthDate = $birthDate;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $orgUnit = OrgUnit::find(session('orgUnit'))->getDictsOrgUnit();

        $clients = Client::where('org_unit_id', $orgUnit->id)
            ->where('surname', 'like', '%' . $this->surname . '%')
            ->where('name', 'like', '%' . $this->name . '%')
            ->where('birth_date', 'like', '%' . $this->birthDate . '%');

        if ($this->patronymic == "") {
            $clients = $clients->where(function ($query) {
                $query->where('patronymic', 'like', "%%")->orWhereNull('patronymic');
            });
        } else {
            $clients = $clients->where('patronymic', 'like', '%' . $this->patronymic . '%');
        }

        return $clients->orderBy('surname');
    }

    /**
     * @param Client $client
     * @return array
     */
    public function map($client): array
    {
        return [
            $client->surname,
            $client->name,
            $client->patronymic,
            $client->birth_date,
        ];
    }

    /**
     * @return string[]
     */
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
