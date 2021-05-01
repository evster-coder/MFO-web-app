<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\OrgUnit;

use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index(Request $req)
    {
        $users = User::find(Auth::user()->id)->getDownUsers()
                            ->with('roles')
                            ->with('orgUnit')
                            ->orderBy('username')
                            ->paginate(4);
        //dd($users);

        return view('users.index', ['users' => $users]);
    }

    public function getUsers(Request $req)
    {
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
                    ->paginate(4);
        //dd($users);
        return view('components.users-tbody', compact('users'))->render();
    }
    }

    public function create()
    {
    	$newUser = new User();

    	return view('users.create', 
    				['curUser' => $newUser]);
    }

    public function store(Request $req)
    {
    	$data = $req->input();
    	dd($data);
    }

    public function destroy($user)
    {
        $deletingItem = User::find($user);

        if($deletingItem)
        {
            $deletingItem->delete();
            return redirect()->route('user.index');
        }
        else
            return redirect()->route('user.index')
                ->withErrors(['msg' => 'Ошибка удаления в UserController::destroy']);
    }
}
