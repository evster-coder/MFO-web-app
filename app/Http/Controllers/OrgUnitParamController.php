<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\OrgUnitParam;
use Response;

class OrgUnitParamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $params = OrgUnitParam::orderBy('name')
            ->paginate(config('app.admin.page_size', 20));

        return view('orgunits.params.index', ['params' => $params]);
    }

    /**
     * @param Request $req
     * @return string
     */
    public function getParams(Request $req): string
    {
        if ($req->ajax()) {
            $query = $req->get('query');
            $query = str_replace(" ", "%", $query);

            $params = OrgUnitParam::where('name', 'like', "%$query%")
                ->orWhere('slug', 'like', "%$query%")
                ->orderBy('name')
                ->paginate(10);

            return view('components.orgunitparams-tbody', compact('params'))->render();
        }

        return '';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'slug' => 'required|string|min:3|max:50',
        ]);

        $paramId = $request->get('dataId');

        // Добавление
        if (empty($request->dataId)) {
            $dataType = $request->get('data_type');

            if ($dataType == 'number') {
                $dataTypeValue = 'number';
            } else {
                if ($dataType == 'date') {
                    $dataTypeValue = 'date';
                } else {
                    $dataTypeValue = 'string';
                }
            }

            OrgUnitParam::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'data_type' => $dataTypeValue,
            ]);

            $msg = trans('message.model.created.success');
        } else {
            $param = OrgUnitParam::find($paramId);

            if ($param == null) {
                return redirect()->route('param.index')
                    ->withErrors(['msg' => trans('message.model.not_found')]);
            }

            $param->name = $request->name;
            $param->slug = $request->slug;
            $param->save();

            $msg = trans('message.model.updated.success');
        }

        return redirect()->route('param.index')
            ->with(['status' => $msg]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        return Response::json(OrgUnitParam::find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $deletingItem = OrgUnitParam::find($id);

        if ($deletingItem && $deletingItem->delete()) {
            return redirect()->route('param.index')
                ->with(['status' => trans('message.model.deleted.success')]);
        } else {
            return redirect()->route('param.index')
                ->withErrors([
                    'msg' => trans('message.model.deleted.fail',
                        ['error' => ' в OrgUnitParamController::destroy']),
                ]);
        }
    }
}
