<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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



Route::get('/register', function () {
    return view('auth.register');
});
Route::get('/', function () {
    return view(view: 'auth.login');
});

Route::post('/register', [AuthController::class, 'registerWeb'])->name('register.web');
Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.web');

  