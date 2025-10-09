<?php

namespace App\Http\Controllers;

use App\Models\DamageReport;
use Illuminate\Http\Request;

class DamageReportController extends Controller
{
    // Menampilkan semua laporan kerusakan
    public function index()
    {
        $reports = DamageReport::with(['user', 'category'])->latest()->get();
        return view('reports.index', compact('reports'));
    }

    // Menampilkan detail laporan tertentu
    public function show($id)
    {
        $report = DamageReport::with(['user', 'category'])->findOrFail($id);
        return view('reports.show', compact('report'));
    }

    // Admin menerima atau menolak laporan
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected', // hanya dua opsi
        ]);

        $report = DamageReport::findOrFail($id);
        $report->update([
            'status' => $request->status,
        ]);

        return redirect()->route('damage-reports.index')->with('success', 'Status laporan berhasil diperbarui.');
    }
}
