<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemWebController;
use App\Http\Controllers\DamageReportController;




// Route logout manual



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



// Halaman Register & Login
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Aksi Register & Login (POST)
Route::post('/register', [AuthController::class, 'registerWeb'])->name('register.web');
Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.web');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::resource('categories', CategoryController::class);

Route::resource('items', ItemWebController::class);

Route::get('/search', [ItemWebController::class, 'search'])->name('items.search');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('damage-reports', DamageReportController::class)->only(['index', 'show', 'update']);
