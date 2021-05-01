<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class OrgUnitController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        return view('auth.login');
    }

    public function changeorgunit(Request $request)
    {
        $orgUnitIdChange = $request->input('orgUnit');


    	if($orgUnitIdChange != null 
        && Auth::user()->canSetOrgUnit($orgUnitIdChange))
        {
            session(['OrgUnit' => $orgUnitIdChange]);
            return back();
        }
        else
            abort(404);
    }

}
