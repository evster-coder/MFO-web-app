<?php

namespace App\Exports;

use App\Models\ClientForm;
use App\Models\OrgUnit;
use Illuminate\Database\Eloquent\Builder;
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
     * @return Builder
     */
    public function query(): Builder
    {
        return ClientForm::whereIn('org_unit_id', OrgUnit::whereDescendantOrSelf(session('orgUnit'))->pluck('id'))
            ->whereNotNull('director_approval_id')
            ->join('director_approvals', 'director_approvals.id', '=', 'client_forms.director_approval_id')
            ->whereBetween('director_approvals.approval_date', [$this->startDate, $this->endDate])
            ->orderBy('director_approvals.approval_date');
    }

    /**
     * @param ClientForm $clientForm
     * @return array
     */
    public function map($clientForm): array
    {
        if ($clientForm->directorApproval->approval) {
            $state = 'Одобрена';
        } else {
            $state = 'Отклонена';
        }
        $numDate = '№' . $clientForm->id . ' от ' . date('d.m.Y', strtotime($clientForm->loan_date));

        return [
            $numDate,
            date('d.m.Y', strtotime($clientForm->directorApproval->approval_date)),
            $clientForm->client->fullName,
            $clientForm->loan_cost . ' руб.',
            $clientForm->directorApproval->user->username . ' (' . $clientForm->directorApproval->user->full_name . ')',
            $state,
        ];
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'Номер заявки',
            'Дата одобрения',
            'Клиент',
            'Сумма займа',
            'Пользователь',
            'Статус',
        ];
    }
}
