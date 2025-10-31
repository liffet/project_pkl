<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DamageReport;
use Illuminate\Support\Facades\Auth;

class DamageReportController extends Controller
{
    /**
     * 🔹 Menampilkan semua laporan
     * Admin bisa lihat semua, user hanya miliknya
     */
    public function index()
    {
        $user = Auth::user();

        $query = DamageReport::with([
            'user',
            'item.category',
            'item.room',
            'item.floor'
        ])->latest();

        if ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }

        $reports = $query->get()->map(function ($report) {
            return [
                'id' => $report->id,
                'status' => $report->status,
                'reason' => $report->reason,
                'created_at' => $report->created_at,
                'updated_at' => $report->updated_at,
                'item_code' => $report->item->code ?? null,
                'item_name' => $report->item->name ?? null,
                'category' => $report->item->category->name ?? null,
                'room' => $report->item->room->name ?? null,
                'floor' => $report->item->floor->name ?? null,
                'user' => $report->user->name ?? null,
            ];
        });

        return response()->json([
            'message' => 'List laporan kerusakan berhasil diambil',
            'data' => $reports
        ]);
    }

    /**
     * 🔹 User melaporkan item rusak
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'reason'  => 'required|string|max:1000',
        ]);

        $report = DamageReport::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'reason'  => $request->reason,
            'status'  => 'pending',
        ])->load(['item.category', 'item.room', 'item.floor']);

        return response()->json([
            'message' => 'Laporan kerusakan item berhasil dikirim',
            'data' => [
                'id' => $report->id,
                'status' => $report->status,
                'reason' => $report->reason,
                'item_code' => $report->item->code ?? null,
                'item_name' => $report->item->name ?? null,
                'category' => $report->item->category->name ?? null,
                'room' => $report->item->room->name ?? null,
                'floor' => $report->item->floor->name ?? null,
            ]
        ], 201);
    }

    /**
     * 🔹 Menampilkan detail laporan
     */
    public function show($id)
    {
        $report = DamageReport::with([
            'user',
            'item.category',
            'item.room',
            'item.floor'
        ])->findOrFail($id);

        if (Auth::user()->role === 'user' && $report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Detail laporan berhasil diambil',
            'data' => [
                'id' => $report->id,
                'status' => $report->status,
                'reason' => $report->reason,
                'item_code' => $report->item->code ?? null,
                'item_name' => $report->item->name ?? null,
                'category' => $report->item->category->name ?? null,
                'room' => $report->item->room->name ?? null,
                'floor' => $report->item->floor->name ?? null,
                'user' => $report->user->name ?? null,
            ]
        ]);
    }

    /**
     * 🔹 Admin memperbarui status laporan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected'
        ]);

        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $report = DamageReport::findOrFail($id);
        $report->update(['status' => $request->status]);
        $report->load(['item.category', 'item.room', 'item.floor']);

        return response()->json([
            'message' => 'Status laporan berhasil diperbarui',
            'data' => [
                'id' => $report->id,
                'status' => $report->status,
                'reason' => $report->reason,
                'item_code' => $report->item->code ?? null,
                'item_name' => $report->item->name ?? null,
                'category' => $report->item->category->name ?? null,
                'room' => $report->item->room->name ?? null,
                'floor' => $report->item->floor->name ?? null,
            ]
        ]);
    }

    /**
     * 🔹 Hapus laporan (khusus admin)
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $report = DamageReport::findOrFail($id);
        $report->delete();

        return response()->json(['message' => 'Laporan berhasil dihapus']);
    }
}
