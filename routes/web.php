<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\authentication\LoginController;
use App\Http\Controllers\backend\dashboard\DashboardController;
use App\Http\Controllers\backend\user\UserController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login-check', [LoginController::class, 'store'])->name('login-check');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('logout', [LoginController::class, 'logout'])->name('logout.post');

/*
|--------------------------------------------------------------------------
| Authenticated Web Routes
|--------------------------------------------------------------------------
*/
// Root redirects to Vue SPA
Route::get('/', function () {
    return redirect('/spa');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard (Admin Blade)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Flat Members (User Management)
    Route::get('/user', [UserController::class, 'index'])->name('user')->middleware('permission:user list');
    Route::get('/user-create', [UserController::class, 'create'])->name('user-create')->middleware('permission:user add');
    Route::post('/user-store', [UserController::class, 'store'])->name('user-store')->middleware('permission:user add');
    Route::get('/user-edit/{id}', [UserController::class, 'edit'])->name('user-edit')->middleware('permission:user edit');
    Route::post('/user-update/{id}', [UserController::class, 'update'])->name('user-update')->middleware('permission:user edit');
    Route::post('/user-delete/{id}', [UserController::class, 'destroy'])->name('user-delete')->middleware('permission:user delete');
    Route::post('/user-change-status', [UserController::class, 'changeStatus'])->name('user-change-status')->middleware('permission:user status');
});

/*
|--------------------------------------------------------------------------
| Vue 3 SPA Route
|--------------------------------------------------------------------------
*/
Route::get('/spa/{any?}', function () {
    return view('app');
})->where('any', '.*')->name('spa');

