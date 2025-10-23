<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemWebController;
use App\Http\Controllers\DamageReportController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\RoomController;

// Halaman Register & Login
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Aksi Register & Login
Route::post('/register', [AuthController::class, 'registerWeb'])->name('register.web');
Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.web');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Resource routes
    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemWebController::class);  // Ini sudah cukup untuk CRUD + filter
    Route::resource('floors', FloorController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('damage-reports', DamageReportController::class)->only(['index', 'show', 'update']);
    
    // Route tambahan
    Route::get('/search', [ItemWebController::class, 'search'])->name('items.search');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// HAPUS ATAU COMMENT BARIS INI - INI YANG BIKIN KONFLIK:
Route::get('/dashboard/items', [DashboardController::class, 'item'])->name('dashboard.items');
Route::get('/dashboard/reports', [DashboardController::class, 'report'])->name('dashboard.reports');


