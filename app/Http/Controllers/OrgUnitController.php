<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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
     * @return View
     */
    public function index(): View
    {
        $orgUnits = OrgUnit::all();
        $grouped = $orgUnits->groupBy('parent_id');

        foreach ($orgUnits as $orgUnit) {
            if ($grouped->has($orgUnit->id)) {
                $orgUnit->childOrgUnits = $grouped[$orgUnit->id];
            }
        }
        $orgUnits = $orgUnits->where('parent_id', null);

        return view('orgunits.index', compact('orgUnits'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function changeorgunit(Request $request): RedirectResponse
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

        return back();
    }

    /**
     * @param $parentId
     * @return RedirectResponse|View
     */
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

            $currOrgUnit = new OrgUnit();

            return view('orgunits.create', compact(['currOrgUnit', 'parent', 'params']));
        } else {
            return back()->withErrors(['msg' => "Недостаточно прав для добавления в это подразделение!"]);
        }
    }

    /**
     * @param Request $req
     * @return RedirectResponse
     */
    public function store(Request $req): RedirectResponse
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
            $params = $req->get('params');
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

    /**
     * @param Request $req
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $req, int $id): RedirectResponse
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

            $editOrgUnit->org_unit_code = $req->get('org_unit_code');

            if ($editOrgUnit->save()) {
                //запись параметров
                $params = $req->get('params');
                foreach ($params as $key => $value) {
                    if ($value != null) {
                        $param = OrgUnitParam::where('slug', $key)->first();
                        $paramValue = ParamValue::updateOrCreate(
                            [
                                'org_unit_id' => $editOrgUnit->id,
                                'org_unit_param_id' => $param->id,
                            ], []);

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

                        if ($req->get('params_cb')) {
                            $deletedItems = $req->get('params_cb');
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
                    ->with(['status' => trans('message.model.updated.success')]);
            } else {
                return redirect()->route('orgunit.show', $editOrgUnit->id)
                    ->withErrors(['msg' => trans('message.model.updated.fail')]);
            }
        } else {
            return back()->withErrors(['msg' => "Недостаточно прав для Редактирования этого подразделения!"]);
        }
    }

    /**
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $orgUnit = OrgUnit::find($id);
        $params = $orgUnit->params();

        $users = User::where('org_unit_id', $id)->get();

        return view('orgunits.show', compact(['orgUnit', 'users', 'params']));
    }

    /**
     * @param Request $req
     * @param int|null $id
     * @return RedirectResponse
     */
    public function destroy(Request $req, ?int $id = null): RedirectResponse
    {
        if ($id == null) {
            $id = $req->get('org_unit_id');
        }

        if (OrgUnit::whereDescendantOrSelf(Auth::user()->org_unit_id)
            ->get()
            ->contains('id', $id)) {
            if (OrgUnit::find($id)->isLeaf()) {
                $orgUnit = OrgUnit::find($id);
                $orgUnit->delete();

                return redirect()->route('orgunit.index')
                    ->with(['status' => trans('message.model.deleted.success')]);
            } else {
                return back()->withErrors([
                    'msg' => trans('message.model.deleted.fail', ['error' => ' т.к. у него присутствуют дочерние узлы']),
                ]);
            }
        } else {
            return back()->withErrors(['msg' => "Недостаточно прав для удаления этого подразделения!"]);
        }

    }

    /**
     * @param int $id
     * @return View|RedirectResponse
     */
    public function edit(int $id)
    {
        if (OrgUnit::whereDescendantOrSelf(Auth::user()->org_unit_id)
            ->get()
            ->contains('id', $id)) {

            $currOrgUnit = OrgUnit::find($id);
            $parent = $currOrgUnit->parent()->first();
            $orgUnitParams = OrgUnitParam::orderBy('name')->get();

            $paramsArr = [];
            foreach ($orgUnitParams as $param) {
                $paramsArr[] = $param->getClosestValue($id);
            }
            $params = collect($paramsArr);

            return view('orgunits.create',compact(['currOrgUnit', 'parent', 'params']));
        } else {
            return back()->withErrors(['msg' => "Недостаточно прав для редактирования этого подразделения!"]);
        }
    }
}
