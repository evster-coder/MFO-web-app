<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\OrgUnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\OrgUnitParamController;

use App\Http\Controllers\DictsData\InterestRateController;
use App\Http\Controllers\DictsData\LoanTermController;
use App\Http\Controllers\DictsData\MaritalStatusController;
use App\Http\Controllers\DictsData\SeniorityController;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientFormController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SecurityApprovalController;
use App\Http\Controllers\DirectorApprovalController;


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

//need to change
Route::get('/payment/create/{id}', [PaymentController::class, 'create'])
											->name('payment.create');
Route::get('/payment/{id}', [PaymentController::class, 'show'])
											->name('payment.show');
Route::post('/payment', [PaymentController::class, 'store'])
											->name('payment.store');
Route::delete('/payment/{id}', [PaymentController::class, 'destroy'])
											->name('payment.destroy');

Route::group(['middleware' => 'auth'], function() {
	Route::get('/banned', [UserController::class, 'banned'])
										->name('user.banned');
});


Route::group(['middleware' => ['auth', 'notBanned']], function() {

	//стартовый рут после авторизации для разных ролей пользователей
	Route::get('/', function () {
	    if(Auth::user()->hasRole('admin'))
	    	return redirect()->route('user.index');
	    elseif(Auth::user()->hasRole('cashier'))
	    	return redirect()->route('clientForm.index');
	    elseif(Auth::user()->hasRole('security'))
	    	return redirect()->route('securityApproval.tasks');
	    elseif(Auth::user()->hasRole('director'))
	    	return redirect()->route('directorApproval.tasks');
	    else return redirect()->route('user.profile');
	})->name('welcome');


	Route::group(['middleware' => 'perm:view-clientforms'], function(){
		Route::get('/client-forms/export-excel', [ClientFormController::class, 'export'])->name('clientForm.export');

		Route::group(['middleware' => 'perm:create-client-form'], function(){
			Route::get('/client-form/create', [ClientFormController::class, 'create'])
										->name('clientForm.create');
			Route::post('/client-form', [ClientFormController::class, 'store'])
										->name('clientForm.store');
		});

		Route::group(['middleware' => 'perm:edit-clientform'], function(){
			Route::get('/client-form/{id}/edit', [ClientFormController::class, 'edit'])
										->name('clientForm.edit');
			Route::put('/client-form/{id}', [ClientFormController::class, 'update'])
										->name('clientForm.update');
		});

		Route::group(['middleware' => 'perm:delete-clientform'], function(){
			Route::delete('/client-form/{id}', [ClientFormController::class, 'destroy'])
										->name('clientForm.destroy');
		});

		Route::get('/client-form', [ClientFormController::class, 'index'])
										->name('clientForm.index');
		Route::get('/client-form/{id}', [ClientFormController::class, 'show'])
										->name('clientForm.show');
		Route::get('/client-forms/get-client-forms', [ClientFormController::class, 'getForms'])
										->name('clientForm.list');
	});

	Route::get('/client-form-data/{id}', [ClientFormController::class, 'getForm'])
									->name('client.get-data');

	Route::get('/client-client-forms/{id}', [ClientController::class, 'getClientForms'])
									->name('client.get-clientForms');

	Route::get('/client-loans/{id}', [ClientController::class, 'getLoans'])
									->name('client.get-loans');

	Route::group(['middleware' => 'perm:view-security-approvals'], function(){
		Route::get('/sec-approval', [SecurityApprovalController::class, 'index'])
									->name('securityApproval.index');
		Route::get('/sec-approvals/export-excel', [SecurityApprovalController::class, 'export'])
									->name('securityApproval.export');
		Route::get('/sec-approvals/get-apprs', [SecurityApprovalController::class, 'getApprs'])
									->name('securityApproval.list');
		Route::get('/sec-approval/{id}', [SecurityApprovalController::class, 'show'])
									->name('securityApproval.show');
		Route::group(['middleware' => 'perm:manage-security-approval'], function() {
			Route::get('/sec-approval-new', [SecurityApprovalController::class, 'taskList'])
									->name('securityApproval.tasks');
			Route::get('/sec-approval/create/{id}', [SecurityApprovalController::class, 'create'])
									->name('securityApproval.create');
			Route::post('/sec-approval/create-accept', [SecurityApprovalController::class, 'accept'])
									->name('securityApproval.accept');
			Route::post('/sec-approval/create-reject', [SecurityApprovalController::class, 'reject'])
									->name('securityApproval.reject');
		});

		Route::group(['middleware' => 'perm:delete-security-approval'], function(){
			Route::delete('/sec-approval/{id}', [SecurityApprovalController::class, 'destroy'])
									->name('securityApproval.destroy');
		});
	});


	Route::group(['middleware' => 'perm:view-director-approvals'], function() {
		Route::get('/director-approval', [DirectorApprovalController::class, 'index'])
									->name('directorApproval.index');
		Route::get('/director-approvals/export-excel', [DirectorApprovalController::class, 'export'])
									->name('directorApproval.export');
		Route::get('/director-approvals/get-apprs', [DirectorApprovalController::class, 'getApprs'])
									->name('directorApproval.list');
		Route::get('/director-approval/{id}', [DirectorApprovalController::class, 'show'])
									->name('directorApproval.show');
		Route::group(['middleware' => 'perm:manage-director-approval'], function() {
			Route::get('/director-approval-new', [DirectorApprovalController::class, 'taskList'])
									->name('directorApproval.tasks');
			Route::get('/director-approval/create/{id}', [DirectorApprovalController::class, 'create'])
									->name('directorApproval.create');
			Route::post('/director-approval/create-accept', [DirectorApprovalController::class, 'accept'])
									->name('directorApproval.accept');
			Route::post('/director-approval/create-reject', [DirectorApprovalController::class, 'reject'])
									->name('directorApproval.reject');
		});
		Route::group(['middleware' => 'perm:delete-director-approval'], function(){
			Route::delete('/director-approval/{id}', [DirectorApprovalController::class, 'destroy'])
									->name('directorApproval.destroy');
		});
	});


	Route::group(['middleware' => 'perm:view-loans'], function(){
		Route::get('/loan', [LoanController::class, 'index'])
													->name('loan.index');
		Route::get('/loans/get-loans', [LoanController::class, 'getLoans'])
													->name('loan.list');
		Route::get('/loans/export-excel', [LoanController::class, 'export'])->name('loan.export');
		Route::get('/loan/{id}', [LoanController::class, 'show'])
													->name('loan.show');

		Route::get('/loans/close/{id}', [LoanController::class, 'closeLoan'])
													->name('loan.close');

		Route::group(['middleware' => 'perm:create-loan'], function(){
			Route::post('/loan', [LoanController::class, 'store'])
													->name('loan.store');
		});
		Route::group(['middleware' => 'perm:delete-loan'], function(){
			Route::delete('/loan/{id}', [LoanController::class, 'destroy'])
													->name('loan.destroy');
		});
	});


	Route::group(['middleware' => 'perm:view-clients'], function(){
		Route::get('/client', [ClientController::class, 'index'])
											->name('client.index');

		Route::get('/clients/export-excel', [ClientController::class, 'export'])->name('client.export');

		Route::group(['middleware' => 'perm:create-client'], function(){
			Route::get('/client/create', [ClientController::class, 'create'])
											->name('client.create');
			Route::post('/client', [ClientController::class, 'store'])
											->name('client.store');
		});
		Route::group(['middleware' => 'perm:edit-client'], function(){
			Route::get('/client/{id}/edit', [ClientController::class, 'edit'])
											->name('client.edit');
			Route::put('/client/{id}', [ClientController::class, 'update'])
											->name('client.update');
		});
		Route::get('/client/{id}', [ClientController::class, 'show'])
											->name('client.show');
		Route::get('/clients/get-clients', [ClientController::class, 'getClients'])
											->name('client.list');
		Route::post('clients/get-same-clients', [ClientController::class, 'sameClients'])
											->name('client.sameclients');

		Route::group(['middleware' => 'perm:delete-client'], function(){
			Route::delete('/client/{id}', [ClientController::class, 'destroy'])
											->name('client.destroy');
		});
	});


	Route::get('/changepassword', [UserController::class, 'changePassword'])
													->name('auth.change-password');
	Route::put('/changepassword', [UserController::class, 'updatePassword'])
																->name('auth.update-password');

	Route::group(['middleware' => 'changepass'], function() {

		Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');

		Route::get('resetyourpassword', [UserController::class, 'resetYourselfPassword'])
																->name('user.resetyourpassword');


	Route::get('/maritalstatuses/get-statuses', [MaritalStatusController::class, 'getStatuses'])
										->name('maritalstatus.list');
	Route::get('/senioritis/get-senioritis', [SeniorityController::class, 'getSenioritis'])
										->name('seniority.list');
	Route::get('/loanterms/get-loanterms', [LoanTermController::class, 'getTerms'])
										->name('loanterm.list');
	Route::get('/loanterms/axios-loanterms', [LoanTermController::class, 'axiosTerms'])
										->name('loanterm.axiosList');
	Route::get('/interestrates/get-interestrates', [InterestRateController::class, 'getRates'])
										->name('interestrate.list');
	Route::get('/interestrates/axios-interestrates', [InterestRateController::class, 'axiosRates'])
										->name('interestrate.axiosList');


	Route::group(['middleware' => 'perm:view-users'], function(){
		//user routes
		Route::get('/user', [UserController::class, 'index'])->name('user.index');

		Route::get('/users/get-users', [UserController::class, 'getUsers'])->name('user.list');

		Route::get('/users/export-excel', [UserController::class, 'export'])->name('user.export');

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

		Route::get('/perm/edit/{id}', [PermissionController::class, 'edit'])->name('perm.edit');
		Route::post('/perm', [PermissionController::class, 'store'])->name('perm.store');
		Route::delete('/perm/{id}', [PermissionController::class, 'destroy'])->name('perm.destroy');
	});

	Route::group(['middleware' => 'perm:manage-datadicts'], function(){
		//interest_rate routes
		Route::get('/interestrate', [InterestRateController::class, 'index'])
										->name('interestrate.index');
		Route::post('/interestrate', [InterestRateController::class, 'store'])
										->name('interestrate.store');
		Route::get('/interestrate/{id}/edit', [InterestRateController::class, 'edit'])
										->name('interestrate.edit');
		Route::delete('/interestrate/{id}', [InterestRateController::class, 'destroy'])
										->name('interestrate.destroy');

		//LoanTerm routes
		Route::get('/loanterm', [LoanTermController::class, 'index'])
										->name('loanterm.index');
		Route::post('/loanterm', [LoanTermController::class, 'store'])
										->name('loanterm.store');
		Route::get('/loanterm/{id}/edit', [LoanTermController::class, 'edit'])
										->name('loanterm.edit');
		Route::delete('/loanterm/{id}', [LoanTermController::class, 'destroy'])
										->name('loanterm.destroy');


		//MartialStatus route
		Route::get('/maritalstatus', [MaritalStatusController::class, 'index'])
										->name('maritalstatus.index');
		Route::post('/maritalstatus', [MaritalStatusController::class, 'store'])
										->name('maritalstatus.store');
		Route::get('/maritalstatus/{id}/edit', [MaritalStatusController::class, 'edit'])
										->name('maritalstatus.edit');
		Route::delete('/maritalstatus/{id}', [MaritalStatusController::class, 'destroy'])
										->name('maritalstatus.destroy');

		//Seniority routes
		Route::get('/seniority', [SeniorityController::class, 'index'])
										->name('seniority.index');
		Route::post('/seniority', [SeniorityController::class, 'store'])
										->name('seniority.store');
		Route::get('/seniority/{id}/edit', [SeniorityController::class, 'edit'])
										->name('seniority.edit');
		Route::delete('/seniority/{id}', [SeniorityController::class, 'destroy'])
										->name('seniority.destroy');
	});


	Route::group(['middleware' => 'perm:change-curr-orgunit'], function(){

		Route::put('changeorgunit', [OrgUnitController::class, 'changeorgunit'])
															->name('orgunit.change');
	});

	Route::group(['middleware' => 'perm:view-orgunits'], function() {
		Route::get('/orgunit', [OrgUnitController::class, 'index'])
																->name('orgunit.index');
		Route::get('/orgunit/{id}', [OrgUnitController::class, 'show'])
																->name('orgunit.show');

		Route::group(['middleware' => 'perm:create-orgunit'], function(){
			Route::get('/orgunit/create/{parent?}', [OrgUnitController::class, 'create'])
																->name('orgunit.create');
			Route::post('/orgunit', [OrgUnitController::class, 'store'])
																->name('orgunit.store');
		});

		Route::group(['middleware' => 'perm:edit-orgunit'], function(){
			Route::get('/orgunit/{id}/edit', [OrgUnitController::class, 'edit'])
																->name('orgunit.edit');
			Route::put('/orgunit/{id}', [OrgUnitController::class, 'update'])
																->name('orgunit.update');
		});

		Route::group(['middleware' => 'perm:delete-orgunit'], function(){
			Route::delete('/orgunit/{id?}', [OrgUnitController::class, 'destroy'])
																->name('orgunit.destroy');
		});
	});

	//orgunitparams routes
	Route::group(['middleware' => 'perm:view-orgunit-params'], function() {
		Route::get('/orgunitparam', [OrgUnitParamController::class, 'index'])
																->name('param.index');
		Route::get('/orgunitparams/get-orgunitparams', [OrgUnitParamController::class, 'getParams'])
																->name('param.list');

		Route::group(['middleware' => 'perm:create-orgunit-param'], function(){
			Route::post('/orgunitparam', [OrgUnitParamController::class, 'store'])
																->name('param.store');
		});

		Route::group(['middleware' => 'perm:edit-orgunit-param'], function(){
			Route::get('/orgunitparam/{id}/edit', [OrgUnitParamController::class, 'edit'])
																->name('param.edit');
		});
		Route::group(['middleware' => 'perm:delete-orgunit-param'], function(){
			Route::delete('/orgunitparam/{id}', [OrgUnitParamController::class, 'destroy'])
																->name('param.destroy');
		});
	});
});
});
require __DIR__.'/auth.php';
