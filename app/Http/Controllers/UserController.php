<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\OrgUnit;
use App\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Exports\UsersExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $users = User::find(Auth::user()->id)
            ->getDownUsers()
            ->with('roles')
            ->with('orgUnit')
            ->orderBy('username')
            ->paginate(config('app.admin.page_size', 20));

        return view('users.index', ['users' => $users]);
    }

    /**
     * @param Request $req
     * @return BinaryFileResponse
     */
    public function export(Request $req): BinaryFileResponse
    {
        $filename = 'users' . date('ymd') . '.xlsx';
        $query = $req->get("query");

        return (new UsersExport($query))->download($filename);
    }

    /**
     * @return View|RedirectResponse
     */
    public function changePassword()
    {
        if (Auth::user()->need_change_password) {
            return view('users.change-password');
        }

        return redirect()->route('user.profile');
    }

    /**
     * @return View|RedirectResponse
     */
    public function banned()
    {
        if (Auth::user()->blocked) {
            return view('banned');
        }

        return redirect()->route('user.profile');
    }

    /**
     * @param Request $req
     * @return RedirectResponse
     */
    public function updatePassword(Request $req): RedirectResponse
    {
        $req->validate([
            'new-password' => 'required',
            'new-password-confirm' => 'required|same:new-password',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($req->get('new-password'));
        $user->need_change_password = false;
        $user->save();

        return redirect()->route('user.profile')
            ->with(['status' => trans('passwords.changed')]);
    }

    /**
     * @return View
     */
    public function profile(): View
    {
        $user = Auth::user();

        return view('users.profile', compact('user'));
    }

    /**
     * @return RedirectResponse
     */
    public function resetYourselfPassword(): RedirectResponse
    {
        $user = Auth::user();
        $user->need_change_password = true;
        $user->save();

        return redirect()->route('user.profile')
            ->with(['status' => trans('passwords.reset')]);
    }

    /**
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $user = User::find($id);

        return view('users.show', compact('user'));
    }

    /**
     * @param Request $req
     * @return string
     */
    public function getUsers(Request $req): string
    {
        if ($req->ajax()) {
            $sortBy = $req->get('sortby');
            $sortDesc = $req->get('sortdesc');
            $query = $req->get('query', '');
            $query = str_replace(" ", "%", $query);

            $users = User::find(Auth::user()->id)
                ->getDownUsers()
                ->select('users.*')
                ->join('org_units', 'org_units.id', '=', 'users.org_unit_id')
                ->where('username', 'like', "%$query%")
                ->OrWhere('org_units.org_unit_code', 'like', "%$query%")
                ->OrWhere('full_name', 'like', "%$query%")
                ->orderBy($sortBy, $sortDesc)
                ->with(['roles', 'orgUnit'])
                ->paginate(config('app.admin.page_size', 20));

            return view('components.users-tbody', compact('users'))->render();
        }

        return '';
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $curUser = new User();

        $orgUnit = OrgUnit::find(Auth::user()->org_unit_id);
        $orgUnits = OrgUnit::whereDescendantOrSelf($orgUnit)
            ->select(['id', 'org_unit_code as text'])
            ->get();

        $roles = Role::all();

        return view('users.create', compact(['curUser', 'orgUnits', 'roles']));
    }

    public function store(StoreUserRequest $req)
    {
        $data = $req->all();

        $user = new User($data);
        $user->password = Hash::make($req->get('password'));
        $user->blocked = false;
        $user->need_change_password = false;

        if ($user->save()) {
            $roles = $req->get('roles');
            foreach ($roles as $role) {
                $user->assignRoles($role);
            }

            return redirect()->route('user.show', $user->id)
                ->with(['msg' => trans('message.model.created.success')]);
        } else {
            return back()->withErrors(['msg' => trans('message.model.created.fail')])
                ->withInput();
        }
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $curUser = User::find($id);

        $orgUnit = OrgUnit::find(Auth::user()->org_unit_id);
        $orgUnits = OrgUnit::whereDescendantOrSelf($orgUnit)
            ->select(['id', 'org_unit_code as text'])
            ->get();

        $roles = Role::all();

        return view('users.create', compact(['curUser', 'orgUnits', 'roles']));
    }

    /**
     * @param UpdateUserRequest $req
     * @param int $id
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $req, int $id): RedirectResponse
    {
        $editUser = User::find($id);

        if (!$editUser) {
            return back()->withErrors(['msg' => trans('message.model.not_found')])->withInput();
        }

        $result = $editUser->fill($req->all());

        if ($result->save()) {
            $curRoles = $result->roles;
            $roles = $req->get('roles');

            //удаляем неотмеченные чекбоксы
            foreach ($curRoles as $role) {
                if (!in_array($role->slug, $roles)) {
                    $result->unassignRoles($role->slug);
                }
            }

            //добавляем новые роли
            foreach ($roles as $role) {
                $result->assignRoles($role);
            }

            return redirect()->route('user.show', $result->id);

        } else {
            return back()->withErrors(['msg' => trans('message.model.updated.fail')])->withInput();
        }
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $deletingItem = User::find($id);

        if ($deletingItem && $deletingItem->delete()) {
            return redirect()->route('user.index')->with(['status' => trans('message.model.deleted.success')]);
        } else {
            return redirect()->route('user.index')
                ->withErrors([
                    'msg' => trans('message.model.deleted.success',
                        ['error' => ' в UserController::destroy']),
                ]);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse|RedirectResponse
     */
    public function block(int $id)
    {
        if (Auth::user()->id == $id) {
            return back()->withErrors(['msg' => 'Нельзя заблокировать себя']);
        } else {
            $banUser = User::find($id);

            if (!$banUser) {
                return response()->json(['Ошибка' => 'Вы не можете заблокировать этого пользователя!']);
            }

            if (Auth::user()->canSetOrgUnit($banUser->org_unit_id)) {
                $banUser->blocked = true;
                $banUser->save();

                return redirect()->route('user.show', $banUser->id)
                    ->with(['status' => 'Успешно заблокирован']);
            } else {
                return response()->json(['Ошибка' => 'Вы не можете заблокировать этого пользователя!']);
            }
        }
    }

    /**
     * @param int $id
     * @return JsonResponse|RedirectResponse
     */
    public function unblock(int $id)
    {
        if (Auth::user()->id == $id) {
            return back()->withErrors(['msg' => 'Нельзя разблокировать себя']);
        } else {
            $unbanUser = User::find($id);

            if (!$unbanUser) {
                return response()->json(['Ошибка' => 'Вы не можете разблокировать этого пользователя!']);
            }

            if (Auth::user()->canSetOrgUnit($unbanUser->org_unit_id)) {
                $unbanUser->blocked = false;
                $unbanUser->save();

                return redirect()->route('user.show', $unbanUser->id)
                    ->with(['status' => 'Успешно разблокирован']);
            } else {
                return response()->json(['Ошибка' => 'Вы не можете разблокировать этого пользователя!']);
            }

        }
    }

    /**
     * @param int $id
     * @return JsonResponse|RedirectResponse
     */
    public function resetPassword(int $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['Ошибка' => 'Вы не можете сбросить пароль этого пользователя!']);
        }

        if (Auth::user()->canSetOrgUnit($user->org_unit_id)) {
            $user->need_change_password = true;
            $user->password = Hash::make($user->username);
            $user->save();

            return redirect()->route('user.show', $user->id)
                ->with(['status' => 'Пароль успешно сброшен! Он совпадает с логином пользователя, его необходимо сменить при следующей авторизации пользователя.']);
        } else {
            return response()->json(['Ошибка' => 'Вы не можете сбросить пароль этого пользователя!']);
        }
    }
}
