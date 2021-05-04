<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;

use App\Http\Requests\RoleRequest;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::orderBy('name')->paginate(10);

        return view('roles.index', ['roles' => $roles]);
    }

    public function getRoles(Request $req)
    {
        if($req->ajax())
        {
            $sortBy = $req->get('sortby');
            $sortDesc = $req->get('sortdesc');
            $query = $req->get('query');
            $query = str_replace(" ", "%", $query);

            $roles = Role::where('name', 'like', '%'.$query.'%')
                        ->OrWhere('slug','like', '%'.$query.'%')
                        ->orderBy($sortBy, $sortDesc)
                        ->paginate(10);

            return view('components.roles-tbody', compact('roles'))->render();
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $newRole = new Role();

        $permissions = Permission::orderBy('name')->get();

        return view('roles.show', 
                    ['role' => $newRole,
                    'permissions' => $permissions,
                ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $data = $request->all();
        $role = new Role($data);

        $role->save();

        if ($role)
        {
            //заполняем ее права
            $permissions = $request->get('perm');
            foreach ($permissions as $key => $value)
            {
                if($value == "1")
                {
                    $role->permissions()->attach($key);
                }
                else
                {
                    $role->permissions()->detach($key);
                }
            }

            return redirect()->route('role.index')->with(['status' => "Роль ".$role->name." yспешно добавлена"]);
        }
        else
            return back()->withErrors(['msg' => "Ошибка создания объекта"])->withInput();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $permissions = Permission::orderBy('name')->get();


        if($role != null)
            return view('roles.show', ['role' => $role,
                                       'permissions' => $permissions]);
        else
            return back()->withErrors(['msg' => "Указанная роль не найдена!"]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $editRole = Role::find($id);

        if(empty($editRole))
            return back()->withErrors(['msg' => "Обновляемый объект не найден"])->withInput();


        //получаем и обновляем права
        $permissions = $request->get('perm');
        if($permissions)
            foreach ($permissions as $key => $value)
            {
                if($value == "1")
                {
                    if(!$editRole->permissions->contains('id', $key))
                        $editRole->permissions()->attach($key);
                }
                else
                {
                    if($editRole->permissions->contains('id', $key))
                        $editRole->permissions()->detach($key);
                }
            }

        return redirect()->route('role.index')->with(['status' => "Роль ".$editRole->name." yспешно обновлена"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletingRole = Role::find($id);

        if($deletingRole)
        {
            $deletingRole->delete();
            return redirect()->route('role.index')->with(['status' => 'Роль успешно удалена']);
        }
        else
        {
            return redirect()->route('role.index')
                ->withErrors(['msg' => 'Ошибка удаления в RoleController::destroy']);

        }
    }
}
