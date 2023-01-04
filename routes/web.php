<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'prevent-back-history'], function(){
	Route::get('/', function () {
		return view('auth.login');
	});

	Auth::routes();

	Route::group(['prefix' => 'verify'], function () {
		Route::get('/', 		[App\Http\Controllers\Auth\TwoFactorController::class, 'index'])->name('verify.index');
		Route::post('/store', 	[App\Http\Controllers\Auth\TwoFactorController::class, 'store'])->name('verify.store');
		Route::get('/resend', 	[App\Http\Controllers\Auth\TwoFactorController::class, 'resend'])->name('verify.resend');
	});

	Route::get('/home', 			[App\Http\Controllers\HomeController::class, 'index'])->name('home');
	Route::post('/home', 			[App\Http\Controllers\HomeController::class, 'index']);
	Route::post('/home/counterdata',[App\Http\Controllers\HomeController::class, 'counterData']);
	Route::get('/account', 			[App\Http\Controllers\HomeController::class, 'account']);
	Route::post('/update-password', [App\Http\Controllers\HomeController::class, 'update_password']);
	Route::get('logout', 			[App\Http\Controllers\HomeController::class, 'logout']);
	Route::get('access-login/{id}', [App\Http\Controllers\HomeController::class, 'accessLogin']);

	Route::get('/home/get_low_inventory_alert_data', [App\Http\Controllers\HomeController::class, 'get_low_inventory_alert_data']);
	Route::get('/home/disable_notification_alert', 	 [App\Http\Controllers\HomeController::class, 'disable_notification_alert']);

	//Password Encoding
	Route::get('password-encode', 	[App\Http\Controllers\Auth\LoginController::class, 'passwordEncode']);
	Route::post('password-update', 	[App\Http\Controllers\Auth\LoginController::class, 'passwordUpdate']);

	// Admin Routes
	Route::group(['prefix' => 'admin'], function () {
		// Account Routes
		Route::group(['prefix' => 'account'], function () {
			Route::get('/', 				[App\Http\Controllers\Admin\AccountController::class, 'index']);
			Route::get('list', 				[App\Http\Controllers\Admin\AccountController::class, 'list']);
			Route::get('create', 			[App\Http\Controllers\Admin\AccountController::class, 'create']);
			Route::post('save', 			[App\Http\Controllers\Admin\AccountController::class, 'store']);
			Route::get('edit/{id}', 		[App\Http\Controllers\Admin\AccountController::class, 'edit']);
			Route::get('view/{id}', 		[App\Http\Controllers\Admin\AccountController::class, 'view']);
			Route::post('update', 			[App\Http\Controllers\Admin\AccountController::class, 'update']);
			Route::post('update-status', 	[App\Http\Controllers\Admin\AccountController::class, 'updateStatus']);
		});

		Route::get('global-setting', 	[App\Http\Controllers\Admin\AccountController::class, 'showGlobalSetting']);
		Route::post('global-setting', 	[App\Http\Controllers\Admin\AccountController::class, 'updateGlobalSetting']);

		Route::group(['prefix' => 'machine-model'], function () {
			Route::get('/', 				[App\Http\Controllers\Admin\MachineModelController::class, 'index']);
			Route::get('list', 				[App\Http\Controllers\Admin\MachineModelController::class, 'list']);
			Route::get('create', 			[App\Http\Controllers\Admin\MachineModelController::class, 'create']);
			Route::post('save', 			[App\Http\Controllers\Admin\MachineModelController::class, 'store']);
			Route::get('edit/{id}', 		[App\Http\Controllers\Admin\MachineModelController::class, 'edit']);
			Route::post('update', 			[App\Http\Controllers\Admin\MachineModelController::class, 'update']);
			Route::post('update-status', 	[App\Http\Controllers\Admin\MachineModelController::class, 'updateStatus']);
		});

		Route::group(['prefix' => 'machine'], function () {
			Route::get('list/{id}', 		[App\Http\Controllers\Admin\MachineController::class, 'list']);
			Route::get('create/{id}', 		[App\Http\Controllers\Admin\MachineController::class, 'create']);
			Route::post('save', 			[App\Http\Controllers\Admin\MachineController::class, 'store']);
			Route::get('edit/{id}', 		[App\Http\Controllers\Admin\MachineController::class, 'edit']);
			Route::post('update', 			[App\Http\Controllers\Admin\MachineController::class, 'update']);
			Route::post('update-status', 	[App\Http\Controllers\Admin\MachineController::class, 'updateStatus']);
			Route::get('{id}', 				[App\Http\Controllers\Admin\MachineController::class, 'index']);
		});
	});

	// Parent OR Sub OR Standard Routes
	Route::group(['prefix' => 'app', 'middleware' => 'twoFactor'], function () {

		Route::group(['prefix' => 'common'], function () {
			Route::post('/fileUpload', 			[App\Http\Controllers\Accounts\CommonController::class, 'fileUpload']);
			Route::delete('/deleteUpload/{id}', [App\Http\Controllers\Accounts\CommonController::class, 'deleteUpload']);
		});

		Route::get('/products', 			[App\Http\Controllers\Accounts\ProductController::class, 'index']);

		Route::group(['prefix' => 'product'], function () {

			Route::get('/list', 		[App\Http\Controllers\Accounts\ProductController::class, 'list']);
			Route::get('/add', 			[App\Http\Controllers\Accounts\ProductController::class, 'create']);
			Route::post('/save', 		[App\Http\Controllers\Accounts\ProductController::class, 'store']);
			Route::get('/edit/{id}', 	[App\Http\Controllers\Accounts\ProductController::class, 'edit']);
			Route::post('/update', 		[App\Http\Controllers\Accounts\ProductController::class, 'update']);
			Route::post('/delete', 		[App\Http\Controllers\Accounts\ProductController::class, 'delete']);

			Route::get('/export', 		[App\Http\Controllers\Accounts\ProductController::class, 'export']);
			Route::post('/checkname', 	[App\Http\Controllers\Accounts\ProductController::class, 'checkName']);
			Route::post('/retire', 		[App\Http\Controllers\Accounts\ProductController::class, 'retire']);
			Route::post('/deleteImage', [App\Http\Controllers\Accounts\ProductController::class, 'deleteImage']);
			Route::post('/checkIdentifier', 	[App\Http\Controllers\Accounts\ProductController::class, 'checkIdentifier']);
			Route::post('/deleteVariant', 	[App\Http\Controllers\Accounts\ProductController::class, 'deleteVariant']);
		});

		Route::group(['prefix' => 'machines-inventory'], function () {
			Route::get('/', 			[App\Http\Controllers\Accounts\MachineController::class, 'indexInventory']);
			Route::get('/list', 		[App\Http\Controllers\Accounts\MachineController::class, 'listInventory']);
			Route::get('/manage-mapping/{id}', 	[App\Http\Controllers\Accounts\MachineController::class, 'viewInventory']);
			Route::get('/edit/{id}', 	[App\Http\Controllers\Accounts\MachineController::class, 'editInventory']);
			Route::post('/update', 		[App\Http\Controllers\Accounts\MachineController::class, 'updateInventory']);

			Route::post('/getVariant', 	[App\Http\Controllers\Accounts\MachineController::class, 'getVariant']);
			Route::post('/getVarName', 	[App\Http\Controllers\Accounts\MachineController::class, 'getVarName']);
			Route::post('/getVarPrice', [App\Http\Controllers\Accounts\MachineController::class, 'getVarPrice']);
		});

		Route::group(['prefix' => 'machines'], function () {
			Route::get('/', 			[App\Http\Controllers\Accounts\MachineController::class, 'index']);
			Route::get('/list', 		[App\Http\Controllers\Accounts\MachineController::class, 'list']);
			Route::get('/edit/{id}', 	[App\Http\Controllers\Accounts\MachineController::class, 'edit']);
			Route::post('/update', 		[App\Http\Controllers\Accounts\MachineController::class, 'update']);

			Route::get('/list/{id}', 	[App\Http\Controllers\Accounts\MachineController::class, 'listByID']);
			Route::get('/add/{id}',		[App\Http\Controllers\Accounts\MachineController::class, 'create']);
			Route::post('/save', 		[App\Http\Controllers\Accounts\MachineController::class, 'store']);
		});

		Route::group(['prefix' => 'setting'], function () {
			Route::get('/', 			[App\Http\Controllers\Accounts\SettingController::class, 'index']);
			Route::post('/update', 		[App\Http\Controllers\Accounts\SettingController::class, 'update']);

			Route::post('/sendOTP', 	[App\Http\Controllers\Accounts\SettingController::class, 'sendOTP']);
			Route::post('/checkOTP', 	[App\Http\Controllers\Accounts\SettingController::class, 'checkOTP']);

			Route::get('/receipt', 		[App\Http\Controllers\Accounts\SettingController::class, 'receipt']);
			Route::post('/updateReceipt',[App\Http\Controllers\Accounts\SettingController::class, 'updateReceipt']);
		});

		Route::group(['prefix' => 'customer'], function () {
			Route::get('/', 			[App\Http\Controllers\Accounts\CustomersController::class, 'index']);
			Route::get('/list', 		[App\Http\Controllers\Accounts\CustomersController::class, 'list']);

			Route::get('/export', 		[App\Http\Controllers\Accounts\CustomersController::class, 'export']);
		});

        Route::group(['prefix' => 'traffic-analytics'], function () {
            Route::get('/', 			[App\Http\Controllers\Accounts\TrafficController::class, 'index']);
            Route::get('/draw', 		[App\Http\Controllers\Accounts\TrafficController::class, 'getData']);
        });

		Route::group(['prefix' => 'promotion'], function () {
			Route::get('/', 			[App\Http\Controllers\Accounts\PromotionController::class, 'index']);
			Route::get('/list', 		[App\Http\Controllers\Accounts\PromotionController::class, 'list']);
			Route::get('create', 		[App\Http\Controllers\Accounts\PromotionController::class, 'create']);
			Route::post('save', 		[App\Http\Controllers\Accounts\PromotionController::class, 'store']);
			Route::get('/edit/{id}', 	[App\Http\Controllers\Accounts\PromotionController::class, 'edit']);
			Route::post('/update', 		[App\Http\Controllers\Accounts\PromotionController::class, 'update']);
			Route::post('update-status', [App\Http\Controllers\Accounts\PromotionController::class, 'updateStatus']);
		});

		Route::group(['prefix' => 'accounts'], function () {
			Route::get('/', 			[App\Http\Controllers\Accounts\SubAccountController::class, 'index']);
			Route::get('/list', 		[App\Http\Controllers\Accounts\SubAccountController::class, 'list']);
			Route::get('/add', 			[App\Http\Controllers\Accounts\SubAccountController::class, 'create']);
			Route::post('/save', 		[App\Http\Controllers\Accounts\SubAccountController::class, 'store']);
		});

		Route::group(['prefix' => 'sales'], function () {

			Route::get('/', 			[App\Http\Controllers\Accounts\SalesController::class, 'index']);
			Route::get('/list', 		[App\Http\Controllers\Accounts\SalesController::class, 'list']);
			Route::post('/export', 		[App\Http\Controllers\Accounts\SalesController::class, 'export']);
			Route::post('/getSalesTotal', 		[App\Http\Controllers\Accounts\SalesController::class, 'getSalesTotal']);
			Route::post('/getSalesChart', 		[App\Http\Controllers\Accounts\SalesController::class, 'getSaleChartData']);
			Route::post('/getProductSalesChart', [App\Http\Controllers\Accounts\SalesController::class, 'getProductSaleData']);
			Route::post('/getCustomerSalesData', [App\Http\Controllers\Accounts\SalesController::class, 'getCustomerSalesData']);
			Route::get('/analytics', 		[App\Http\Controllers\Accounts\SalesController::class, 'analytics']);
		});

		Route::group(['prefix' => 'visitors'], function () {
			Route::get('/', 			[App\Http\Controllers\Accounts\VisitorController::class, 'index']);
			Route::get('/list', 		[App\Http\Controllers\Accounts\VisitorController::class, 'list']);
			Route::post('/export', 		[App\Http\Controllers\Accounts\VisitorController::class, 'export']);
			// Route::post('/getSalesTotal', 		[App\Http\Controllers\Accounts\SalesController::class, 'getSalesTotal']);
			// Route::post('/getSalesChart', 		[App\Http\Controllers\Accounts\SalesController::class, 'getSaleChartData']);
			Route::post('/getAnalyticChartData', [App\Http\Controllers\Accounts\VisitorController::class, 'getAnalyticChartData']);
			Route::get('/analytics', 		[App\Http\Controllers\Accounts\VisitorController::class, 'analytics']);
		});

		Route::group(['prefix' => 'advertisement'], function () {
			Route::get('/', 			[App\Http\Controllers\Accounts\AdvertisementController::class, 'index']);
			Route::get('/list', 		[App\Http\Controllers\Accounts\AdvertisementController::class, 'list']);
			Route::get('/add', 			[App\Http\Controllers\Accounts\AdvertisementController::class, 'create']);
			Route::post('/save', 		[App\Http\Controllers\Accounts\AdvertisementController::class, 'store']);
			Route::get('/edit/{id}', 	[App\Http\Controllers\Accounts\AdvertisementController::class, 'edit']);
			Route::post('/update', 		[App\Http\Controllers\Accounts\AdvertisementController::class, 'update']);
			Route::post('/delete', 		[App\Http\Controllers\Accounts\AdvertisementController::class, 'delete']);

			Route::post('/checkname', 	[App\Http\Controllers\Accounts\AdvertisementController::class, 'checkName']);
			Route::post('/deleteImage', [App\Http\Controllers\Accounts\AdvertisementController::class, 'deleteImage']);
		});

		Route::group(['prefix' => 'ajax'], function () {
			Route::post('/getAccountMachine', 	[App\Http\Controllers\Accounts\AjaxController::class, 'getAccountMachines']);
		});

		Route::group(['prefix' => 'content'], function () {
			Route::get('/', 			[App\Http\Controllers\Accounts\ContentController::class, 'index']);
			Route::post('/save', 		[App\Http\Controllers\Accounts\ContentController::class, 'store']);
		});

		Route::group(['prefix' => 'account-status'], function () {
			Route::get('/', 			[App\Http\Controllers\Accounts\SubscriptionController::class, 'index']);
			Route::post('/save', 		[App\Http\Controllers\Accounts\SubscriptionController::class, 'store']);
			Route::get('/edit', 		[App\Http\Controllers\Accounts\SubscriptionController::class, 'edit']);
		});
	});
});
