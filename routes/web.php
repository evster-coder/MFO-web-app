<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\OrgunitController;

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

	Route::put('changeorgunit', [OrgUnitController::class, 'changeorgunit'])
														->name('orgunits.change');

});
require __DIR__.'/auth.php';
