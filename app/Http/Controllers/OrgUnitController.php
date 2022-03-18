<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Models\OrgUnit;
use App\Models\OrgUnitParam;
use App\Models\ParamValue;
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
        $orgUnits = OrgUnit::all();

        $grouped = $orgUnits->groupBy('parent_id');

        foreach ($orgUnits as $orgUnit) {
            if ($grouped->has($orgUnit->id)) {
                $orgUnit->childOrgUnits = $grouped[$orgUnit->id];
            }
        }
        $orgUnits = $orgUnits->where('parent_id', null);

        return view('orgunits.index', ['orgUnits' => $orgUnits]);
    }

    public function changeorgunit(Request $request)
    {
        $orgUnitIdChange = $request->input('org_unit');

        if ($orgUnitIdChange != null
            && Auth::user()->canSetOrgUnit($orgUnitIdChange)) {
            session([
                'orgUnit' => $orgUnitIdChange,
                'orgUnitCode' => OrgUnit::find($orgUnitIdChange)->org_unit_code,
            ]);

            return back();
        } else {
            abort(403, 'Нет прав на данное действие');
        }
    }

    public function create($parentId)
    {
        if (OrgUnit::whereDescendantOrSelf(Auth::user()->org_unit_id)
            ->get()
            ->contains('id', $parentId)) {
            $parent = OrgUnit::find($parentId);

            $orgUnitParams = OrgUnitParam::orderBy('name')->get();

            $paramsArr = [];
            foreach ($orgUnitParams as $param) {
                $paramsArr[] = $param->getClosestValue($parentId);
            }
            $params = collect($paramsArr);


            $orgUnit = new OrgUnit();

            return view('orgunits.create', [
                'currOrgUnit' => $orgUnit,
                'parent' => $parent,
                'params' => $params,
            ]);
        } else {
            return back()->withErrors(['msg' => "Недостаточно прав для добавления в это подразделение!"]);
        }
    }

    public function store(Request $req)
    {
        if (OrgUnit::whereDescendantOrSelf(Auth::user()->org_unit_id)
            ->get()
            ->contains('id', $req->get('parent_id'))) {
            $orgUnit = new OrgUnit();

            $orgUnit->org_unit_code = $req->get('$this->org_unit_code');
            $orgUnit->has_dictionaries = $req->get('has_dictionaries');

            $orgUnit->parent_id = $req->get('parent_id');

            $orgUnit->save();

            //запись параметров
            $params = $req->params;
            foreach ($params as $key => $value) {
                if ($value != null) {
                    $param = OrgUnitParam::where('slug', $key)->first();
                    $paramValue = ParamValue::create(
                        [
                            'org_unit_id' => $orgUnit->id,
                            'org_unit_param_id' => $param->id,
                        ]);

                    switch ($param->data_type) {
                        case 'number':
                            $paramValue->data_as_number = $value;
                            break;
                        case 'date':
                            $paramValue->data_as_date = $value;
                            break;
                        default:
                            $paramValue->data_as_string = $value;
                            break;
                    }

                    $paramValue->save();
                }
            }

            return redirect()->route('orgunit.show', $orgUnit->id);
        } else {
            return back()->withErrors(['msg' => "Недостаточно прав для Добавления к этому подразделению!"]);
        }

    }

    public function update(Request $req, $id)
    {
        $editOrgUnit = OrgUnit::find($id);

        if (OrgUnit::whereDescendantOrSelf(Auth::user()->org_unit_id)
            ->get()
            ->contains('id', $id)) {
            if ($req->get('has_dictionaries') == 0) {
                //рекурсивный запрет справочников для дочерних узлов
                $childs = OrgUnit::whereDescendantOrSelf($editOrgUnit)->get();
                foreach ($childs as $child) {
                    $child->has_dictionaries = false;
                    $child->save();
                }
            } else {
                $editOrgUnit->has_dictionaries = true;
            }

            $editOrgUnit->org_unit_code = $req->get('$this->org_unit_code');

            if ($editOrgUnit->save()) {
                //запись параметров
                $params = $req->params;
                foreach ($params as $key => $value) {
                    if ($value != null) {
                        $param = OrgUnitParam::where('slug', $key)->first();
                        $paramValue = ParamValue::updateOrCreate(
                            [
                                'org_unit_id' => $editOrgUnit->id,
                                'org_unit_param_id' => $param->id,
                            ], []);

                        switch ($param->data_type) {
                            case 'string':
                                $paramValue->data_as_string = $value;
                                break;
                            case 'number':
                                $paramValue->data_as_number = $value;
                                break;
                            case 'date':
                                $paramValue->data_as_date = $value;
                                break;
                            default:
                                $paramValue->data_as_string = $value;
                                break;
                        }

                        $paramValue->save();

                        if ($req->params_cb) {
                            $deletedItems = $req->params_cb;
                            foreach ($deletedItems as $key => $value) {
                                $delete = ParamValue::where('org_unit_id', $editOrgUnit->id)
                                    ->where('org_unit_param_id', $value)
                                    ->first();
                                if ($delete) {
                                    $delete->delete();
                                }
                            }
                        }
                    }
                }

                return redirect()->route('orgunit.show', $editOrgUnit->id)
                    ->with(['status' => 'Успешно изменен']);
            } else {
                return redirect()->route('orgunit.show', $editOrgUnit->id)
                    ->withErrors(['msg' => "Ошибка обновления записи"]);

            }
        } else {
            return back()->withErrors(['msg' => "Недостаточно прав для Редактирования этого подразделения!"]);
        }

    }

    public function show($id)
    {
        $orgUnit = OrgUnit::find($id);
        $params = $orgUnit->params();

        $users = User::where('org_unit_id', $id)->get();

        //dd($params);
        return view('orgunits.show', [
            'orgUnit' => $orgUnit,
            'users' => $users,
            'params' => $params,
        ]);
    }

    public function destroy(Request $req, $id = null)
    {
        if ($id == null) {
            $id = $req->org_unit_id;
        }

        if (OrgUnit::whereDescendantOrSelf(Auth::user()->org_unit_id)
            ->get()
            ->contains('id', $id)) {
            if (OrgUnit::find($id)->isLeaf()) {
                $orgUnit = OrgUnit::find($id);
                $orgUnit->delete();

                return redirect()->route('orgunit.index')->with(['status' => 'Успешно удален.']);

            } else {
                return back()->withErrors(['msg' => 'Невозможно удалить подразделение, т.к. у него присутствуют дочерние узлы']);
            }
        } else {
            return back()->withErrors(['msg' => "Недостаточно прав для удаления этого подразделения!"]);
        }

    }

    public function edit($id)
    {
        if (OrgUnit::whereDescendantOrSelf(Auth::user()->org_unit_id)
            ->get()
            ->contains('id', $id)) {
            $editOrgUnit = OrgUnit::find($id);

            $parent = $editOrgUnit->parent()->first();

            $orgUnitParams = OrgUnitParam::orderBy('name')->get();

            $paramsArr = [];
            foreach ($orgUnitParams as $param) {
                $paramsArr[] = $param->getClosestValue($id);
            }
            $params = collect($paramsArr);


            return view('orgunits.create', [
                'currOrgUnit' => $editOrgUnit,
                'parent' => $parent,
                'params' => $params,
            ]);
        } else {
            return back()->withErrors(['msg' => "Недостаточно прав для редактирования этого подразделения!"]);
        }

    }
}
