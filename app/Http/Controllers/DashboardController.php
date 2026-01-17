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
        $activeTab = $request->query('tab', 'all');
        $page = $request->query('page', 1);
        $perPage = 10;
        $today = Carbon::today();
        $totalItems = Item::count();
        $maintenanceNow = Item::whereDate('replacement_date', '<', $today)->count();
        $soonMaintenance = Item::whereDate('replacement_date', '>=', $today)
            ->whereDate('replacement_date', '<=', $today->copy()->addDays(7))
            ->count();
        $safeItems = Item::whereDate('replacement_date', '>', $today->copy()->addDays(7))->count();
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

      
        return view('dashboard.reports', compact(
            'reports',
            'totalReports',
            'pendingReports',
            'acceptedReports',
            'rejectedReports'
        ));
    }


    public function item(Request $request)
    {
        $categories = Category::all();

        $itemsQuery = Item::with('category')->latest();

        if ($request->has('category_id') && $request->category_id) {
            $itemsQuery->where('category_id', $request->category_id);
        }

        $items = $itemsQuery->paginate(10)->withQueryString();

        $totalItems = Item::count();
        $activeItems = Item::where('status', 'active')->count();
        $inactiveItems = Item::where('status', 'inactive')->count();
        $needMaintenance = Item::where('replacement_date', '<=', now()->addDays(7))->count();


        return view('dashboard.items', compact(
            'items',
            'categories',
            'totalItems',
            'activeItems',
            'inactiveItems',
            'needMaintenance'
        ));
    }

    public function damagereport(Request $request)
    {

        $activeTab = $request->get('tab', 'all');

  
        $statuses = ['pending', 'accepted', 'rejected'];
        $totalReports = DamageReport::count();
        $stats = [];
        foreach ($statuses as $status) {
            $stats[$status] = DamageReport::where('status', $status)->count();
        }

   
        $reports = [];
  
        $reports['all'] = DamageReport::with(['user', 'item.category', 'item.room', 'item.floor'])
            ->latest()
            ->paginate(10, ['*'], 'all_page');

        foreach ($statuses as $status) {
            $reports[$status] = DamageReport::with(['user', 'item.category', 'item.room', 'item.floor'])
                ->where('status', $status)
                ->latest()
                ->paginate(10, ['*'], $status . '_page');
        }

        return view('reports.index', [
            'activeTab' => $activeTab,
            'totalReports' => $totalReports,
            'pendingReports' => $stats['pending'],
            'acceptedReports' => $stats['accepted'],
            'rejectedReports' => $stats['rejected'],
            'allReports' => $reports['all'],
            'pendingList' => $reports['pending'],
            'acceptedList' => $reports['accepted'],
            'rejectedList' => $reports['rejected'],
        ]);
    }
}
