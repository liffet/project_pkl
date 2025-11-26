<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\DamageReportController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\FloorController;
use App\Http\Controllers\Api\BuildingController;

// ================== AUTH TANPA LOGIN (REGISTER & LOGIN) ==================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ================== ROUTE YANG HARUS LOGIN (PAKAI SANCTUM) ==================
Route::middleware('auth:sanctum')->group(function () {

    // ================== LOGOUT & INFO USER ==================
    Route::post('/user/update', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/user/change-password', [AuthController::class, 'changePassword']);
    // ================== HANYA ADMIN YANG BOLEH (CRUD) ==================
    Route::middleware('admin')->group(function () {

        // === CRUD Kategori ===
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // === CRUD Barang ===
        Route::post('/items', [ItemController::class, 'store']);
        Route::put('/items/{id}', [ItemController::class, 'update']);
        Route::delete('/items/{id}', [ItemController::class, 'destroy']);

        // === CRUD Ruangan ===
        Route::post('/rooms', [RoomController::class, 'store']);
        Route::put('/rooms/{id}', [RoomController::class, 'update']);
        Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);

        // === CRUD Lantai ===
        Route::post('/floors', [FloorController::class, 'store']);
        Route::put('/floors/{id}', [FloorController::class, 'update']);
        Route::delete('/floors/{id}', [FloorController::class, 'destroy']);
    });

    // ================== ADMIN & USER (BOLEH LIHAT SAJA) ==================
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/items', [ItemController::class, 'index']);
    Route::get('/items/soon-expired', [ItemController::class, 'soonExpired']);

    // === Lihat Ruangan & Lantai ===
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::get('/rooms/{id}', [RoomController::class, 'show']);
    Route::get('/floors', [FloorController::class, 'index']);
    Route::get('/floors/{id}', [FloorController::class, 'show']);

    Route::get('/buildings', [BuildingController::class, 'index']);
    Route::get('/buildings/{id}/floors', [BuildingController::class, 'floors']);
    // ================== Laporan Kerusakan ==================

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/damage-reports', [DamageReportController::class, 'index']);
        Route::post('/damage-reports', [DamageReportController::class, 'store']);
        Route::get('/damage-reports/{id}', [DamageReportController::class, 'show']);
        Route::put('/damage-reports/{id}', [DamageReportController::class, 'update']);
        Route::delete('/damage-reports/{id}', [DamageReportController::class, 'destroy']);
    });
});

