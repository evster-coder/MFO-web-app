<?php

namespace App\Exports;

use App\Models\ClientForm;
use App\Models\OrgUnit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DirectorApprovalsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
	use Exportable;

	protected $startDate;
	protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
		return ClientForm::whereIn('orgunit_id', OrgUnit::whereDescendantOrSelf(session('OrgUnit'))->pluck('id'))
		    ->whereNotNull('director_approval_id')
		    ->join('director_approvals', 'director_approvals.id', '=', 'client_forms.director_approval_id')
		    ->whereBetween('director_approvals.approvalDate', [$this->startDate, $this->endDate])
		    ->orderBy('director_approvals.approvalDate');
    }

    public function map($clientform): array
    {    	
    	if($clientform->DirectorApproval->approval)
    		$state = 'Одобрена';
    	else
    		$state = 'Отклонена';
    	$numDate = '№' . $clientform->id . ' от ' . date('d.m.Y', strtotime($clientform->loanDate));
        return [
            $numDate,
            date('d.m.Y', strtotime($clientform->DirectorApproval->approvalDate)),
            $clientform->Client->fullName,
            $clientform->loanCost . ' руб.',
            $clientform->DirectorApproval->User->username . ' (' . $clientform->DirectorApproval->User->FIO  . ')',
            $state
        ];
    }

    public function headings(): array
    {
        return [
            'Номер заявки',
            'Дата одобрения',
            'Клиент',
            'Сумма займа',
            'Пользователь',
            'Статус'
        ];
    }
}
