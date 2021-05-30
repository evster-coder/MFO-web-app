<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;

use App\Models\ClientForm;
use App\Models\Client;
use App\Models\OrgUnit;

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
    	$clientforms = ClientForm::where('client_id', $id)->get();

        return view('clients.show',
         ['clientforms' => $clientforms,
         'client' => $client
     ]);
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
