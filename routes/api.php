<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DictsData\MaritalStatusController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\OrgUnitController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:web')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth'], function() {

	//Route::get('/orgunits-down', [OrgUnitController::class, 'getDownOrgUnits'])
	//							->name('orgunit.showdown');



	Route::get('/maritalstatus', [MaritalStatusController::class, 'index'])
								->name('maritalstatus.index');

});

