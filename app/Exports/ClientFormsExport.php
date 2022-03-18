<?php

namespace App\Exports;

use App\Models\ClientForm;
use App\Models\OrgUnit;
use App\Models\Client;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class ClientFormsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;

    /**
     * @var
     */
    protected $id;
    /**
     * @var
     */
    protected $loanDate;
    /**
     * @var
     */
    protected $clientFio;
    /**
     * @var
     */
    protected $state;

    /**
     * @param $id
     * @param $loanDate
     * @param $clientFio
     * @param $state
     */
    public function __construct($id, $loanDate, $clientFio, $state)
    {
        $this->id = $id;
        $this->loanDate = $loanDate;
        $this->clientFio = $clientFio;
        $this->state = $state;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $clientForms = ClientForm::whereIn('org_unit_id',
            OrgUnit::whereDescendantOrSelf(session('orgUnit'))->pluck('id'))
            ->where('client_forms.id', 'like', '%' . $this->id . '%')
            ->where('loan_date', 'like', '%' . $this->loanDate . '%')
            ->whereIn('client_id',
                Client::where(DB::raw('CONCAT_WS(" ", `surname`, `name`, `patronymic`) '),
                    'like',
                    '%' . $this->clientFio . '%')->pluck('id'));
        //фильтрация по статусу
        if ($this->state == 'considered') {
            //получить заявки в рассмотрении
            $clientForms = $clientForms
                ->leftJoin('director_approvals', 'client_forms.director_approval_id', '=', 'director_approvals.id')
                ->leftJoin('security_approvals', 'client_forms.security_approval_id', '=', 'security_approvals.id')
                ->where(function ($query) {
                    $query->whereNull('client_forms.director_approval_id')
                        ->orWhere('director_approvals.approval', true);
                })
                ->where(function ($query) {
                    $query->whereNull('client_forms.security_approval_id')
                        ->orWhere('security_approvals.approval', true);
                })
                ->where(function ($query) {
                    $query->whereNull('client_forms.security_approval_id')
                        ->orWhereNull('client_forms.director_approval_id')
                        ->orWhere('security_approvals.approval', '!=', true)
                        ->orWhere('director_approvals.approval', '!=', true);
                })->select('client_forms.*');
        } elseif ($this->state == 'accepted') {
            $clientForms = $clientForms
                ->leftJoin('director_approvals', 'client_forms.director_approval_id', '=', 'director_approvals.id')
                ->leftJoin('security_approvals', 'client_forms.security_approval_id', '=', 'security_approvals.id')
                ->where('director_approvals.approval', true)
                ->where('security_approvals.approval', true)
                ->select('client_forms.*');

        } elseif ($this->state == 'rejected') {
            $clientForms = $clientForms
                ->leftJoin('director_approvals', 'client_forms.director_approval_id', '=', 'director_approvals.id')
                ->leftJoin('security_approvals', 'client_forms.security_approval_id', '=', 'security_approvals.id')
                ->where('director_approvals.approval', false)
                ->orWhere('security_approvals.approval', false)
                ->select('client_forms.*');

        } elseif ($this->state == 'loanSigned') {
            $clientForms = $clientForms->join('loans', 'client_forms.id', 'loans.client_form_id')
                ->select('client_forms.*');
        }

        return $clientForms->orderBy('loan_date');
    }


    /**
     * @param ClientForm $clientForm
     * @return array
     */
    public function map($clientForm): array
    {
        return [
            $clientForm->id,
            $clientForm->loan_date,
            $clientForm->client->fullName,
            $clientForm->status,
        ];
    }

    /**
     * @return string[]
     */
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
