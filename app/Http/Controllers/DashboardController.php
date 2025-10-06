<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class DashboardController extends Controller
{
    public function __construct()
    {
        // pastikan dashboard hanya untuk user yang login
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // jumlah total perangkat
        $totalItems = Item::count();

        // perangkat yang sudah lewat replacement_date -> harus maintenance sekarang
        $maintenanceNow = Item::whereDate('replacement_date', '<', now())->count();

        // perangkat yang akan mencapai replacement_date dalam 7 hari (termasuk hari ini)
        $soonMaintenance = Item::whereDate('replacement_date', '>=', now())
                               ->whereDate('replacement_date', '<=', now()->addDays(7))
                               ->count();

        // perangkat yang masih aman (lebih dari 30 hari dari sekarang)
        $safeItems = Item::whereDate('replacement_date', '>', now()->addDays(30))->count();

        // perangkat yang tidak termasuk kategori di atas (opsional)
        $nearSafe = $totalItems - ($maintenanceNow + $soonMaintenance + $safeItems);

        // --- daftar item sesuai kategori (dipakai di tabel) ---
        $maintenanceList = Item::whereDate('replacement_date', '<', now())
                               ->with('category')
                               ->orderBy('replacement_date', 'asc')
                               ->take(3)
                               ->get();

        $soonList = Item::whereDate('replacement_date', '>=', now())
                        ->whereDate('replacement_date', '<=', now()->addDays(7))
                        ->with('category')
                        ->orderBy('replacement_date', 'asc')
                        ->take(3)
                        ->get();

        $safeList = Item::whereDate('replacement_date', '>', now()->addDays(7))
                        ->with('category')
                        ->orderBy('replacement_date', 'asc')
                        ->take(3)
                        ->get();

        return view('dashboard', compact(
            'user',
            'totalItems',
            'maintenanceNow',
            'soonMaintenance',
            'safeItems',
            'nearSafe',
            'maintenanceList',
            'soonList',
            'safeList'
        ));
    }
}
