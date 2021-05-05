<?php

namespace App\Http\Controllers\DictsData;

use App\Models\DictsData\InterestRate;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Response;

class InterestRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = InterestRate::orderBy('percentValue')->paginate(10);
        return view('dictfields.interestrate.index', ['rates' => $items]);
    }

    public function getRates(Request $req)
    {
        if($req->ajax())
        {
            $query = $req->get('query');
            $query = str_replace(" ", "%", $query);

            $rates = InterestRate::where('percentValue', 'like', '%'.$query.'%')
                        ->orderBy('percentValue')
                        ->paginate(10);

            return view('components.interestrates-tbody', compact('rates'))->render();
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
                'percentValue' => 'required|numeric|between:0,1000000.999',
        ]);

        $rateId = $request->dataId;
        InterestRate::updateOrCreate(['id' => $rateId],['percentValue' => $request->percentValue]);
        if(empty($request->dataId))
            $msg = 'Элемент успешно создан.';
        else
            $msg = 'Элемент успешно обновлен.';
        return redirect()->route('interestrate.index')->with(['status' => $msg]);

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InterestRate  $interestRate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $interestRate = InterestRate::find($id);
        return Response::json($interestRate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InterestRate  $interestRate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletingItem = InterestRate::find($id);

        if($deletingItem)
        {
            $deletingItem->delete();
            return redirect()->route('interestrate.index')
                            ->with(['status' => 'Элемент успешно удален.']);
        }
        else
            return redirect()->route('interestrate.index')
                ->withErrors(['msg' => 'Ошибка удаления в InterestRateController::destroy']);

    }
}
