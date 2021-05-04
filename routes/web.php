<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\OrgunitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

use App\Http\Controllers\DictsData\InterestRateController;
use App\Http\Controllers\DictsData\LoanTermController;
use App\Http\Controllers\DictsData\MaritalStatusController;
use App\Http\Controllers\DictsData\SeniorityController;

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

Route::group(['middleware' => 'auth'], function() {

	Route::get('/', function () {
	    return view('welcome');
	})->name('welcome');

	Route::group(['middleware' => 'perm:change-curr-orgunit'], function(){

		Route::put('changeorgunit', [OrgUnitController::class, 'changeorgunit'])
															->name('orgunits.change');	
	});

	Route::group(['middleware' => 'perm:view-users'], function(){
		//user routes
		Route::get('/user', [UserController::class, 'index'])->name('user.index');

		Route::get('/users/get-users', [UserController::class, 'getUsers'])->name('user.list');

		Route::group(['middleware' => 'perm:create-user'], function(){
			Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
			Route::post('/user', [UserController::class, 'store'])->name('user.store');
		});

		Route::group(['middleware' => 'perm:delete-user'], function(){
			Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
		});

		Route::group(['middleware' => 'perm:edit-user'], function(){
			Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
			Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
		});

		Route::group(['middleware' => 'perm:manage-users'], function(){
			Route::get('/user/block/{id}', [UserController::class, 'block'])->name('user.block');
			Route::get('/user/unblock/{id}', [UserController::class, 'unblock'])->name('user.unblock');
			Route::get('/user/resetpassword/{id}', [UserController::class, 'resetPassword'])
																->name('user.resetpassword');
		});

		Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
	});

	//Роли пользователя
	Route::group(['middleware' => 'perm:manage-roles'], function(){
		Route::get('/role', [RoleController::class, 'index'])->name('role.index');

		Route::get('/roles/get-roles', [RoleController::class, 'getRoles'])->name('role.list');

		Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
		Route::post('/role', [RoleController::class, 'store'])->name('role.store');
		Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
		Route::get('/role/{id}', [RoleController::class, 'show'])->name('role.show');
		Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
	});


	Route::group(['middleware' => 'perm:manage-perms'], function(){
		Route::get('/perm', [PermissionController::class, 'index'])->name('perm.index');

		Route::get('/perms/get-perms', [PermissionController::class, 'getPerms'])->name('perm.list');
		
		Route::get('/perm/create', [PermissionController::class, 'create'])->name('perm.create');
		Route::get('/perm/edit/{id}', [PermissionController::class, 'edit'])->name('perm.edit');
		Route::post('/perm', [PermissionController::class, 'store'])->name('perm.store');
		Route::delete('/perm/{id}', [PermissionController::class, 'destroy'])->name('perm.destroy');
	});

	Route::group(['middleware' => 'perm:manage-datadicts'], function(){
		//interestRate routes
		Route::get('/interestrate', [InterestRateController::class, 'index'])
										->name('interestrate.index');
		Route::get('/interestrate/create', [InterestRateController::class, 'create'])
										->name('interestrate.create');
		Route::post('/interestrate', [InterestRateController::class, 'store'])
										->name('interestrate.store');
		Route::get('/interestrate/{id}', [InterestRateController::class, 'show'])
										->name('interestrate.show');
		Route::get('/interestrate/{id}/edit', [InterestRateController::class, 'edit'])
										->name('interestrate.edit');
		Route::put('/interestrate/{id}', [InterestRateController::class, 'update'])
										->name('interestrate.update');
		Route::delete('/interestrate/{id}', [InterestRateController::class, 'destroy'])
										->name('interestrate.destroy');

		//LoanTerm routes
		Route::get('/loanterm', [LoanTermController::class, 'index'])
										->name('loanterm.index');
		Route::get('/loanterm/create', [LoanTermController::class, 'create'])
										->name('loanterm.create');
		Route::post('/loanterm', [LoanTermController::class, 'store'])
										->name('loanterm.store');
		Route::get('/loanterm/{id}', [LoanTermController::class, 'show'])
										->name('loanterm.show');
		Route::get('/loanterm/{id}/edit', [LoanTermController::class, 'edit'])
										->name('loanterm.edit');
		Route::put('/loanterm/{id}', [LoanTermController::class, 'update'])
										->name('loanterm.update');
		Route::delete('/loanterm/{id}', [LoanTermController::class, 'destroy'])
										->name('loanterm.destroy');

		//MartialStatus route
		Route::get('/maritalstatus/{id}', [MaritalStatusController::class, 'show'])
										->name('maritalstatus.show');
		Route::get('/maritalstatus/create', [MaritalStatusController::class, 'create'])
										->name('maritalstatus.create');
		Route::post('/maritalstatus', [MaritalStatusController::class, 'store'])
										->name('maritalstatus.store');
		Route::get('/maritalstatus/{id}/edit', [MaritalStatusController::class, 'edit'])
										->name('maritalstatus.edit');
		Route::put('/maritalstatus/{id}', [MaritalStatusController::class, 'update'])
										->name('maritalstatus.update');
		Route::delete('/maritalstatus/{id}', [MaritalStatusController::class, 'destroy'])
										->name('maritalstatus.destroy');

		//Seniority routes
		Route::get('/seniority', [SeniorityController::class, 'index'])
										->name('seniority.index');
		Route::get('/seniority/create', [SeniorityController::class, 'create'])
										->name('seniority.create');
		Route::post('/seniority', [SeniorityController::class, 'store'])
										->name('seniority.store');
		Route::get('/seniority/{id}', [SeniorityController::class, 'show'])
										->name('seniority.show');
		Route::get('/seniority/{id}/edit', [SeniorityController::class, 'edit'])
										->name('seniority.edit');
		Route::put('/seniority/{id}', [SeniorityController::class, 'update'])
										->name('seniority.update');
		Route::delete('/seniority/{id}', [SeniorityController::class, 'destroy'])
										->name('seniority.destroy');
	});




});
require __DIR__.'/auth.php';
