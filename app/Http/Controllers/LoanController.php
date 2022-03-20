<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\ClientForm;
use App\Models\Loan;
use App\Models\OrgUnit;
use App\Exports\LoansExport;
use DateTime;
use DB;
use DateTimeZone;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $loans = Loan::whereIn('org_unit_id', OrgUnit::whereDescendantOrSelf(session('orgUnit'))
            ->pluck('id'))
            ->paginate(config('app.admin.page_size', 20));

        return view('loans.index', compact('loans'));
    }

    /**
     * @param Request $req
     * @return BinaryFileResponse
     */
    public function export(Request $req): BinaryFileResponse
    {
        $filename = 'loans' . date('ymd') . '.xlsx';

        //Получить параметры поиска
        $loanNumber = str_replace(" ", "%", $req->get('loan_number'));
        $loanConclusionDate = str_replace(" ", "%", $req->get('loan_conclusion_date'));
        $clientFio = str_replace(" ", "%", $req->get('clientFio'));
        $statusOpen = str_replace(" ", "%", $req->get('status_open'));

        return (new LoansExport($loanNumber, $loanConclusionDate, $clientFio, $statusOpen))->download($filename);
    }

    /**
     * @param Request $req
     * @return string
     */
    public function getLoans(Request $req): string
    {
        //Получить параметры поиска
        $loanNumber = str_replace(" ", "%", $req->get('loan_number'));
        $loanConclusionDate = str_replace(" ", "%", $req->get('loan_conclusion_date'));
        $clientFio = str_replace(" ", "%", $req->get('clientFio'));
        $statusOpen = str_replace(" ", "%", $req->get('status_open'));

        $sortBy = $req->get('sortby');
        $sortDesc = $req->get('sortdesc');

        //сортировка фильтрация пагинация
        $loans = Loan::whereIn('loans.org_unit_id',
            OrgUnit::whereDescendantOrSelf(session('orgUnit'))->pluck('org_units.id'))
            ->where('loan_number', 'like', "%$loanNumber%")
            ->where('loan_conclusion_date', 'like', "%$loanConclusionDate%")
            ->where('status_open', 'like', "%$statusOpen%");

        if ($clientFio != "") {
            $loans = $loans->join('client_forms', 'loans.client_form_id', 'client_forms.id')
                ->join('clients', 'client_forms.client_id', 'clients.id')
                ->where(DB::raw('CONCAT_WS(" ", `surname`, `name`, `patronymic`) '),
                    'like',
                    '%' . $clientFio . '%')
                ->select('loans.*');
        }

        $loans = $loans->orderBy($sortBy, $sortDesc)
            ->paginate(config('app.admin.page_size', 20));

        return view('components.loans-tbody', compact('loans'))->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $clientForm = ClientForm::find($request->get('client_form_id'));

        if ($clientForm->securityApproval
            && $clientForm->securityApproval->approval) {
            $newLoan = new Loan();

            $now = new DateTime();
            $now->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));

            $newLoan->org_unit_id = session('orgUnit');
            $newLoan->client_form_id = $clientForm->id;

            //получаем имя населенного пункта для номера договора
            $localityName = OrgUnit::find(session('orgUnit'))
                ->params()
                ->where('orgUnitParam.slug', 'slug-locality')
                ->first();

            if ($localityName != null && $localityName->data_as_string != null) {
                $loanNumberName = $localityName->data_as_string;
            } else {
                $loanNumberName = 'DOGO';
            }

            $newLoan->loan_number = $loanNumberName . '/' . date_format($now, 'ymdHi');
            $newLoan->loan_conclusion_date = $now;
            $newLoan->status_open = true;

            if ($newLoan->save()) {
                return redirect()->route('loan.show', $newLoan->id);
            } else {
                return back()->withErrors(['msg' => "Ошибка создания займа"])
                    ->withInput();
            }
        } else {
            return back()->withErrors(['msg' => 'Договор не был одобрен!']);
        }

    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function closeLoan($id): RedirectResponse
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
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        return view('loans.show', ['loan' => Loan::find($id)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $loan = Loan::find($id);

        try {
            if ($loan && $loan->delete()) {
                return redirect()->route('loan.index')
                    ->with(['status' => trans('message.model.deleted.success')]);
            } else {
                return redirect()->route('clientForm.index')
                    ->withErrors([
                        'msg' => trans('message.model.deleted.fail',
                            ['error' => ' в LoanController::destroy']
                        ),
                    ]);
            }
        } catch (QueryException $e) {
            return back()->withErrors(['msg' => trans('message.model.deleted.fail')]);
        }
    }
}
