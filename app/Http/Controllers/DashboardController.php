<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Category;
use App\Models\DamageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Tab aktif dari query string, default 'all'
        $activeTab = $request->query('tab', 'all');

        // Page saat ini dari query string, default 1
        $page = $request->query('page', 1);

        $perPage = 10;

        $today = Carbon::today();

        // Statistik
        $totalItems = Item::count();
        $maintenanceNow = Item::whereDate('replacement_date', '<', $today)->count();
        $soonMaintenance = Item::whereDate('replacement_date', '>=', $today)
            ->whereDate('replacement_date', '<=', $today->copy()->addDays(7))
            ->count();
        $safeItems = Item::whereDate('replacement_date', '>', $today->copy()->addDays(7))->count();

        // Pagination dengan override page sesuai tab aktif
        $allItems = Item::with('category')
            ->orderBy('replacement_date', 'asc')
            ->paginate($perPage, ['*'], 'page', $activeTab === 'all' ? $page : 1);

        $safeList = Item::whereDate('replacement_date', '>', $today->copy()->addDays(7))
            ->with('category')
            ->orderBy('replacement_date', 'asc')
            ->paginate($perPage, ['*'], 'page', $activeTab === 'safe' ? $page : 1);

        $soonList = Item::whereDate('replacement_date', '>=', $today)
            ->whereDate('replacement_date', '<=', $today->copy()->addDays(7))
            ->with('category')
            ->orderBy('replacement_date', 'asc')
            ->paginate($perPage, ['*'], 'page', $activeTab === 'soon' ? $page : 1);

        $maintenanceList = Item::whereDate('replacement_date', '<', $today)
            ->with('category')
            ->orderBy('replacement_date', 'asc')
            ->paginate($perPage, ['*'], 'page', $activeTab === 'maintenance' ? $page : 1);

        return view('dashboard', compact(
            'user',
            'totalItems',
            'maintenanceNow',
            'soonMaintenance',
            'safeItems',
            'maintenanceList',
            'soonList',
            'safeList',
            'allItems',
            'activeTab'
        ));
    }

    public function report()
    {
        $reports = DamageReport::with(['user', 'category'])
            ->latest()
            ->paginate(10);

        $totalReports = DamageReport::count();
        $pendingReports = DamageReport::where('status', 'pending')->count();
        $acceptedReports = DamageReport::where('status', 'accepted')->count();
        $rejectedReports = DamageReport::where('status', 'rejected')->count();

        return view('reports.index', compact(
            'reports',
            'totalReports',
            'pendingReports',
            'acceptedReports',
            'rejectedReports'
        ));
    }

   public function item(Request $request)
{
    $categories = Category::all(); // <-- tambahkan ini

    $itemsQuery = Item::with('category')->latest();

    // Filter kategori jika dipilih
    if ($request->has('category_id') && $request->category_id) {
        $itemsQuery->where('category_id', $request->category_id);
    }

    $items = $itemsQuery->paginate(10)->withQueryString();

    // Statistik
    $totalItems = Item::count();
    $activeItems = Item::where('status', 'active')->count();
    $inactiveItems = Item::where('status', 'inactive')->count();
    $needMaintenance = Item::where('replacement_date', '<=', now()->addDays(7))->count();

    return view('items.index', compact(
        'items',
        'categories', // <-- kirim ke view
        'totalItems',
        'activeItems',
        'inactiveItems',
        'needMaintenance'
    ));
}

}