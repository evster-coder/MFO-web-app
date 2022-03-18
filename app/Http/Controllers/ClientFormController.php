<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ClientForm;
use App\Models\DictsData\MaritalStatus;
use App\Models\DictsData\Seniority;
use App\Models\Client;
use App\Models\OrgUnit;
use App\Models\Passport;

use App\Http\Requests\ClientFormRequest;

use App\Exports\ClientFormsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

use DB;
use DateTime;
class ClientFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientForms = ClientForm::whereIn('org_unit_id', OrgUnit::whereDescendantOrSelf(session('orgUnit'))->pluck('id'))->paginate(20);
        return view('clientforms.index', ['clientForms' => $clientForms]);
    }

    //экспорт таблицы в эксель
    public function export(Request $req)
    {
        $now = new DateTime('NOW');
        $filename = 'clientforms' . date_format($now, 'ymd') . '.xlsx';

        //Получить параметры поиска
        $id = str_replace(" ", "%", $req->get('id'));
        $loanDate = str_replace(" ", "%", $req->get('loan_date'));
        $clientFio = str_replace(" ", "%", $req->get('clientFio'));
        $state = $req->get('state');

        return (new ClientFormsExport($id, $loanDate, $clientFio, $state))->download($filename);

    }

    public function getForms(Request $req)
    {
            //Получить параметры поиска
            $id = str_replace(" ", "%", $req->get('id'));
            $loanDate = str_replace(" ", "%", $req->get('loan_date'));
            $clientFio = str_replace(" ", "%", $req->get('clientFio'));
            $state = $req->get('state');


            //фильтрация пагинация
            $clientForms = ClientForm::whereIn('org_unit_id', OrgUnit::whereDescendantOrSelf(session('orgUnit'))->pluck('id'))
                        ->where('client_forms.id', 'like', '%'.$id.'%')
                                ->where('loan_date', 'like', '%'.$loanDate.'%')
                                ->whereIn('client_id',
                                            Client::where(DB::raw('CONCAT_WS(" ", `surname`, `name`, `patronymic`) '),
                                                         'like',
                                                '%'.$clientFio.'%')->pluck('id'));

            //фильтрация по статусу
            if($state == 'considered')
            {
                //получить заявки в рассмотрении
                $clientForms = $clientForms
                        ->leftJoin('director_approvals', 'client_forms.director_approval_id', '=', 'director_approvals.id')
                        ->leftJoin('security_approvals', 'client_forms.security_approval_id', '=', 'security_approvals.id')
                        ->where(function($query){
                            $query->whereNull('client_forms.director_approval_id')
                            ->orWhere('director_approvals.approval', true);
                        })
                        ->where(function($query){
                            $query->whereNull('client_forms.security_approval_id')
                            ->orWhere('security_approvals.approval', true);
                        })
                        ->where(function($query){
                            $query->whereNull('client_forms.security_approval_id')
                            ->orWhereNull('client_forms.director_approval_id')
                            ->orWhere('security_approvals.approval', '!=', true)
                            ->orWhere('director_approvals.approval', '!=', true);
                        })->select('client_forms.*');
            }
            elseif($state == 'accepted')
            {
                $clientForms = $clientForms
                        ->leftJoin('director_approvals', 'client_forms.director_approval_id', '=', 'director_approvals.id')
                        ->leftJoin('security_approvals', 'client_forms.security_approval_id', '=', 'security_approvals.id')
                        ->where('director_approvals.approval', true)
                        ->where('security_approvals.approval', true)
                        ->select('client_forms.*');

            }
            elseif($state == 'rejected')
            {
                $clientForms = $clientForms
                        ->leftJoin('director_approvals', 'client_forms.director_approval_id', '=', 'director_approvals.id')
                        ->leftJoin('security_approvals', 'client_forms.security_approval_id', '=', 'security_approvals.id')
                        ->where('director_approvals.approval', false)
                        ->orWhere('security_approvals.approval', false)
                        ->select('client_forms.*');

            }
            elseif($state == 'loanSigned')
            {
                $clientForms = $clientForms->join('loans', 'client_forms.id', 'loans.client_form_id')
                        ->select('client_forms.*');
            }

            $clientForms = $clientForms->orderBy('loan_date')
                                       ->paginate(20);
            return view('components.clientforms-tbody', compact('clientForms'))->render();

    }

    public function getForm($id, Request $req)
    {
        if($req->ajax())
        {
            return view('components.clientform-info', ['clientForm' => ClientForm::find($id)])->render();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientForm = new ClientForm();

        $orgUnit = OrgUnit::find(session('orgUnit'))->getDictsOrgUnit();

        $clients = Client::where('org_unit_id', $orgUnit->id)->get();

        $maritalStatuses = MaritalStatus::all();
        $seniorities = Seniority::all();

        return view('clientforms.create',
            ['clientForm' => $clientForm,
            'clients' => $clients,
            'maritalStatuses' => $maritalStatuses,
            'seniorities' => $seniorities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ClientFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientFormRequest $request)
    {
        $clientForm = new ClientForm($request->all());
        $clientForm->org_unit_id = session('orgUnit');
        $clientForm->user_id = Auth::user()->id;
        $clientForm->has_credits = $request->get('has_credits');

        $curClient = Client::find($request->get('client_id'));

        $passport = new Passport();
        $passport->passport_series = $request->get('passport_series');
        $passport->passport_number = $request->get('passport_number');
        $passport->passport_issued_by = $request->get('passport_issued_by');
        $passport->passport_department_code = $request->get('passport_department_code');
        $passport->passport_date_issue = $request->get('passport_date_issue');
        $passport->passport_birthplace = $request->get('passport_birthplace');

        if($curClient->clientForms->count())
        {
            /** @var Passport $oldPassport */
            $oldPassport = $curClient->clientForms->last()->passport;
            if($oldPassport->passport_series == $passport->passport_series
                && $oldPassport->passport_number == $passport->passport_number
                && $oldPassport->passport_date_issue == $passport->passport_date_issue
                && $oldPassport->passport_issued_by == $passport->passport_issued_by
                && $oldPassport->passport_department_code == $passport->passport_department_code
                && $oldPassport->passport_birthplace == $passport->passport_birthplace)
                $passport = $oldPassport;
            else
                $passport->save();
        }
        else
        {
            $passport->save();
        }

        if(!$passport)
            return back()->withErrors(['msg' => 'Ошибка создания паспорта'])->withInput();

        $clientForm->passport_id = $passport->id;

        if ($clientForm->save())
        {
            return redirect()->route('clientForm.show', $clientForm->id);
        }
        else
            return back()->withErrors(['msg' => "Ошибка создания объекта"])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('clientforms.show',
            ['clientForm' => ClientForm::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editClientForm = ClientForm::find($id);
        $maritalStatuses = MaritalStatus::all();
        $seniorities = Seniority::all();
        $orgUnit = OrgUnit::find(session('orgUnit'))->getDictsOrgUnit();

        $clients = Client::where('org_unit_id', $orgUnit->id)->get();

        return view('clientforms.create',
            ['clientForm' => $editClientForm,
            'clients' => $clients,
            'maritalStatuses' => $maritalStatuses,
            'seniorities' => $seniorities,
        ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ClientFormRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientFormRequest $request, $id)
    {
        $clientForm = ClientForm::find($id);

        if(empty($clientForm))
            return back()->withErrors(['msg' => "Обновляемый объект не найден"])->withInput();
        $data = $request->all();

        $result = $clientForm->fill($data);
        $result->has_credits = $request->get('has_credits');

        $passport = new Passport();
        $passport->passport_series = $request->passport_series;
        $passport->passport_number = $request->passport_number;
        $passport->passport_issued_by = $request->passport_issued_by;
        $passport->passport_department_code = $request->passport_department_code;
        $passport->passport_date_issue = $request->passport_date_issue;
        $passport->passport_birthplace = $request->passport_birthplace;

        $oldPassport = $clientForm->passport;
        if($oldPassport->passport_series == $passport->passport_series
            && $oldPassport->passport_number == $passport->passport_number
            && $oldPassport->passport_date_issue == $passport->passport_date_issue
            && $oldPassport->passport_issued_by == $passport->passport_issued_by
            && $oldPassport->passport_department_code == $passport->passport_department_code
            && $oldPassport->passport_birthplace == $passport->passport_birthplace)
            $passport = $oldPassport;
        else
            $passport->save();

        if(!$passport)
            return back()->withErrors(['msg' => 'Ошибка обновления паспорта'])->withInput();

        $clientForm->passport_id = $passport->id;

        if($result->save())
        {
            return redirect()->route('clientForm.show', $result->id)
                             ->with(['status' => 'Анкета успешно обновлена']);
        }
        else
        {
            return back()->withErrors(['msg' => "Ошибка обновления записи"])
                         ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletingItem = ClientForm::find($id);
        if($deletingItem)
        {
            try{
                $deletingItem->delete();
                return redirect()->route('clientForm.index')
                        ->with(['status' => 'Заявка успешно удалена']);
            }
            catch (\Illuminate\Database\QueryException $e){
                return redirect()->route('clientForm.show', ['id' => $id])->withErrors(['msg' => 'Невозможно удалить заявку, т.к ней привязан договор займа']);
            }
        }
        else
        {
            return redirect()->route('clientForm.index')
                ->withErrors(['msg' => 'Ошибка удаления в ClientFormController::destroy']);
        }
    }
}
