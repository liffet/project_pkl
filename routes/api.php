<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\DamageReportController;

// ================== Auth Routes (No Middleware) ==================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ================== Authenticated Routes ==================
Route::middleware('auth:sanctum')->group(function () {
    
    // Logout & User Info
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ================== Admin Only Routes (Harus di ATAS) ==================
    Route::middleware('admin')->group(function () {
        // CRUD Kategori (Admin)
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // CRUD Barang (Admin)
        Route::post('/items', [ItemController::class, 'store']);
        Route::put('/items/{id}', [ItemController::class, 'update']);
        Route::delete('/items/{id}', [ItemController::class, 'destroy']);


    });

    // ================== User & Admin Routes (Harus di BAWAH) ==================
    // Lihat kategori & barang (User biasa + Admin bisa akses)
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/items', [ItemController::class, 'index']);
    Route::get('/items/soon-expired', [ItemController::class, 'soonExpired']);
    
    Route::get('/damage-reports', [DamageReportController::class, 'index']);
    Route::post('/damage-reports', [DamageReportController::class, 'store']);
    Route::get('/damage-reports/{id}', [DamageReportController::class, 'show']);
    Route::put('/damage-reports/{id}', [DamageReportController::class, 'update']);
    Route::delete('/damage-reports/{id}', [DamageReportController::class, 'destroy']);
});