<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Models\OrgUnit;


class OrgUnitController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orgunits = OrgUnit::all();

        $grouped = $orgunits->groupBy('parent_id');

        foreach ($orgunits as $orgunit) {
            if ($grouped->has($orgunit->id)) {
                $orgunit->childOrgUnits = $grouped[$orgunit->id];
            }
        }
        $orgunits = $orgunits->where('parent_id', null);

        return view('orgunits.index', ['orgunits' => $orgunits]);
    }

    public function changeorgunit(Request $request)
    {
        $orgUnitIdChange = $request->input('orgUnit');


    	if($orgUnitIdChange != null 
        && Auth::user()->canSetOrgUnit($orgUnitIdChange))
        {
            session(['OrgUnit' => $orgUnitIdChange,
                     'OrgUnitCode' => OrgUnit::find($orgUnitIdChange)->orgUnitCode],);
            return back();
        }
        else
            abort(403, 'Нет прав на данное действие');
    }

    /*public function getDownOrgUnits(Request $req)
    {
        $query = $req->get('query');

        $orgunits = OrgUnit::whereDescendantAndSelf(Auth::user()->orgunit_id)
                            ->where('orgUnitCode', 'like', '%'.$query.'%')
                            ->get();

        return response()->json($orgunits);
    }*/

}
