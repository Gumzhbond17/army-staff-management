<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\StaffCategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WorkingStatusController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::prefix('ranks')->group(function () {
//     Route::get('/', [RankController::class, 'index']);
// });

// Route::prefix('units')->group(function () {
//     Route::get('/', [UnitController::class, 'index']);
// });

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return view('welcome'); // your protected view
    });

    // Use resources
    Route::resource('ranks', RankController::class);
    Route::resource('units', UnitController::class);
    Route::resource('provinces', ProvinceController::class);
    Route::apiResource('districts', DistrictController::class);
    Route::resource('staff-categories', StaffCategoryController::class);
    Route::resource('working-statuses', WorkingStatusController::class);
});
