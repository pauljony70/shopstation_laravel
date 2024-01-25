<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\Basic\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\Basic\CategoryController as AdminCategoryController;

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
Route::group(['prefix' => 'admin', 'middleware' => 'web'], function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login.form');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
})->withoutMiddleware(['admin.check']);

Route::group(['prefix' => 'admin', 'middleware' => ['admin.check']], function () {
    // These routes will have the 'admin.check' middleware applied
    Route::get('/', [AdminAuthController::class, 'showDashboard'])->name('admin.dashboard');

    Route::get('brands', [AdminBrandController::class, 'index'])->name('admin.brands.index');
    Route::post('brands', [AdminBrandController::class, 'store'])->name('admin.brands.store');
    Route::get('brands/edit/{id}', [AdminBrandController::class, 'edit'])->name('admin.brands.edit');
    Route::put('brands/update/{id}', [AdminBrandController::class, 'update'])->name('admin.brands.update');
    Route::delete('brands/delete/{id}', [AdminBrandController::class, 'destroy'])->name('admin.brands.destroy');
    Route::post('unique-brand-name', [AdminBrandController::class, 'uniqueBrandNmae'])->name('admin.brands.unique-name');

    Route::get('categories/{catSlug?}', [AdminCategoryController::class, 'index'])->name('admin.category.index');
    Route::post('category/store', [AdminCategoryController::class, 'store'])->name('admin.category.store');
    Route::post('unique-category-name', [AdminCategoryController::class, 'uniqueCategoryNmae'])->name('admin.categories.unique-name');
});
