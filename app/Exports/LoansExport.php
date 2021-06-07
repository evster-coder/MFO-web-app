<?php

namespace App\Exports;

use App\Models\Loan;
use App\Models\OrgUnit;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;
class LoansExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
	use Exportable;

	protected $loanNumber;
	protected $loanConclusionDate;
	protected $clientFio;
	protected $statusOpen;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($loanNumber, $loanConclusionDate, 
    										$clientFio, $statusOpen)
    {
    	$this->loanNumber = $loanNumber;
    	$this->loanConclusionDate = $loanConclusionDate;
    	$this->clientFio = $clientFio;
    	$this->statusOpen = $statusOpen;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        //сортировка фильтрация пагинация
        $loans = Loan::whereIn('loans.orgunit_id', OrgUnit::whereDescendantOrSelf(session('OrgUnit'))->pluck('orgunits.id'))
                            ->where('loanNumber', 'like', '%'.$this->loanNumber.'%')
                            ->where('loanConclusionDate', 'like', '%'.$this->loanConclusionDate.'%')
                            ->where('statusOpen', 'like', '%'.$this->statusOpen.'%');

        if($this->clientFio != "")
            $loans = $loans->join('client_forms', 'loans.clientform_id', 'client_forms.id')
                            ->join('clients', 'client_forms.client_id', 'clients.id')
                            ->where(DB::raw('CONCAT_WS(" ", `surname`, `name`, `patronymic`) '),
                                                         'like', 
                                                '%'.$this->clientFio.'%')
                            ->select('loans.*');

        return $loans->orderBy('loanConclusionDate');
    }

    public function map($loan): array
    {
    	if($this->statusOpen == "1")
    		$statusOpen = "Открыт";
    	else
    		$statusOpen = "Закрыт";

        return [
            $loan->loanNumber,
            $loan->loanConclusionDate,
            $loan->loanClosingDate,
            $loan->ClientForm->Client->fullName,
            $loan->statusOpen = $statusOpen,
        ];
    }

    public function headings(): array
    {
        return [
            'Номер договора',
            'Дата заключения',
            'Дата закрытия',
            'Клиент',
            'Статус'
        ];
    }


}
