<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Models\OrgUnit;
use App\Models\User;

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

    public function create($parentId)
    {
        if(OrgUnit::whereDescendantOrSelf(Auth::user()->orgunit_id)
                                ->get()
                                ->contains('id', $parentId))
        {
            $parent = OrgUnit::find($parentId);

            $orgunit = new OrgUnit();
            return view('orgunits.create', [
                'currOrgunit' => $orgunit,
                'parent' => $parent]);
        }
        else
            return back()->withErrors(['msg' => "Недостаточно прав для добавления в это подразделение!"]);
    }

    public function store(Request $req)
    {
        if(OrgUnit::whereDescendantOrSelf(Auth::user()->orgunit_id)
                        ->get()
                        ->contains('id', $req->get('parent_id')))

        {
            $orgUnit = new OrgUnit();

            $orgUnit->orgUnitCode = $req->get('orgUnitCode');
            $orgUnit->hasDictionaries = $req->get('hasDictionaries');

            $orgUnit->parent_id = $req->get('parent_id');

            $orgUnit->save();

            return redirect()->route('orgunit.show', $orgUnit->id);
        }
        else
            return back()->withErrors(['msg' => "Недостаточно прав для Добавления к этому подразделению!"]);

    }

    public function update(Request $req, $id)
    {
        $editOrgunit = OrgUnit::find($id);

        if(OrgUnit::whereDescendantOrSelf(Auth::user()->orgunit_id)
                        ->get()
                        ->contains('id', $id))
        {
            if($req->get('hasDictionaries') == 0)
            {
                //рекурсивный запрет справочников для дочерних узлов
                $childs = OrgUnit::whereDescendantOrSelf($editOrgunit)->get();
                foreach($childs as $child)
                {
                    $child->hasDictionaries = false;
                    $child->save();
                }
            }
            else
                $editOrgunit->hasDictionaries = true;

            $editOrgunit->orgUnitCode = $req->get('orgUnitCode');

            $editOrgunit->save();

            if($editOrgunit)
            {
                return redirect()->route('orgunit.show', $editOrgunit->id)
                        ->with(['status' => 'Успешно изменен']);
            }
            else
            {
                return redirect()->route('orgunit.show', $editOrgunit->id)
                        ->withErrors(['msg' => "Ошибка обновления записи"]);

            }
        }
        else
            return back()->withErrors(['msg' => "Недостаточно прав для Редактирования этого подразделения!"]);

    }

    public function show($id)
    {
        $orgunit = OrgUnit::find($id);
        $users = User::where('orgunit_id', $id)->get();
        return view('orgunits.show', ['orgunit' => $orgunit, 'users' => $users]);
    }

    public function destroy(Request $req, $id = null)
    {
        if($id == null)
            $id = $req->orgunit_id;

        if(OrgUnit::whereDescendantOrSelf(Auth::user()->orgunit_id)
                                ->get()
                                ->contains('id', $id))
        {
            if(OrgUnit::find($id)->isLeaf())
            {
                $orgunit = OrgUnit::find($id);
                $orgunit->delete();
                return redirect()->route('orgunit.index')->with(['status' => 'Успешно удален.']);

            }
            else
                return back()->withErrors(['msg' => 'Невозможно удалить подразделение, т.к. у него присутствуют дочерние узлы']);
        }
        else
            return back()->withErrors(['msg' => "Недостаточно прав для удаления этого подразделения!"]);

    }

    public function edit($id)
    {
        if(OrgUnit::whereDescendantOrSelf(Auth::user()->orgunit_id)
                                ->get()
                                ->contains('id', $id))
        {
            $editOrgunit = OrgUnit::find($id);

            $parent = $editOrgunit->parent()->first();

            return view('orgunits.create',[
                'currOrgunit' => $editOrgunit,
                'parent' => $parent,
            ]);
        }
        else
            return back()->withErrors(['msg' => "Недостаточно прав для редактирования этого подразделения!"]);

    }
}
