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
        $items = InterestRate::orderBy('percent_value')->paginate(10);
        return view('dictfields.interestrate.index', ['rates' => $items]);
    }

    public function axiosRates(Request $req)
    {
        $query = $req->get('query');

        $terms = InterestRate::where('percent_value', 'like', '%'.$query.'%')
                        ->orderBy('percent_value')->get();
        return Response::json($terms);

    }

    //получение данных по запросу
    public function getRates(Request $req)
    {
        if($req->ajax())
        {
            $query = $req->get('query');
            $query = str_replace(" ", "%", $query);

            $rates = InterestRate::where('percent_value', 'like', '%'.$query.'%')
                        ->orderBy('percent_value')
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
                'percent_value' => 'required|numeric|between:0,1000000.999',
        ]);

        $rateId = $request->dataId;
        InterestRate::updateOrCreate(['id' => $rateId],['percent_value' => $request->get('percent_value')]);
        if(empty($request->dataId))
            $msg = 'Элемент успешно создан.';
        else
            $msg = 'Элемент успешно обновлен.';
        return redirect()->route('interestrate.index')->with(['status' => $msg]);

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
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
     * @param $id
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
