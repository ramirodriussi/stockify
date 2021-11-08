<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;

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

	// General  -------

	Route::get('user', [UserController::class, 'show']);

	// Profile --------

	Route::get('user/profile', [ProfileController::class, 'show']);

	Route::post('profile/update', [ProfileController::class, 'update']);

	Route::post('profile/password/update', [ProfileController::class, 'passwordUpdate']);

	Route::post('user/image/store', [ProfileController::class, 'updateLogo']);

	// Stores ----------

	Route::apiResources([
		'stores' => StoreController::class,
		'products' => ProductController::class,
	]);


});
