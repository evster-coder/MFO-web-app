<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ClientForm;
use App\Models\Loan;
use App\Models\OrgUnit;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = Loan::whereIn('orgunit_id', OrgUnit::whereDescendantOrSelf(session('OrgUnit'))->pluck('id'))->paginate(20);

        return view('loans.index', [
            'loans' => $loans,
        ]);
    }


    //получение списка займов
    public function getLoans(Request $req)
    {
        //Получить параметры поиска   
        $loanNumber = str_replace(" ", "%", $req->get('loanNumber'));
        $loanConclusionDate = str_replace(" ", "%", $req->get('loanConclusionDate'));
        $clientFio = str_replace(" ", "%", $req->get('clientFio'));
        $statusOpen = str_replace(" ", "%", $req->get('statusOpen'));

        $sortBy = $req->get('sortby');
        $sortDesc = $req->get('sortdesc');

        //сортировка фильтрация пагинация
        $loans = Loan::where('loanNumber', 'like', '%'.$loanNumber.'%')
                            ->where('loanConclusionDate', 'like', '%'.$loanConclusionDate.'%')
                            ->where('statusOpen', 'like', '%'.$statusOpen.'%')
                            ->orderBy($sortBy, $sortDesc)
                            ->paginate(20); 

        return view('components.loans-tbody', compact('loans'))->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = ClientForm::find($id);
        return view('loans.show', ['loan' => $loan]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }
}
