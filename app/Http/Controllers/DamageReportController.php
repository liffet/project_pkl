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
     * Export ke Excel
     */
    public function exportExcel()
    {
        return Excel::download(new LaporanKerusakanExport(), 'laporan_kerusakan.xlsx');
    }

    /**
     * Menampilkan daftar laporan kerusakan
     */
    public function index()
    {
        $reports = DamageReport::with([
            'user',
            'item.category',
            'item.room',
            'item.floor'
        ])->latest()->paginate(10);

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
     * Menyimpan laporan kerusakan baru (dengan foto)
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'reason' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('damage_photos', 'public');
        }

        DamageReport::create([
            'user_id' => auth()->id(),
            'item_id' => $request->item_id,
            'reason' => $request->reason,
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        return redirect()->route('damage-reports.index')->with('success', 'Laporan kerusakan berhasil dikirim.');
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

    /**
     * Menghapus laporan dan foto terkait
     */
    public function destroy($id)
    {
        $report = DamageReport::findOrFail($id);

        if ($report->photo && Storage::disk('public')->exists($report->photo)) {
            Storage::disk('public')->delete($report->photo);
        }

        $report->delete();

        return redirect()->route('damage-reports.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
