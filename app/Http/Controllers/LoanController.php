<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ClientForm;
use App\Models\Loan;
use App\Models\OrgUnit;

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
        $loans = Loan::whereIn('orgunit_id', OrgUnit::whereDescendantOrSelf(session('OrgUnit'))->pluck('id'))->paginate(20);

        return view('loans.index', [
            'loans' => $loans,
        ]);
    }

    //экспорт таблицы в эксель
    public function export(Request $req)
    {
        
    }


    //получение списка займов
    public function getLoans(Request $req)
    {
        //Получить параметры поиска   
        $loanNumber = str_replace(" ", "%", $req->get('loanNumber'));
        $loanConclusionDate = str_replace(" ", "%", $req->get('loanConclusionDate'));
        $clientFio = str_replace(" ", "%", $req->get('clientFio'));
        $statusOpen = str_replace(" ", "%", $req->get('statusOpen'));

        $sortBy = $req->get('sortby');
        $sortDesc = $req->get('sortdesc');

        //сортировка фильтрация пагинация
        $loans = Loan::where('loanNumber', 'like', '%'.$loanNumber.'%')
                            ->where('loanConclusionDate', 'like', '%'.$loanConclusionDate.'%')
                            ->where('statusOpen', 'like', '%'.$statusOpen.'%');

        if($clientFio != "")
            $loans = $loans->join('client_forms', 'loans.clientform_id', 'client_forms.id')
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
        $clientform = ClientForm::find($request->get('clientform_id'));

        if($clientform->SecurityApproval 
            && $clientform->SecurityApproval ->approval)
        {
            $newLoan = new Loan();

            $now = new DateTime('NOW');
            $now->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));

            $newLoan->orgunit_id = session('OrgUnit');
            $newLoan->clientform_id = $clientform->id;

            //получаем имя населенного пункта для номера договора
            $localityName = OrgUnit::find(session('OrgUnit'))
                            ->params()
                            ->where('OrgUnitParam.slug', 'slug-locality')
                            ->first();

            if($localityName != null && $localityName->dataAsString != null)
                $loanNumberName = $localityName->dataAsString;
            else
                $loanNumberName = 'DOGO';

            $newLoan->loanNumber = $loanNumberName . '/' . date_format($now, 'ymdHi');

            $newLoan->loanConclusionDate = $now;
            $newLoan->statusOpen = true;

            $newLoan->save();
            if ($newLoan)
                return redirect()->route('loan.show', $newLoan->id);
            else
                return back()->withErrors(['msg' => "Ошибка создания займа"])->withInput();
        }
        else
            return back()->withErrors(['msg' => 'Договор не был одобрен!']);

    }

    public function closeLoan($id)
    {
        $now = new DateTime('NOW');
        $now->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));


        $loan = Loan::find($id);
        $loan->statusOpen = false;
        $loan->loanClosingDate = $now;

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
        $loan = Loan::find($id);
        return view('loans.show', ['loan' => $loan]);
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
            catch (\Illuminate\Database\QueryException $e){
                return back()->withErrors(['msg' => 'Невозможно удалить Договор займа']);
            }
        }
        else
        {
            return redirect()->route('clientform.index')
                ->withErrors(['msg' => 'Ошибка удаления в ClientFormController::destroy']);
        }
    }
}
