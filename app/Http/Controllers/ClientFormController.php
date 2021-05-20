<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ClientForm;
use App\Models\DictsData\MaritalStatus;
use App\Models\DictsData\Seniority;
use App\Models\Client;
use App\Models\OrgUnit;


use Illuminate\Support\Facades\Auth;

use DB;
class ClientFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientforms = ClientForm::whereIn('orgunit_id', OrgUnit::whereDescendantOrSelf(session('OrgUnit'))->pluck('id'))->paginate(20);
        return view('clientforms.index', ['clientforms' => $clientforms]);
    }

    public function getForms(Request $req)
    {
            //Получить параметры поиска   
            $id = str_replace(" ", "%", $req->get('id'));
            $loanDate = str_replace(" ", "%", $req->get('loanDate'));
            $clientFio = str_replace(" ", "%", $req->get('clientFio'));


            //сортировка пагинация
            $clientforms = ClientForm::where('id', 'like', '%'.$id.'%')
                                ->where('loanDate', 'like', '%'.$loanDate.'%')
                                ->whereIn('client_id', 
                                            Client::where(DB::raw('CONCAT_WS(" ", `surname`, `name`, `patronymic`) '),
                                                         'like', 
                                                '%'.$clientFio.'%')->pluck('id'))
                                ->orderBy('loanDate')
                                ->paginate(20);

            return view('components.clientforms-tbody', compact('clientforms'))->render();

    }

    public function pendingApproval()
    {
        return view('clientforms.approvals.index');
    }

    public function showApproval()
    {
        return view('clientforms.approvals.show');
    }


    public function getForm($id, Request $req)
    {
        if($req->ajax())
        {
            $clientform = ClientForm::find($id);

            return view('components.clientform-info', compact('clientform'))->render();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientform = new ClientForm();
        $clients = Client::select('id', 'name as text')
                            ->get();
        $maritalstatuses = MaritalStatus::all();
        $seniorities = Seniority::all();


        return view('clientforms.create',
            ['clientform' => $clientform,
            'clients' => $clients,
            'maritalstatuses' => $maritalstatuses,
            'seniorities' => $seniorities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $clientform = new ClientForm($request->all());
        $clientform->orgunit_id = session('OrgUnit');
        $clientform->user_id = Auth::user()->id;
        $clientform->hasCredits = $request->hasCredits;

        $clientform->save();

        if ($clientform)
        {
            return redirect()->route('clientform.show', $clientform->id);
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
        $clientform = ClientForm::find($id);
        return view('clientforms.show',
            ['clientform' => $clientform]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editClientform = ClientForm::find($id);

        return view('clientforms.create',
            ['clientform' => $editClientform,]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
                return redirect()->route('clientform.index')
                        ->with(['status' => 'Заявка успешно удалена']);
            }
            catch (\Illuminate\Database\QueryException $e){
                return redirect()->route('clientform.show', ['id' => $id])->withErrors(['msg' => 'Невозможно удалить заявку, т.к ней привязан договор займа']);
            }
        }
        else
        {
            return redirect()->route('clientform.index')
                ->withErrors(['msg' => 'Ошибка удаления в ClientFormController::destroy']);
        }


    }
}
