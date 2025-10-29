<?php

namespace App\Http\Controllers;

use App\Models\DamageReport;
use Illuminate\Http\Request;

class DamageReportController extends Controller
{
    /**
     * Menampilkan daftar laporan kerusakan
     */
    public function index()
    {
        // Eager loading user dan item beserta relasi item
        $reports = DamageReport::with([
            'user',
            'item.category',
            'item.room',
            'item.floor'
        ])->latest()->paginate(10); // gunakan paginate agar Blade pagination jalan

        // Statistik untuk Blade
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

    /**
     * Menampilkan detail laporan tertentu
     */
    public function show($id)
    {
        $report = DamageReport::with([
            'user',
            'item.category',
            'item.room',
            'item.floor'
        ])->findOrFail($id);

        return view('reports.show', compact('report'));
    }

    /**
     * Admin menerima atau menolak laporan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $report = DamageReport::findOrFail($id);
        $report->update([
            'status' => $request->status,
        ]);

        return redirect()->route('damage-reports.index')->with('success', 'Status laporan berhasil diperbarui.');
    }
}
