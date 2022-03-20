<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use App\Models\ClientForm;
use App\Models\Loan;
use App\Models\Client;
use App\Models\OrgUnit;
use App\Exports\ClientsExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $orgUnit = OrgUnit::find(session('orgUnit'))->getDictsOrgUnit();

        $clients = Client::where('org_unit_id', $orgUnit->id)
            ->orderBy('surname')
            ->paginate(config('app.admin.page_size', 20));

        return view('clients.index', compact('clients'));
    }

    /**
     * Export data to Excel
     *
     * @param Request $req
     * @return BinaryFileResponse
     */
    public function export(Request $req): BinaryFileResponse
    {
        $filename = 'clients-' . date('ymd') . '.xlsx';

        $surname = str_replace(" ", "%", $req->get('surname', ''));
        $name = str_replace(" ", "%", $req->get('name', ''));
        $patronymic = str_replace(" ", "%", $req->get('patronymic', ''));
        $birthDate = str_replace(" ", "%", $req->get('birth_date', ''));

        return (new ClientsExport($surname, $name, $patronymic, $birthDate))->download($filename);
    }

    /**
     * @param int $id
     * @param Request $req
     * @return string
     */
    public function getClientForms(int $id, Request $req): string
    {
        if ($req->ajax()) {
            $startDate = $req->get('dateFrom', Carbon::createFromTimestamp(0)->format('y-m-d'));
            $endDate = $req->get('dateTo', Carbon::now()->addYear()->format('y-m-d'));

            $clientForms = ClientForm::where('client_id', $id)
                ->whereBetween('loan_date', [$startDate, $endDate])
                ->get();

            return view('components.client-clientforms-tbody', compact('clientForms'))->render();
        }

        return '';
    }

    /**
     * @param int $id
     * @param Request $req
     * @return string
     */
    public function getLoans(int $id, Request $req): string
    {
        if ($req->ajax()) {
            $loanNumber = str_replace(" ", "%", $req->get('loan_number'));
            $loanConclusionDate = str_replace(" ", "%", $req->get('loan_conclusion_date'));

            $loans = Loan::join('client_forms', 'loans.client_form_id', '=', 'client_forms.id')
                ->where('client_forms.client_id', $id)
                ->where('loan_number', 'like', "%$loanNumber%")
                ->where('loan_conclusion_date', 'like', "%$loanConclusionDate%")
                ->select('loans.*')
                ->get();

            return view('components.client-loans-tbody', compact('loans'))->render();
        }

        return '';
    }

    /**
     * Получение клиентов с такими же фио и датой рождения
     *
     * @param Request $req
     * @return string
     */
    public function sameClients(Request $req): string
    {
        if ($req->ajax()) {
            $surname = $req->get('surname');
            $name = $req->get('name');
            $patronymic = $req->get('patronymic');
            $birthDate = $req->get('birth_date');

            $orgUnit = OrgUnit::find(session('orgUnit'))->getDictsOrgUnit();

            $clients = Client::where('org_unit_id', $orgUnit->id)
                ->where('name', $name)
                ->where('surname', $surname)
                ->where('patronymic', $patronymic)
                ->where('birth_date', $birthDate)
                ->get();

            return view('components.same-client', compact('clients'))->render();
        }

        return '';
    }

    /**
     * @param Request $req
     * @return string
     */
    public function getClients(Request $req): string
    {
        if ($req->ajax()) {
            $surname = str_replace(" ", "%", $req->get('surname'));
            $name = str_replace(" ", "%", $req->get('name'));
            $patronymic = str_replace(" ", "%", $req->get('patronymic'));
            $birthDate = str_replace(" ", "%", $req->get('birth_date'));

            //поиск клиентов с аналогичными параметрами
            $orgUnit = OrgUnit::find(session('orgUnit'))->getDictsOrgUnit();

            $clients = Client::where('org_unit_id', $orgUnit->id)
                ->where('surname', 'like', "%$surname%")
                ->where('name', 'like', "%$name%")
                ->where('birth_date', 'like', "%$birthDate%");

            if ($patronymic) {
                $clients = $clients->where('patronymic', 'like', '%' . $patronymic . '%');
            }

            //сортировка пагинация
            $clients = $clients->orderBy('surname')
                ->paginate(config('app.admin.page_size', 20));

            return view('components.clients-tbody', compact('clients'))->render();
        }

        return '';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('clients.create', ['curClient' => new Client()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ClientRequest $request
     * @return RedirectResponse
     */
    public function store(ClientRequest $request): RedirectResponse
    {
        $client = new Client($request->validated());
        $client->org_unit_id = OrgUnit::find(session('orgUnit'))->getDictsOrgUnit()->id;

        if ($client->save()) {
            if ($request->get('isJSON') != null) {
                return back()->with(['status' => trans('message.model.created.success')])->withInput();
            } else {
                return redirect()->route('client.show', $client->id)
                    ->with(['status' => trans('message.model.created.success')]);
            }
        } else {
            return back()->withErrors(['msg' => trans('message.model.created.fail')])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $client = Client::find($id);

        if (!$client) {
            abort(404);
        }

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $curClient = Client::find($id);

        if (!$curClient) {
            abort(404);
        }

        return view('clients.create', compact('curClient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ClientRequest $req
     * @param int $id
     * @return RedirectResponse
     */
    public function update(ClientRequest $req, int $id): RedirectResponse
    {
        $editClient = Client::find($id);

        if (!$editClient) {
            abort(404);
        }

        $data = $req->validated();
        $result = $editClient->fill($data);

        if ($result->save()) {
            return redirect()->route('client.show', $result->id)
                ->with(['status' => trans('message.model.updated.success')]);
        } else {
            return back()->withErrors(['msg' => trans('message.model.updated.fail')])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $deletingClient = Client::find($id);

        try {
            if ($deletingClient && $deletingClient->delete()) {
                return redirect()->route('client.index')
                    ->with(['status' => trans('message.model.deleted.success')]);
            } else {
                return redirect()->route('client.index')
                    ->withErrors([
                        'msg' => trans('message.model.deleted.fail',
                            ['error' => ' в ClientController::destroy']
                        ),
                    ]);
            }
        } catch (QueryException $e) {
            return redirect()->route('client.show',
                ['id' => $id])->withErrors(['msg' => 'Невозможно удалить клиента, т.к нему привязаны объекты']);
        }
    }
}
