<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OrgUnitParam;

use Response;

class OrgUnitParamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = OrgUnitParam::orderBy('name')->paginate(10);

        return view('orgunits.params.index', ['params' => $params]);
    }

    //возврат данных по запросу
    public function getParams(Request $req)
    {
        if($req->ajax())
        {
            $query = $req->get('query');
            $query = str_replace(" ", "%", $query);

            $params = OrgUnitParam::where('name', 'like', '%'.$query.'%')
                        ->orWhere('slug', 'like', '%'.$query.'%')
                        ->orderBy('name')
                        ->paginate(10);

            return view('components.orgunitparams-tbody', compact('params'))->render();
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //simple validation (no need to add Request class)
        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'slug' => 'required|string|min:3|max:50',
        ]);

        $paramId = $request->dataId;

        //Если добавление
        if(empty($request->dataId))
        {
            $dataTypeValue = "";
            if($request->data_type == 'number')
                $dataTypeValue = 'number';
            else if ($request->data_type == 'date')
                $dataTypeValue = 'date';
            else $dataTypeValue = 'string';
            OrgUnitParam::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'data_type' => $dataTypeValue,
                ]);
            $msg = 'Элемент успешно создан.';
        }
        else
        {
            $param = OrgUnitParam::find($paramId);

            if($param == null)
                return redirect()->route('param.index')
                    ->withErrors(['msg' => 'Обновляемая запись не найдена']);

            $param->name = $request->name;
            $param->slug = $request->slug;
            $param->save();

            $msg = 'Элемент успешно обновлен.';
        }
        return redirect()->route('param.index')->with(['status' => $msg]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Response::json(OrgUnitParam::find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletingItem = OrgUnitParam::find($id);

        if($deletingItem)
        {
            $deletingItem->delete();
            return redirect()->route('param.index')->with(['status' => 'Элемент успешно удален.']);
        }
        else
            return redirect()->route('param.index')
                ->withErrors(['msg' => 'Ошибка удаления в OrgUnitParamController::destroy']);

    }
}
