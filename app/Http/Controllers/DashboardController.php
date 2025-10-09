<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Jumlah total perangkat
        $totalItems = Item::count();

        // Perangkat yang sudah lewat replacement_date (harus maintenance)
        $maintenanceNow = Item::whereDate('replacement_date', '<', now())->count();

        // Perangkat yang akan maintenance dalam 7 hari ke depan
        $soonMaintenance = Item::whereDate('replacement_date', '>=', now())
                               ->whereDate('replacement_date', '<=', now()->addDays(7))
                               ->count();

        // Perangkat yang masih aman (lebih dari 7 hari dari sekarang)
        $safeItems = Item::whereDate('replacement_date', '>', now()->addDays(7))->count();

        // Daftar item untuk tabel
        $maintenanceList = Item::whereDate('replacement_date', '<', now())
                               ->with('category')
                               ->orderBy('replacement_date', 'asc')
                               ->get();

        $soonList = Item::whereDate('replacement_date', '>=', now())
                        ->whereDate('replacement_date', '<=', now()->addDays(7))
                        ->with('category')
                        ->orderBy('replacement_date', 'asc')
                        ->get();

        $safeList = Item::whereDate('replacement_date', '>', now()->addDays(7))
                        ->with('category')
                        ->orderBy('replacement_date', 'asc')
                        ->get();

        return view('dashboard', compact(
            'user',
            'totalItems',
            'maintenanceNow',
            'soonMaintenance',
            'safeItems',
            'maintenanceList',
            'soonList',
            'safeList'
        ));
    }
}