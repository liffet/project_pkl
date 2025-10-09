<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DamageReport;
use Illuminate\Support\Facades\Auth;

class DamageReportController extends Controller
{
    /**
     * Menampilkan semua laporan (khusus admin)
     */
    public function index()
    {
        $user = Auth::user();

        // Kalau user biasa → hanya lihat laporan miliknya
        if ($user->role === 'user') {
            $reports = DamageReport::where('user_id', $user->id)
                ->with(['user', 'category'])
                ->latest()
                ->get();
        } else {
            // Kalau admin → lihat semua laporan
            $reports = DamageReport::with(['user', 'category'])
                ->latest()
                ->get();
        }

        return response()->json([
            'message' => 'List laporan kerusakan berhasil diambil',
            'data' => $reports
        ]);
    }

    /**
     * Membuat laporan baru (user)
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'device'      => 'required|string|max:255',
            'reason'      => 'required|string|max:1000',
        ]);

        $report = DamageReport::create([
            'user_id'     => Auth::id(),
            'category_id' => $request->category_id,
            'device'      => $request->device,
            'reason'      => $request->reason,
            'status'      => 'pending',
        ]);

        return response()->json([
            'message' => 'Laporan kerusakan berhasil dikirim',
            'data'    => $report
        ], 201);
    }

    /**
     * Menampilkan detail laporan
     */
    public function show($id)
    {
        $report = DamageReport::with(['user', 'category'])->findOrFail($id);

        // Hanya admin atau pemilik laporan yang boleh melihat
        if (Auth::user()->role === 'user' && $report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Detail laporan berhasil diambil',
            'data' => $report
        ]);
    }

    /**
     * Update status laporan (khusus admin)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected'
        ]);

        $report = DamageReport::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $report->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status laporan berhasil diperbarui',
            'data' => $report
        ]);
    }

    /**
     * Hapus laporan (opsional, hanya admin)
     */
    public function destroy($id)
    {
        $report = DamageReport::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $report->delete();

        return response()->json(['message' => 'Laporan berhasil dihapus']);
    }
}
