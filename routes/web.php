<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\StaffCategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkingStatusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    // Province -> Districts lookup for the add form AJAX
    Route::get('/provinces/{province}/districts', [DistrictController::class, 'byProvince'])
        ->name('provinces.districts');

    Route::resource('districts', DistrictController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('ranks', RankController::class);
    Route::resource('units', UnitController::class);
    Route::resource('provinces', ProvinceController::class);
    Route::resource('staff-categories', StaffCategoryController::class);
    Route::resource('working-statuses', WorkingStatusController::class);

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)
            ->only(['index', 'store', 'update', 'destroy']);
    });
});
