<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Auth;

class UsersExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
	use Exportable;

	protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return User::find(Auth::user()->id)
                    ->getDownUsers()
                    ->select('users.*')
                    ->join('org_units', 'org_units.id', '=', 'users.org_unit_id')
                    ->where('username', 'like', '%'.$this->params.'%')
                    ->OrWhere('org_units.org_unit_code', 'like', '%'.$this->params.'%')
                    ->OrWhere('full_name', 'like', '%'.$this->params.'%')
                    ->with(['roles', 'orgUnit']);

    }

    public function map($user): array
    {
    	$roles = "";
    	foreach($user->roles as $role)
    		$roles = $roles . $role->name . " ";
    	$blocked = $user->blocked ? 'Да' : 'Нет';

        return [
            $user->username,
            $user->full_name,
            $user->orgUnit->org_unit_code,
            $blocked,
            $roles
        ];
    }

    public function headings(): array
    {
        return [
            'Логин',
            'ФИО',
            'Код подразделения',
            'Блокировка',
            'Роли'
        ];
    }
}
