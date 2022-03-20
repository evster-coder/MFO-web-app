<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use Response;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $perms = Permission::orderBy('name')
            ->paginate(config('app.admin.page_size', 20));

        return view('perms.index', compact('perms'));
    }

    /**
     * @param Request $req
     * @return string
     */
    public function getPerms(Request $req): string
    {
        if ($req->ajax()) {
            $sortBy = $req->get('sortby');
            $sortDesc = $req->get('sortdesc');
            $query = $req->get('query');
            $query = str_replace(" ", "%", $query);

            $perms = Permission::where('name', 'like', "%$query%")
                ->OrWhere('slug', 'like', "%$query%")
                ->orderBy($sortBy, $sortDesc)
                ->paginate(config('app.admin.page_size', 20));

            return view('components.perms-tbody', compact('perms'))->render();
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
            'name' => 'required|string|min:2|max:100',
            'slug' => 'required|string|min:2|max:100',
        ]);

        $sameName = Permission::where('name', $request->get('name'))->first();
        $sameSlug = Permission::where('slug', $request->get('slug'))->first();

        if ($sameName != null && (empty($request->permId) || $sameName->id != $request->permId)) {
            return redirect()->route('perm.index')->withErrors([
                'msg' => 'Запись с таким значением Названия уже существует',
            ]);
        }

        if ($sameSlug != null && (empty($request->permId) || $sameSlug->id != $request->permId)) {
            return redirect()->route('perm.index')->withErrors([
                'msg' => 'Запись с таким значением slug уже существует',
            ]);
        }

        $permId = $request->get('permId');
        $perm = Permission::updateOrCreate(['id' => $permId], [
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
        ]);

        if (empty($request->permId)) {
            //добавляем все новые права админу
            $admins = Role::where('slug', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->permissions()->attach($perm->id);
            }

            $msg = trans('message.model.created.success');
        } else {
            $msg = trans('message.model.updated.success');
        }

        return redirect()->route('perm.index')->with(['status' => $msg]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $perm = Permission::find($id);

        return Response::json($perm);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $deletingItem = Permission::find($id);

        if ($deletingItem && $deletingItem->delete()) {
            return redirect()->route('perm.index')
                ->with(['status' => trans('message.model.deleted.success')]);
        } else {
            return redirect()->route('perm.index')
                ->withErrors([
                    'msg' => trans('message.model.deleted.fail',
                        ['error' => ' в PermissionController::destroy']),
                ]);
        }
    }
}
