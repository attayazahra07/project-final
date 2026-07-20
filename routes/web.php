<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/compare', [DashboardController::class, 'compare'])->name('compare');
    Route::post('/watchlist/toggle', [\App\Http\Controllers\WatchlistController::class, 'toggle']);
    
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/{id}/toggle-role', [\App\Http\Controllers\AdminController::class, 'toggleUserRole'])->name('admin.users.toggle-role');

        // Manage Ports
        Route::get('/ports', [\App\Http\Controllers\AdminController::class, 'portsIndex'])->name('admin.ports.index');
        Route::get('/ports/create', [\App\Http\Controllers\AdminController::class, 'portsCreate'])->name('admin.ports.create');
        Route::post('/ports', [\App\Http\Controllers\AdminController::class, 'portsStore'])->name('admin.ports.store');
        Route::get('/ports/{id}/edit', [\App\Http\Controllers\AdminController::class, 'portsEdit'])->name('admin.ports.edit');
        Route::put('/ports/{id}', [\App\Http\Controllers\AdminController::class, 'portsUpdate'])->name('admin.ports.update');
        Route::delete('/ports/{id}', [\App\Http\Controllers\AdminController::class, 'portsDestroy'])->name('admin.ports.destroy');

        // Manage Articles
        Route::get('/articles', [\App\Http\Controllers\AdminController::class, 'articlesIndex'])->name('admin.articles.index');
        Route::get('/articles/create', [\App\Http\Controllers\AdminController::class, 'articlesCreate'])->name('admin.articles.create');
        Route::post('/articles', [\App\Http\Controllers\AdminController::class, 'articlesStore'])->name('admin.articles.store');
        Route::get('/articles/{id}/edit', [\App\Http\Controllers\AdminController::class, 'articlesEdit'])->name('admin.articles.edit');
        Route::put('/articles/{id}', [\App\Http\Controllers\AdminController::class, 'articlesUpdate'])->name('admin.articles.update');
        Route::delete('/articles/{id}', [\App\Http\Controllers\AdminController::class, 'articlesDestroy'])->name('admin.articles.destroy');
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
