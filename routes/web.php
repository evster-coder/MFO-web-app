<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\OrgunitController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/aboba', function(Request $request){
	dd($request->session()->all());
	return view('welcome');
});

Route::group(['middleware' => 'auth'], function() {

	Route::get('/', function () {
	    return view('welcome');
	})->name('welcome');

	Route::group(['middleware' => 'perm:change-curr-orgunit'], function(){

		Route::put('changeorgunit', [OrgUnitController::class, 'changeorgunit'])
															->name('orgunits.change');	
	});

	Route::group(['middleware' => 'perm:manage-users'], function(){
		//user routes
		Route::get('/user', [UserController::class, 'index'])->name('user.index');
		Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
		Route::post('/user', [UserController::class, 'store'])->name('user.store');
		Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
		Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
		Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
		Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
	});




});
require __DIR__.'/auth.php';
