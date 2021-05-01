<?php

namespace App\Http\Controllers\DictsData;

use App\Models\DictsData\Seniority;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SeniorityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Seniority::orderBy('name', 'desc')->paginate(10);
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
     * @param  \App\Models\Seniority  $seniority
     * @return \Illuminate\Http\Response
     */
    public function show(Seniority $seniority)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seniority  $seniority
     * @return \Illuminate\Http\Response
     */
    public function edit(Seniority $seniority)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seniority  $seniority
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seniority $seniority)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seniority  $seniority
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seniority $seniority)
    {
        //
    }
}
