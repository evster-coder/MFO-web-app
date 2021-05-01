<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\OrgunitController;
use App\Http\Controllers\UserController;

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
		Route::get('/users/get-users', [UserController::class, 'getUsers'])->name('user.list');

		Route::get('/user', [UserController::class, 'index'])->name('user.index');
		Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
		Route::post('/user', [UserController::class, 'store'])->name('user.store');
		Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
		Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
		Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
		Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
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
