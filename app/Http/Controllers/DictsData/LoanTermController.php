<?php

namespace App\Http\Controllers\DictsData;

use App\Models\DictsData\LoanTerm;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class LoanTermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = LoanTerm::orderBy('daysAmount', 'desc')->paginate(10);
        return $items;
        //return view('dicts.seniority.index', ['items' => $items]);
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
     * @param  \App\Models\LoanTerm  $loanTerm
     * @return \Illuminate\Http\Response
     */
    public function show(LoanTerm $loanTerm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoanTerm  $loanTerm
     * @return \Illuminate\Http\Response
     */
    public function edit(LoanTerm $loanTerm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoanTerm  $loanTerm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoanTerm $loanTerm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoanTerm  $loanTerm
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanTerm $loanTerm)
    {
        //
    }
}
