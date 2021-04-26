<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
    	return User::paginate(10);
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
}
