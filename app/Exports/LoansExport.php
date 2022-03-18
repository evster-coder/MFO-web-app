<?php

namespace App\Exports;

use App\Models\Loan;
use App\Models\OrgUnit;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class LoansExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;

    /**
     * @var
     */
    protected $loanNumber;
    /**
     * @var
     */
    protected $loanConclusionDate;
    /**
     * @var
     */
    protected $clientFio;
    /**
     * @var
     */
    protected $statusOpen;

    /**
     * @param $loanNumber
     * @param $loanConclusionDate
     * @param $clientFio
     * @param $statusOpen
     */
    public function __construct(
        $loanNumber,
        $loanConclusionDate,
        $clientFio,
        $statusOpen
    ) {
        $this->loanNumber = $loanNumber;
        $this->loanConclusionDate = $loanConclusionDate;
        $this->clientFio = $clientFio;
        $this->statusOpen = $statusOpen;
    }

    /**
     * @return Collection
     */
    public function query()
    {
        //сортировка фильтрация пагинация
        $loans = Loan::whereIn('loans.org_unit_id',
            OrgUnit::whereDescendantOrSelf(session('orgUnit'))->pluck('org_units.id'))
            ->where('loan_number', 'like', '%' . $this->loanNumber . '%')
            ->where('loan_conclusion_date', 'like', '%' . $this->loanConclusionDate . '%')
            ->where('status_open', 'like', '%' . $this->statusOpen . '%');

        if ($this->clientFio != "") {
            $loans = $loans->join('client_forms', 'loans.client_form_id', 'client_forms.id')
                ->join('clients', 'client_forms.client_id', 'clients.id')
                ->where(DB::raw('CONCAT_WS(" ", `surname`, `name`, `patronymic`) '),
                    'like',
                    '%' . $this->clientFio . '%')
                ->select('loans.*');
        }

        return $loans->orderBy('loan_conclusion_date');
    }

    /**
     * @param mixed $loan
     * @return array
     */
    public function map($loan): array
    {
        if ($this->statusOpen == "1") {
            $statusOpen = "Открыт";
        } else {
            $statusOpen = "Закрыт";
        }

        return [
            $loan->loan_number,
            $loan->loan_conclusion_date,
            $loan->loan_closing_date,
            $loan->clientForm->client->fullName,
            $statusOpen,
        ];
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'Номер договора',
            'Дата заключения',
            'Дата закрытия',
            'Клиент',
            'Статус',
        ];
    }


}
