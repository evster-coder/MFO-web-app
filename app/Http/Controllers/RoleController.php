<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Http\Requests\RoleRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $roles = Role::orderBy('name')
            ->paginate(config('app.admin.page_size', 20));

        return view('roles.index', compact('roles'));
    }

    public function export(Request $req)
    {
    }

    /**
     * @param Request $req
     * @return string
     */
    public function getRoles(Request $req): string
    {
        if ($req->ajax()) {
            $sortBy = $req->get('sortby');
            $sortDesc = $req->get('sortdesc');
            $query = $req->get('query');
            $query = str_replace(" ", "%", $query);

            $roles = Role::where('name', 'like', "%$query%")
                ->OrWhere('slug', 'like', "%$query%")
                ->orderBy($sortBy, $sortDesc)
                ->paginate(10);

            return view('components.roles-tbody', compact('roles'))->render();
        }

        return '';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $role = new Role();
        $permissions = Permission::orderBy('name')->get();

        return view('roles.show', compact(['role', 'permissions']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     * @return RedirectResponse
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        $role = new Role($request->all());

        if ($role->save()) {
            //заполняем ее права
            $permissions = $request->get('perm');
            foreach ($permissions as $key => $value) {
                if ($value == "1") {
                    $role->permissions()->attach($key);
                } else {
                    $role->permissions()->detach($key);
                }
            }

            return redirect()->route('role.index')
                ->with(['status' => "Роль " . $role->name . " успешно добавлена"]);
        } else {
            return back()->withErrors(['msg' => trans('message.model.created.fail')])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function show(int $id)
    {
        $role = Role::find($id);
        $permissions = Permission::orderBy('name')->get();


        return $role != null
            ? view('roles.show', compact(['role', 'permissions']))
            : back()->withErrors(['msg' => trans('message.model.not_found')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $editRole = Role::find($id);

        if (!$editRole) {
            return back()->withErrors(['msg' => trans('message.model.not_found')])
                ->withInput();
        }

        // Получаем и обновляем права
        $permissions = $request->get('perm');

        if ($permissions) {
            foreach ($permissions as $key => $value) {

                if ($value == "1") {
                    if (!$editRole->permissions->contains('id', $key)) {
                        $editRole->permissions()->attach($key);
                    }
                } else {
                    if ($editRole->permissions->contains('id', $key)) {
                        $editRole->permissions()->detach($key);
                    }
                }
            }
        }

        return redirect()->route('role.index')
            ->with(['status' => "Роль " . $editRole->name . " успешно обновлена"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $deletingRole = Role::find($id);

        if ($deletingRole && $deletingRole->delete()) {
            return redirect()->route('role.index')
                ->with(['status' => trans('message.model.deleted.success')]);
        } else {
            return redirect()->route('role.index')
                ->withErrors([
                    'msg' => trans('message.model.deleted.success', [
                        'error' => ' в RoleController::destroy',
                    ]),
                ]);
        }
    }
}
