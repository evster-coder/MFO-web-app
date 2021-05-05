<?php

namespace App\Http\Controllers\DictsData;

use App\Models\DictsData\MaritalStatus;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Redirect,Response;

class MaritalStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = MaritalStatus::orderBy('name')->paginate(10);
        return view('dictfields.maritalstatus.index', ['statuses' => $items]);
    }

    //возврат данных по запросу
    public function getStatuses(Request $req)
    {
        if($req->ajax())
        {
            $query = $req->get('query');
            $query = str_replace(" ", "%", $query);

            $statuses = MaritalStatus::where('name', 'like', '%'.$query.'%')
                        ->orderBy('name')
                        ->paginate(10);

            return view('components.maritalstatuses-tbody', compact('statuses'))->render();
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //simple validation (no need to add Request class)
        $request->validate([
                'name' => 'required|string|min:1|max:100',
        ]);

        $statusId = $request->dataId;
        MaritalStatus::updateOrCreate(['id' => $statusId],['name' => $request->name]);
        if(empty($request->dataId))
            $msg = 'Элемент успешно создан.';
        else
            $msg = 'Элемент успешно обновлен.';
        return redirect()->route('maritalstatus.index')->with(['status' => $msg]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaritalStatus  $maritalStatus
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = MaritalStatus::find($id);
        return Response::json($status);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaritalStatus  $maritalStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletingItem = MaritalStatus::find($id);

        if($deletingItem)
        {
            $deletingItem->delete();
            return redirect()->route('maritalstatus.index')->with(['status' => 'Элемент успешно удален.']);
        }
        else
            return redirect()->route('maritalstatus.index')
                ->withErrors(['msg' => 'Ошибка удаления в MaritalStatusController::destroy']);
    }
}
