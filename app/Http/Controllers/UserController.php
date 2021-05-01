<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\OrgUnit;

use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index()
    {
        $users = User::find(Auth::user()->id)->getDownUsers()
                            ->orderBy('username')
                            ->paginate(10);
        return view('users.index', ['users' => $users]);
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
