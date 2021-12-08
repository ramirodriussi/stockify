<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ImageController;

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

// test email template

// Route::get('/email', [SaleController::class, 'salesOfTheDay']);

// Route::get('/demo', function () {
//     return new App\Mail\DailySalesEmail();
// });

Route::get('imagen/{imagePath}', [ImageController::class, 'getImage'])->name('getImage');

Route::get('imagen/{subfolder}/{imagePath}', [ImageController::class, 'getImageSubdirectory']);

Route::get('/', function () {
    return view('welcome');
});
