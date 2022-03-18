<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Models\ClientForm;
use App\Models\Loan;
use App\Models\OrgUnit;

use App\Exports\LoansExport;
use Maatwebsite\Excel\Facades\Excel;

use DateTime;
use DB;
use DateTimeZone;
class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = Loan::whereIn('org_unit_id', OrgUnit::whereDescendantOrSelf(session('orgUnit'))->pluck('id'))->paginate(20);

        return view('loans.index', [
            'loans' => $loans,
        ]);
    }

    //экспорт таблицы в эксель
    public function export(Request $req)
    {
        $now = new DateTime('NOW');
        $filename = 'loans' . date_format($now, 'ymd') . '.xlsx';

        //Получить параметры поиска
        $loanNumber = str_replace(" ", "%", $req->get('loan_number'));
        $loanConclusionDate = str_replace(" ", "%", $req->get('loan_conclusion_date'));
        $clientFio = str_replace(" ", "%", $req->get('clientFio'));
        $statusOpen = str_replace(" ", "%", $req->get('status_open'));

        return (new LoansExport($loanNumber, $loanConclusionDate, $clientFio, $statusOpen))->download($filename);
    }


    //получение списка займов  в виде компонента
    public function getLoans(Request $req)
    {
        //Получить параметры поиска
        $loanNumber = str_replace(" ", "%", $req->get('loan_number'));
        $loanConclusionDate = str_replace(" ", "%", $req->get('loan_conclusion_date'));
        $clientFio = str_replace(" ", "%", $req->get('clientFio'));
        $statusOpen = str_replace(" ", "%", $req->get('status_open'));

        $sortBy = $req->get('sortby');
        $sortDesc = $req->get('sortdesc');

        //сортировка фильтрация пагинация
        $loans = Loan::whereIn('loans.org_unit_id', OrgUnit::whereDescendantOrSelf(session('orgUnit'))->pluck('org_units.id'))
                            ->where('loan_number', 'like', '%'.$loanNumber.'%')
                            ->where('loan_conclusion_date', 'like', '%'.$loanConclusionDate.'%')
                            ->where('status_open', 'like', '%'.$statusOpen.'%');

        if($clientFio != "")
            $loans = $loans->join('client_forms', 'loans.client_form_id', 'client_forms.id')
                            ->join('clients', 'client_forms.client_id', 'clients.id')
                            ->where(DB::raw('CONCAT_WS(" ", `surname`, `name`, `patronymic`) '),
                                                         'like',
                                                '%'.$clientFio.'%')
                            ->select('loans.*');

        $loans = $loans->orderBy($sortBy, $sortDesc)
                        ->paginate(20);

        return view('components.loans-tbody', compact('loans'))->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $clientForm = ClientForm::find($request->get('client_form_id'));

        if($clientForm->securityApproval
            && $clientForm->securityApproval ->approval)
        {
            $newLoan = new Loan();

            $now = new DateTime('NOW');
            $now->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));

            $newLoan->org_unit_id = session('orgUnit');
            $newLoan->client_form_id = $clientForm->id;

            //получаем имя населенного пункта для номера договора
            $localityName = OrgUnit::find(session('orgUnit'))
                            ->params()
                            ->where('orgUnitParam.slug', 'slug-locality')
                            ->first();

            if($localityName != null && $localityName->data_as_string != null)
                $loanNumberName = $localityName->data_as_string;
            else
                $loanNumberName = 'DOGO';

            $newLoan->loan_number = $loanNumberName . '/' . date_format($now, 'ymdHi');

            $newLoan->loan_conclusion_date = $now;
            $newLoan->status_open = true;

            if ($newLoan->save())
                return redirect()->route('loan.show', $newLoan->id);
            else
                return back()->withErrors(['msg' => "Ошибка создания займа"])->withInput();
        }
        else
            return back()->withErrors(['msg' => 'Договор не был одобрен!']);

    }

    //закрыть займ
    public function closeLoan($id)
    {
        $now = new DateTime('NOW');
        $now->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));


        $loan = Loan::find($id);
        $loan->status_open = false;
        $loan->loan_closing_date = $now;

        $loan->save();

        return redirect()->route('loan.show', ['id' => $id])
                        ->with(['status' => 'Договор успешно закрыт']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('loans.show', ['loan' => Loan::find($id)]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan = Loan::find($id);

        if($loan)
        {
            try{
                $loan->delete();
                return redirect()->route('loan.index')
                        ->with(['status' => 'Договор успешно удален']);
            }
            catch (QueryException $e){
                return back()->withErrors(['msg' => 'Невозможно удалить Договор займа']);
            }
        }
        else
        {
            return redirect()->route('clientForm.index')
                ->withErrors(['msg' => 'Ошибка удаления в ClientFormController::destroy']);
        }
    }
}
