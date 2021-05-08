<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\OrgUnit;
use App\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


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
                        ->join('orgunits', 'orgunits.id', '=', 'users.orgunit_id')
                        ->where('username', 'like', '%'.$query.'%')
                        ->OrWhere('orgunits.orgUnitCode', 'like', '%'.$query.'%')
                        ->OrWhere('FIO', 'like', '%'.$query.'%')
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

        $orgunit = OrgUnit::find(Auth::user()->orgunit_id);

        $orgunits = OrgUnit::whereDescendantOrSelf($orgunit)
                            ->select('id', 'orgUnitCode as text')
                            ->get();

        $roles = Role::all();

    	return view('users.create', 
    				['curUser' => $newUser,
                    'orgUnits' => $orgunits,
                    'roles' => $roles,
                ]);
    }

    public function store(StoreUserRequest $req)
    {
        //dd("store");
        $data = $req->all();

        //создаем пользователя
        $user = new User($data);

        $user->password = Hash::make($data['password']);
        $user->blocked = false;
        $user->needChangePassword = false;

        $user->save();

        if ($user)
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
        //dd("edit");
        $curUser = User::find($id);


        //получаем опции для селектов
        $orgunit = OrgUnit::find(Auth::user()->orgunit_id);
        $orgunits = OrgUnit::whereDescendantOrSelf($orgunit)
                            ->select('id', 'orgUnitCode as text')
                            ->get();

        $roles = Role::all();


        return view('users.create',
        [
            'curUser' => $curUser,
            'orgUnits' => $orgunits,
            'roles' => $roles
        ]);

    }

    public function update(UpdateUserRequest $req, $id)
    {
        //dd("update");
        
        $editUser = User::find($id);

        if(empty($editUser))
            return back()->withErrors(['msg' => "Обновляемый объект не найден"])->withInput();

        $data = $req->all();
        $result = $editUser->fill($data);

        $result->save();

        if($result)
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
        //dd("destroy");
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
        //dd("block");
        if(Auth::user()->id == $id)
            return back()->withErrors(['msg' => 'Нельзя заблокировать себя']);
        else
        {
            $banUser = User::find($id);

            if($banUser == null)
                return response()->json(['Ошибка' => 'Вы не можете заблокировать этого пользователя!']);

            if(Auth::user()->canSetOrgUnit($banUser->orgunit_id))
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
        //dd("unblock");

        if(Auth::user()->id == $id)
            return back()->withErrors(['msg' => 'Нельзя разблокировать себя']);
        else
        {
            $unbanUser = User::find($id);

            if($unbanUser == null)
                return response()->json(['Ошибка' => 'Вы не можете разблокировать этого пользователя!']);

            if(Auth::user()->canSetOrgUnit($unbanUser->orgunit_id))
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
        //dd("resetPassword");
        
        $user = User::find($id);

        if($user == null)
            return response()->json(['Ошибка' => 'Вы не можете сбросить пароль этого пользователя!']);

        if(Auth::user()->canSetOrgUnit($user->orgunit_id))
        {
            $user->needChangePassword = true;
            $user->save();


            return redirect()->route('user.show', $user->id)->with(['status' => 'Пароль успешно сброшен! Он совпадает с логином пользователя, его необходимо сменить при следующей авторизации пользователя.']);
        }
        else
            return response()->json(['Ошибка' => 'Вы не можете сбросить пароль этого пользователя!']);
    }
}
