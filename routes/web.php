<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController as AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminAuthController::class, 'showDashboard'])->name('admin.dashboard');
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login.form');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});


