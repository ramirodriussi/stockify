<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;

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

Route::middleware('auth:sanctum')->group(function() {

	Route::get('user/profile', [ProfileController::class, 'show']);

	Route::post('profile/update', [ProfileController::class, 'update']);

	Route::post('profile/password/update', [ProfileController::class, 'passwordUpdate']);

	Route::post('user/image/store', [ProfileController::class, 'updateLogo']);

	Route::get('products/code/{code}', [ProductController::class, 'searchByCode']);

	Route::get('products/export', [ProductController::class, 'export']);

	Route::post('products/import', [ProductController::class, 'import']);

	Route::get('products/import/check', [ProductController::class, 'checkIfUploaded']);

	Route::get('sale/day', [SaleController::class, 'salesOfTheDay']);
	
	Route::apiResources([
		'users' => UserController::class,
		'roles' => RoleController::class,
		'stores' => StoreController::class,
		'products' => ProductController::class,
		'sales' => SaleController::class,
		'dashboard' => DashboardController::class,
	]);


});
