<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;


use App\Models\ClientForm;
use App\Models\Loan;
use App\Models\Client;
use App\Models\OrgUnit;

use App\Exports\ClientsExport;

use DateTime;
class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $orgunit = OrgUnit::find(session('OrgUnit'))->getDictsOrgUnit();

        $clients = Client::where('orgunit_id', $orgunit->id)
                            ->orderBy('surname')    
                            ->paginate(20);
    	return view('clients.index', ['clients' => $clients]);
    }

    //экспорт таблицы в эксель
    public function export(Request $req)
    {
        $now = new DateTime('NOW');
        $filename = 'clients' . date_format($now, 'ymd') . '.xlsx';

        $surname = str_replace(" ", "%", $req->get('surname'));
        $name = str_replace(" ", "%", $req->get('name'));
        $patronymic = str_replace(" ", "%", $req->get('patronymic'));
        $birthDate = str_replace(" ", "%", $req->get('birth-date'));

        return (new ClientsExport($surname, $name, $patronymic, $birthDate))->download($filename);
    }

    public function getClientForms($id, Request $req)
    {
        if($req->ajax())
        {
            if($req->get('dateFrom') == "")
                $startDate = "1970-01-01";
            else
                $startDate = $req->get('dateFrom');

            if($req->get('dateTo') == "")
                $endDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime(now())) . " + 365 day"));
            else
                $endDate = $req->get('dateTo');
            $clientforms = ClientForm::where('client_id', $id)
                                    ->whereBetween('loanDate', [$startDate, $endDate])
                                    ->get();
            return view('components.client-clientforms-tbody', compact('clientforms'))->render();
        }
    }

    public function getLoans($id, Request $req)
    {
        if($req->ajax())
        {
            $loanNumber = str_replace(" ", "%", $req->get('loanNumber'));
            $loanConclusionDate = str_replace(" ", "%",$req->get('loanConclusionDate'));

            $loans = Loan::join('client_forms', 'loans.clientform_id', '=', 'client_forms.id')
                                    ->where('client_forms.client_id', $id)
                                    ->where('loanNumber', 'like', '%'.$loanNumber.'%')
                                    ->where('loanConclusionDate', 'like', '%'.$loanConclusionDate.'%')
                                    ->select('loans.*')
                                    ->get();

            return view('components.client-loans-tbody', compact('loans'))->render();
        }


    }

    //возвращает клиентов с такими же фио и датой рождения
    public function sameClients(Request $req)
    {
        if($req->ajax())
        {
            $surname = $req->get('surname');
            $name = $req->get('name');
            $patronymic = $req->get('patronymic');
            $birthDate = $req->get('birthDate');

            $orgunit = OrgUnit::find(session('OrgUnit'))->getDictsOrgUnit();

            $clients = Client::where('orgunit_id', $orgunit->id)
                                ->where('name', $name)
                                ->where('surname', $surname)
                                ->where('patronymic', $patronymic)
                                ->where('birthDate', $birthDate)
                                ->get();

            return view('components.same-client', compact('clients'))->render();
        }
    }

    public function getClients(Request $req)
    {
        if($req->ajax())
        {
            //Получить параметры поиска   
            $surname = str_replace(" ", "%", $req->get('surname'));
            $name = str_replace(" ", "%", $req->get('name'));
            $patronymic = str_replace(" ", "%", $req->get('patronymic'));
            $birthDate = str_replace(" ", "%", $req->get('birth-date'));

            //поиск клиентов с похожими параметрами
            $orgunit = OrgUnit::find(session('OrgUnit'))->getDictsOrgUnit();

            $clients = Client::where('orgunit_id', $orgunit->id)
                        ->where('surname', 'like', '%'.$surname.'%')
                        ->where('name', 'like', '%'.$name.'%')
                        ->where('birthDate', 'like', '%'.$birthDate.'%');

            if($patronymic == "")
                $clients = $clients->where(function($query){
                    $query->where('patronymic', 'like', "%%")->orWhereNull('patronymic');
                });
            else
                $clients = $clients->where('patronymic', 'like', '%'.$patronymic.'%');

            //сортировка пагинация
            $clients = $clients->orderBy('surname')
                        ->paginate(20);

            return view('components.clients-tbody', compact('clients'))->render();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $newClient = new Client();

        $orgunit = OrgUnit::find(session('OrgUnit'))->getDictsOrgUnit();
        $clients = Client::where('orgunit_id', $orgunit->id);

        return view('clients.create', ['curClient' => $newClient]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request)
    {
        //создаем клиента
        $client = new Client($request->all());
        $client->orgunit_id = OrgUnit::find(session('OrgUnit'))->getDictsOrgUnit()->id;

        $client->save();

        if ($client)
        {
            if($request->get('isJSON') != null)
            {
                return back()->with(['status' => 'Клиент успешно добавлен'])->withInput();
            }
            else
                return redirect()->route('client.show', $client->id);
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
        $client = Client::find($id);

        return view('clients.show',
         ['client' => $client]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $curClient = Client::find($id);

        if($curClient)
            return view('clients.create', ['curClient' => $curClient]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientRequest $req, $id)
    {

        $editClient = Client::find($id);
        if(empty($editClient))
            return back()->withErrors(['msg' => "Обновляемый объект не найден"])
                            ->withInput();

        $data = $req->all();
        $result = $editClient->fill($data);

        $result->save();

        if($result)
        {
            return redirect()->route('client.show', $result->id)
                                        ->with(['status' => 'Успешно изменен']);
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
        $deletingClient = Client::find($id);

        if($deletingClient)
        {
            try{
                $deletingClient->delete();
                return redirect()->route('client.index')
                        ->with(['status' => 'Клиент успешно удален']);
            }
            catch (\Illuminate\Database\QueryException $e){
                return redirect()->route('client.show', ['id' => $id])->withErrors(['msg' => 'Невозможно удалить клиента, т.к нему привязаны объекты']);
            }
        }
        else
        {
            return redirect()->route('client.index')
                ->withErrors(['msg' => 'Ошибка удаления в ClientController::destroy']);
        }

    }
}
