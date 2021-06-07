<?php

namespace App\Exports;

use App\Models\ClientForm;
use App\Models\OrgUnit;
use App\Models\Client;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;
class ClientFormsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
	use Exportable;

	protected $id;
	protected $loanDate;
	protected $clientFio;
	protected $state;


    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($id, $loanDate, $clientFio, $state)
    {
    	$this->id = $id;
    	$this->loanDate = $loanDate;
    	$this->clientFio = $clientFio;
    	$this->state = $state;
    }


    public function query()
    {
        $clientforms = ClientForm::whereIn('orgunit_id', OrgUnit::whereDescendantOrSelf(session('OrgUnit'))->pluck('id'))
                    ->where('client_forms.id', 'like', '%'.$this->id.'%')
                            ->where('loanDate', 'like', '%'.$this->loanDate.'%')
                            ->whereIn('client_id', 
                                        Client::where(DB::raw('CONCAT_WS(" ", `surname`, `name`, `patronymic`) '),
                                                     'like', 
                                            '%'.$this->clientFio.'%')->pluck('id'));
        //фильтрация по статусу
        if($this->state == 'considered')
        {
            //получить заявки в рассмотрении
            $clientforms = $clientforms
                    ->leftJoin('director_approvals', 'client_forms.director_approval_id', '=', 'director_approvals.id')
                    ->leftJoin('security_approvals', 'client_forms.security_approval_id', '=', 'security_approvals.id')
                    ->where(function($query){
                        $query->whereNull('client_forms.director_approval_id')
                        ->orWhere('director_approvals.approval', true);
                    })
                    ->where(function($query){
                        $query->whereNull('client_forms.security_approval_id')
                        ->orWhere('security_approvals.approval', true);
                    })
                    ->where(function($query){
                        $query->whereNull('client_forms.security_approval_id')
                        ->orWhereNull('client_forms.director_approval_id')
                        ->orWhere('security_approvals.approval', '!=', true)
                        ->orWhere('director_approvals.approval', '!=', true);
                    })->select('client_forms.*');
        }
        elseif($this->state == 'accepted')
        {
            $clientforms = $clientforms
                    ->leftJoin('director_approvals', 'client_forms.director_approval_id', '=', 'director_approvals.id')
                    ->leftJoin('security_approvals', 'client_forms.security_approval_id', '=', 'security_approvals.id')
                    ->where('director_approvals.approval', true)
                    ->where('security_approvals.approval', true)
                    ->select('client_forms.*');

        }
        elseif($this->state == 'rejected')
        {
            $clientforms = $clientforms
                    ->leftJoin('director_approvals', 'client_forms.director_approval_id', '=', 'director_approvals.id')
                    ->leftJoin('security_approvals', 'client_forms.security_approval_id', '=', 'security_approvals.id')
                    ->where('director_approvals.approval', false)
                    ->orWhere('security_approvals.approval', false)
                    ->select('client_forms.*');

        }
        elseif($this->state == 'loanSigned')
        {
            $clientforms = $clientforms->join('loans', 'client_forms.id', 'loans.clientform_id')
                    ->select('client_forms.*');
        }
        return $clientforms->orderBy('loanDate');
    }


    public function map($clientform): array
    {
        return [
            $clientform->id,
            $clientform->loanDate,
            $clientform->Client->fullName,
            $clientform->status
        ];
    }

    public function headings(): array
    {
        return [
            'Номер заявки',
            'Дата оформления',
            'Клиент',
            'Статус заявки',
        ];
    }

}
