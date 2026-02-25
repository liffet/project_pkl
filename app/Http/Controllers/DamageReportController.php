<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use App\Models\Building;
use App\Models\Category;
use App\Models\DamageReport;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKerusakanExport;
use Illuminate\Support\Facades\Storage;

class DamageReportController extends Controller
{
    /**
     * ======================
     * EXPORT EXCEL
     * ======================
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new LaporanKerusakanExport($request->query()),
            'laporan_kerusakan.xlsx'
        );
    }

    /**
     * ======================
     * INDEX (LIST + FILTER)
     * ======================
     */
    public function index(Request $request)
    {
        $query = DamageReport::with([
            'item.category',
            'item.building',
            'item.floor',
            'item.room',
            'user'
        ]);

        // Filter by item name
        if ($request->filled('item_name')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->item_name . '%');
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by building
        if ($request->filled('building_id')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('building_id', $request->building_id);
            });
        }

        // Filter by floor
        if ($request->filled('floor_id')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('floor_id', $request->floor_id);
            });
        }

        // Filter by room
        if ($request->filled('room_id')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('room_id', $request->room_id);
            });
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // ✅ Filter berdasarkan tanggal (1 hari)
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // ✅ Filter tanggal dari - sampai
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('created_at', [
                $request->tanggal_dari . ' 00:00:00',
                $request->tanggal_sampai . ' 23:59:59'
            ]);
        }

        $reports = $query->latest()->paginate(15);

        // Dropdown Data
        $categories = Category::all();
        $buildings  = Building::all();
        $floors     = Floor::all();
        $rooms      = Room::all();
        $users      = User::all();

        // Statistik (tidak terpengaruh filter)
        $totalReports      = DamageReport::count();
        $pendingReports    = DamageReport::where('status', 'pending')->count();
        $acceptedReports   = DamageReport::where('status', 'accepted')->count();
        $inProgressReports = DamageReport::where('status', 'in_progress')->count();
        $completedReports  = DamageReport::where('status', 'completed')->count();
        $rejectedReports   = DamageReport::where('status', 'rejected')->count();

        return view('reports.index', compact(
            'reports',
            'categories',
            'buildings',
            'floors',
            'rooms',
            'users',
            'totalReports',
            'pendingReports',
            'acceptedReports',
            'inProgressReports',
            'completedReports',
            'rejectedReports'
        ));
    }

    /**
     * ======================
     * SHOW DETAIL
     * ======================
     */
    public function show($id)
    {
        $report = DamageReport::with([
            'user',
            'item.category',
            'item.room',
            'item.floor',
            'item.building',
        ])->findOrFail($id);

        return view('damage-reports.show', compact('report'));
    }

    /**
     * ======================
     * STORE (USER)
     * ======================
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'reason'  => 'required|string',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')
                ->store('damage_photos', 'public');
        }

        DamageReport::create([
            'user_id' => auth()->id(),
            'item_id' => $request->item_id,
            'reason'  => $request->reason,
            'photo'   => $photoPath,
            'status'  => 'pending',
        ]);

        return redirect()
            ->route('damage-reports.index')
            ->with('success', 'Laporan kerusakan berhasil dikirim.');
    }

    /**
     * ======================
     * UPDATE STATUS (ADMIN)
     * ======================
     */
    public function update(Request $request, $id)
    {
        $report = DamageReport::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,accepted,in_progress,completed,rejected'
        ]);

        $report->update([
            'status' => $request->status
        ]);

        $statusMessages = [
            'accepted'    => 'Laporan berhasil diterima',
            'rejected'    => 'Laporan telah ditolak',
            'in_progress' => 'Proses perbaikan dimulai',
            'completed'   => 'Perbaikan telah selesai'
        ];

        return redirect()
            ->route('damage-reports.index')
            ->with('success', $statusMessages[$request->status] ?? 'Status berhasil diperbarui');
    }

    /**
     * ======================
     * DELETE
     * ======================
     */
    public function destroy($id)
    {
        $report = DamageReport::findOrFail($id);

        if ($report->photo && Storage::disk('public')->exists($report->photo)) {
            Storage::disk('public')->delete($report->photo);
        }

        $report->delete();

        return redirect()
            ->route('damage-reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}