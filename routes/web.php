<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/watchlist/toggle', [\App\Http\Controllers\WatchlistController::class, 'toggle']);
    
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/{id}/toggle-role', [\App\Http\Controllers\AdminController::class, 'toggleUserRole'])->name('admin.users.toggle-role');
    });
    
    // Internal API Endpoints
    Route::prefix('api')->group(function () {
        Route::get('/countries', [\App\Http\Controllers\ApiController::class, 'getCountries']);
        Route::get('/ports', [\App\Http\Controllers\ApiController::class, 'getPorts']);
        Route::get('/risk', [\App\Http\Controllers\ApiController::class, 'getRisk']);
        Route::get('/news', [\App\Http\Controllers\ApiController::class, 'getNews']);
        Route::get('/currency', [\App\Http\Controllers\ApiController::class, 'getCurrency']);
    });
});
