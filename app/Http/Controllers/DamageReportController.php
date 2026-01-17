<?php

namespace App\Http\Controllers;

use App\Models\DamageReport;
use Illuminate\Http\Request;
use App\Exports\LaporanKerusakanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class DamageReportController extends Controller
{
    /**
     * ======================
     * EXPORT EXCEL
     * ======================
     */
    public function exportExcel()
    {
        return Excel::download(
            new LaporanKerusakanExport(),
            'laporan_kerusakan.xlsx'
        );
    }

    /**
     * ======================
     * INDEX (LIST + TAB)
     * ======================
     */
    public function index(Request $request)
{
    $activeTab = $request->get('tab', 'all');

    $relations = [
        'user',
        'item.category',
        'item.room',
        'item.floor',
        'item.building',
    ];

    // ======================
    // DATA PER TAB
    // ======================

    $allReports = DamageReport::with($relations)
        ->latest()
        ->paginate(10, ['*'], 'all_page');

    $pendingList = DamageReport::with($relations)
        ->where('status', 'pending')
        ->latest()
        ->paginate(10, ['*'], 'pending_page');

    $acceptedList = DamageReport::with($relations)
        ->where('status', 'accepted')
        ->latest()
        ->paginate(10, ['*'], 'accepted_page');

    $inProgressList = DamageReport::with($relations)
        ->where('status', 'in_progress')
        ->latest()
        ->paginate(10, ['*'], 'in_progress_page');

    $completedList = DamageReport::with($relations)
        ->where('status', 'completed')
        ->latest()
        ->paginate(10, ['*'], 'completed_page');

    $rejectedList = DamageReport::with($relations)
        ->where('status', 'rejected')
        ->latest()
        ->paginate(10, ['*'], 'rejected_page');

    // ======================
    // PILIH DATA UNTUK TABLE
    // ======================

    $reports = match ($activeTab) {
        'pending'     => $pendingList,
        'accepted'    => $acceptedList,
        'in_progress' => $inProgressList,
        'completed'   => $completedList,
        'rejected'    => $rejectedList,
        default       => $allReports,
    };

    // ======================
    // STATISTIK DASHBOARD
    // ======================

    $totalReports      = DamageReport::count();
    $pendingReports    = DamageReport::where('status', 'pending')->count();
    $acceptedReports   = DamageReport::where('status', 'accepted')->count();
    $inProgressReports = DamageReport::where('status', 'in_progress')->count();
    $completedReports  = DamageReport::where('status', 'completed')->count();
    $rejectedReports   = DamageReport::where('status', 'rejected')->count();

    return view('reports.index', compact(
        'activeTab',
        'reports',        // 🔥 INI YANG WAJIB ADA
        'allReports',
        'pendingList',
        'acceptedList',
        'inProgressList',
        'completedList',
        'rejectedList',
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
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected,in_progress,completed',
        ]);

        $report = DamageReport::findOrFail($id);

        $allowedTransitions = [
            'pending'     => ['accepted', 'rejected'],
            'accepted'    => ['in_progress'],
            'in_progress' => ['completed'],
        ];

        $currentStatus = $report->status;
        $newStatus     = $request->status;

        if (!isset($allowedTransitions[$currentStatus])) {
            return back()->with('error', 'Status ini tidak dapat diubah lagi.');
        }

        if (!in_array($newStatus, $allowedTransitions[$currentStatus])) {
            return back()->with('error', 'Perubahan status tidak valid.');
        }

        $report->update([
            'status' => $newStatus,
        ]);

        return redirect()
            ->route('damage-reports.index', [
                'tab' => $request->get('tab', 'all'),
            ])
            ->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * ======================
     * DELETE
     * ======================
     */
    public function destroy($id)
    {
        $report = DamageReport::findOrFail($id);

        if (
            $report->photo &&
            Storage::disk('public')->exists($report->photo)
        ) {
            Storage::disk('public')->delete($report->photo);
        }

        $report->delete();

        return redirect()
            ->route('damage-reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}