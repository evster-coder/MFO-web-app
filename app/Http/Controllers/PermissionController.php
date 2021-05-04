<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Permission;

use Redirect,Response;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perms = Permission::orderBy('name')
                            ->paginate(10);

        return view('perms.index', ['perms' => $perms]);
    }

    public function getPerms(Request $req)
    {
        if($req->ajax())
        {
            $sortBy = $req->get('sortby');
            $sortDesc = $req->get('sortdesc');
            $query = $req->get('query');
            $query = str_replace(" ", "%", $query);

            $perms = Permission::where('name', 'like', '%'.$query.'%')
                        ->OrWhere('slug','like', '%'.$query.'%')
                        ->orderBy($sortBy, $sortDesc)
                        ->paginate(10);

            return view('components.perms-tbody', compact('perms'))->render();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('perms.create');
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
            'name' => 'required|string|min:2|max:100|unique:permissions',
            'slug' => 'required|string|min:2|max:100|unique:permissions',
        ]);

        $permId = $request->permId;
        Permission::updateOrCreate(['id' => $permId],['name' => $request->name, 'slug' => $request->slug]);
        if(empty($request->permId))
            $msg = 'Право успешно создано.';
        else
            $msg = 'Право успешно обновлено';
        return redirect()->route('perm.index')->with(['status' => $msg]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $perm = Permission::find($id);
        return Response::json($perm);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletingItem = Permission::find($id);

        if($deletingItem)
        {
            $deletingItem->delete();
            return redirect()->route('perm.index')->with(['status' => 'Право успешно удалено.']);
        }
        else
            return redirect()->route('perm.index')
                ->withErrors(['msg' => 'Ошибка удаления в PermissionController::destroy']);
    }
}
