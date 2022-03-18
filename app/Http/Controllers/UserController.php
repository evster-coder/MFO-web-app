<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\OrgUnit;
use App\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

use DateTime;

class UserController extends Controller
{
    public function index(Request $req)
    {
        //dd("index");

        $users = User::find(Auth::user()->id)->getDownUsers()
                            ->with('roles')
                            ->with('orgUnit')
                            ->orderBy('username')
                            ->paginate(10);

        return view('users.index', ['users' => $users]);
    }

    public function export(Request $req)
    {
        $now = new DateTime('NOW');
        $filename = 'users' . date_format($now, 'ymd') . '.xlsx';
        $query = $req->get("query");
        return (new UsersExport($query))->download($filename);
    }

    public function changePassword()
    {
        if(Auth::user()->need_change_password)
            return view('users.change-password');
        else abort(404);
    }

    public function banned()
    {
        if(Auth::user()->blocked)
            return view('banned');
        else return redirect()->route('user.profile');
    }

    public function updatePassword(Request $req)
    {
        //simple validation (no need to add Request class)
        $req->validate([
            'new-password' => 'required', 'same:new-password', 'min:3',
            'new-password-confirm' => 'required|same:new-password',
        ]);

        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($req->get('new-password'));
        $user->need_change_password = false;
        $user->save();
        return redirect()->route('user.profile')->with(['status' => 'Пароль был успешно изменен!']);
    }

    public function profile()
    {
        $curUser = Auth::user();
        return view('users.profile', ['user' => $curUser]);
    }

    public function resetYourselfPassword()
    {
        $user = Auth::user();
        $user->need_change_password = true;
        $user->save();
        return redirect()->route('user.profile')->with(['status' => 'Запрошена смена пароля!']);
    }

    public function show($id)
    {
        //dd("show");
        $user = User::find($id);
        $roles = Role::all();

        return view('users.show', ['user' => $user]);
    }

    public function getUsers(Request $req)
    {
        //dd("getUsers");
        if($req->ajax())
        {
            $sortBy = $req->get('sortby');
            $sortDesc = $req->get('sortdesc');
            $query = $req->get('query');
            $query = str_replace(" ", "%", $query);

            $users = User::find(Auth::user()->id)
                        ->getDownUsers()
                        ->select('users.*')
                        ->join('org_units', 'org_units.id', '=', 'users.org_unit_id')
                        ->where('username', 'like', '%'.$query.'%')
                        ->OrWhere('org_units.org_unit_code', 'like', '%'.$query.'%')
                        ->OrWhere('full_name', 'like', '%'.$query.'%')
                        ->orderBy($sortBy, $sortDesc)
                        ->with(['roles', 'orgUnit'])
                        ->paginate(10);

            return view('components.users-tbody', compact('users'))->render();
        }
    }

    public function create()
    {
        //dd("create");
    	$newUser = new User();

        $orgUnit = OrgUnit::find(Auth::user()->org_unit_id);

        $orgUnits = OrgUnit::whereDescendantOrSelf($orgUnit)
                            ->select(['id', 'org_unit_code as text'])
                            ->get();

        $roles = Role::all();

    	return view('users.create',
    				['curUser' => $newUser,
                    'orgUnits' => $orgUnits,
                    'roles' => $roles,
                ]);
    }

    public function store(StoreUserRequest $req)
    {
        $data = $req->all();

        //создаем пользователя
        $user = new User($data);

        $user->password = Hash::make($data['password']);
        $user->blocked = false;
        $user->need_change_password = false;

        if ($user->save())
        {
            //заполняем его роли
            $roles = $req->get('roles');
            foreach ($roles as $role)
            {
                $user->assignRoles($role);
            }

            return redirect()->route('user.show', $user->id);
        }
        else
            return back()->withErrors(['msg' => "Ошибка создания объекта"])->withInput();

    }

    public function edit($id)
    {
        $curUser = User::find($id);

        //получаем опции для селектов
        $orgUnit = OrgUnit::find(Auth::user()->org_unit_id);
        $orgUnits = OrgUnit::whereDescendantOrSelf($orgUnit)
                            ->select(['id', 'org_unit_code as text'])
                            ->get();

        $roles = Role::all();


        return view('users.create',
        [
            'curUser' => $curUser,
            'orgUnits' => $orgUnits,
            'roles' => $roles
        ]);

    }

    public function update(UpdateUserRequest $req, $id)
    {
        $editUser = User::find($id);

        if(empty($editUser))
            return back()->withErrors(['msg' => "Обновляемый объект не найден"])->withInput();

        $data = $req->all();
        $result = $editUser->fill($data);

        if($result->save())
        {
            $curRoles = $result->roles;

            $roles = $req->get('roles');

            //удаляем неотмеченные чекбоксы
            foreach($curRoles as $role)
            {
                if(!in_array($role->slug, $roles))
                    $result->unassignRoles($role->slug);
            }


            //добавляем новые роли
            foreach($roles as $role)
            {
                $result->assignRoles($role);
            }

            return redirect()->route('user.show', $result->id);

        }
        else
        {
            return back()->withErrors(['msg' => "Ошибка обновления записи"])->withInput();
        }

    }

    public function destroy($id)
    {
        $deletingItem = User::find($id);

        if($deletingItem)
        {
            $deletingItem->delete();
            return redirect()->route('user.index')->with(['status' => 'Успешно удален']);
        }
        else
            return redirect()->route('user.index')
                ->withErrors(['msg' => 'Ошибка удаления в UserController::destroy']);
    }


    public function block($id)
    {
        if(Auth::user()->id == $id)
            return back()->withErrors(['msg' => 'Нельзя заблокировать себя']);
        else
        {
            $banUser = User::find($id);

            if($banUser == null)
                return response()->json(['Ошибка' => 'Вы не можете заблокировать этого пользователя!']);

            if(Auth::user()->canSetOrgUnit($banUser->org_unit_id))
            {
                $banUser->blocked = true;
                $banUser->save();

                return redirect()->route('user.show', $banUser->id)
                                            ->with(['status' => 'Успешно заблокирован']);
            }
            else
                return response()->json(['Ошибка' => 'Вы не можете заблокировать этого пользователя!']);
        }
    }

    public function unblock($id)
    {
        if(Auth::user()->id == $id)
            return back()->withErrors(['msg' => 'Нельзя разблокировать себя']);
        else
        {
            $unbanUser = User::find($id);

            if($unbanUser == null)
                return response()->json(['Ошибка' => 'Вы не можете разблокировать этого пользователя!']);

            if(Auth::user()->canSetOrgUnit($unbanUser->org_unit_id))
            {
                $unbanUser->blocked = false;
                $unbanUser->save();

                return redirect()->route('user.show', $unbanUser->id)
                                            ->with(['status' => 'Успешно разблокирован']);
            }
            else
                return response()->json(['Ошибка' => 'Вы не можете разблокировать этого пользователя!']);

        }
    }

    public function resetPassword($id)
    {
        $user = User::find($id);

        if($user == null)
            return response()->json(['Ошибка' => 'Вы не можете сбросить пароль этого пользователя!']);

        if(Auth::user()->canSetOrgUnit($user->org_unit_id))
        {
            $user->need_change_password = true;
            $user->password = Hash::make($user->username);
            $user->save();

            return redirect()->route('user.show', $user->id)->with(['status' => 'Пароль успешно сброшен! Он совпадает с логином пользователя, его необходимо сменить при следующей авторизации пользователя.']);
        }
        else
            return response()->json(['Ошибка' => 'Вы не можете сбросить пароль этого пользователя!']);
    }
}
