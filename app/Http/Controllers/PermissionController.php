<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Permission;
use App\Models\Role;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        //simple validation (no need to add Request class)
        $request->validate([
                'name' => 'required|string|min:2|max:100',
                'slug' => 'required|string|min:2|max:100',
            ]);

        $sameName = Permission::where('name', $request->name)->first();
        $sameSlug = Permission::where('slug', $request->slug)->first();
        if($sameName != null && (empty($request->permId) || $sameName->id != $request->permId))
        {
            return redirect()->route('perm.index')->withErrors(['msg' => 'Запись с таким значением Названия уже существует']);
        }

        if($sameSlug != null && (empty($request->permId) || $sameSlug->id != $request->permId))
        {
            return redirect()->route('perm.index')->withErrors(['msg' => 'Запись с таким значением slug уже существует']);
        }
        $permId = $request->permId;
        $perm = Permission::updateOrCreate(['id' => $permId],['name' => $request->name, 'slug' => $request->slug]);
        if(empty($request->permId))
        {
            //добавляем все новые права админу
            $admins = Role::where('slug', 'admin')->get();
            foreach($admins as $admin)
                $admin->permissions()->attach($perm->id);

            $msg = 'Право успешно создано.';
        }
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
