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
use App\Http\Controllers\BuildingController;

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
    Route::get('/damage-reports/export/excel', [DamageReportController::class, 'exportExcel'])->name('damage-reports.export.excel');
    Route::get('/items/export', [ItemWebController::class, 'exportExcel'])->name('items.export.excel');
    Route::get('/rooms/export/excel', [RoomController::class, 'exportExcel'])->name('rooms.export.excel');
    Route::get('/categories/export/excel', [CategoryController::class, 'exportExcel'])->name('categories.export.excel');
    Route::get('/floors/export/excel/{building_id}', [FloorController::class, 'exportExcel'])
        ->name('floors.export.excel');

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Resource routes
    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemWebController::class);
    Route::resource('floors', FloorController::class)->except(['show']);
    Route::resource('rooms', RoomController::class);
    Route::resource('damage-reports', DamageReportController::class)->only(['index', 'show', 'update']);
    
    Route::get('/building', [BuildingController::class, 'index'])->name('building.index');

    // Route tambahan
    Route::get('/search', [ItemWebController::class, 'search'])->name('items.search');
    
    // Dashboard items & reports
    Route::get('/dashboard/items', [DashboardController::class, 'item'])->name('dashboard.items');
    Route::get('/dashboard/reports', [DashboardController::class, 'report'])->name('dashboard.reports');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// API Routes untuk dynamic dropdown
Route::get('/api/floors/by-building/{buildingId}', function($buildingId) {
    $floors = \App\Models\Floor::where('building_id', $buildingId)->get();
    return response()->json([
        'success' => true,
        'floors' => $floors
    ]);
});

Route::get('/api/rooms/by-floor/{floorId}', function($floorId) {
    $rooms = \App\Models\Room::where('floor_id', $floorId)->get();
    return response()->json([
        'success' => true,
        'rooms' => $rooms
    ]);
}); // ← TAMBAHKAN SEMICOLON INI (yang tadi hilang)