<?php

namespace App\Http\Controllers\DictsData;

use App\Models\DictsData\Seniority;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Response;

class SeniorityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Seniority::orderBy('name')->paginate(10);
        return view('dictfields.seniority.index', ['senioritis' => $items]);
    }


    //возврат данных по запросу
    public function getSenioritis(Request $req)
    {
        if($req->ajax())
        {
            $query = $req->get('query');
            $query = str_replace(" ", "%", $query);

            $senioritis = Seniority::where('name', 'like', '%'.$query.'%')
                        ->orderBy('name')
                        ->paginate(10);

            return view('components.senioritis-tbody', compact('senioritis'))->render();
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

        $seniorityId = $request->dataId;
        Seniority::updateOrCreate(['id' => $seniorityId],['name' => $request->name]);
        if(empty($request->dataId))
            $msg = 'Элемент успешно создан.';
        else
            $msg = 'Элемент успешно обновлен.';
        return redirect()->route('seniority.index')->with(['status' => $msg]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seniority  $seniority
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seniority = Seniority::find($id);
        return Response::json($seniority);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seniority  $seniority
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletingItem = Seniority::find($id);

        if($deletingItem)
        {
            $deletingItem->delete();
            return redirect()->route('seniority.index')->with(['status' => 'Элемент успешно удален.']);
        }
        else
            return redirect()->route('seniority.index')
                ->withErrors(['msg' => 'Ошибка удаления в SeniorityController::destroy']);

    }
}
