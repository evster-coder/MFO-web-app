<?php

namespace App\Exports;

use App\Models\ClientForm;
use App\Models\OrgUnit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SecurityApprovalsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
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
		return ClientForm::whereIn('org_unit_id', OrgUnit::whereDescendantOrSelf(session('orgUnit'))->pluck('id'))
		    ->whereNotNull('security_approval_id')
		    ->join('security_approvals', 'security_approvals.id', '=', 'client_forms.security_approval_id')
		    ->whereBetween('security_approvals.approval_date', [$this->startDate, $this->endDate])
		    ->orderBy('security_approvals.approval_date');
    }

    public function map($clientForm): array
    {
    	if($clientForm->securityApproval->approval)
    		$state = 'Одобрена';
    	else
    		$state = 'Отклонена';
    	$numDate = '№' . $clientForm->id . ' от ' . date('d.m.Y', strtotime($clientForm->loan_date));
        return [
            $numDate,
            date('d.m.Y', strtotime($clientForm->securityApproval->approval_date)),
            $clientForm->client->fullName,
            $clientForm->loan_cost . ' руб.',
            $clientForm->securityApproval->user->username . ' (' . $clientForm->securityApproval->user->full_name  . ')',
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
