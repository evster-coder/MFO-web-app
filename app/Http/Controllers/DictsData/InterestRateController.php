<?php

namespace App\Http\Controllers\DictsData;

use App\Models\DictsData\InterestRate;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class InterestRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = InterestRate::orderBy('percentValue', 'desc')->paginate(10);
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
     * @param  \App\Models\InterestRate  $interestRate
     * @return \Illuminate\Http\Response
     */
    public function show(InterestRate $interestRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InterestRate  $interestRate
     * @return \Illuminate\Http\Response
     */
    public function edit(InterestRate $interestRate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InterestRate  $interestRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InterestRate $interestRate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InterestRate  $interestRate
     * @return \Illuminate\Http\Response
     */
    public function destroy(InterestRate $interestRate)
    {
        //
    }
}
