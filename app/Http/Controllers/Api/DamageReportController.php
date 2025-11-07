<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DamageReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
                'photo' => $report->photo ? asset('storage/' . $report->photo) : null,
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
     * 🔹 User melaporkan item rusak (dengan foto bukti)
     * Foto otomatis dikompres & disimpan di storage/app/public/damage_photos
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'reason'  => 'required|string|max:1000',
            'photo'   => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');

            // Pastikan folder ada
            $destination = storage_path('app/public/damage_photos');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            // Simpan nama file unik
            $filename = uniqid('damage_') . '.' . $image->getClientOriginalExtension();

            // 🔹 Kompres otomatis (75%) dan resize agar lebih ringan
            Image::make($image)
                ->resize(1024, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($destination . '/' . $filename, 75);

            // Simpan path di database
            $photoPath = 'damage_photos/' . $filename;
        }

        $report = DamageReport::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'reason'  => $request->reason,
            'photo'   => $photoPath,
            'status'  => 'pending',
        ])->load(['item.category', 'item.room', 'item.floor']);

        return response()->json([
            'message' => 'Laporan kerusakan berhasil dikirim',
            'data' => [
                'id' => $report->id,
                'status' => $report->status,
                'reason' => $report->reason,
                'photo' => $report->photo ? asset('storage/' . $report->photo) : null,
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
                'photo' => $report->photo ? asset('storage/' . $report->photo) : null,
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
                'photo' => $report->photo ? asset('storage/' . $report->photo) : null,
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

        // 🔹 Hapus foto juga dari storage jika ada
        if ($report->photo && Storage::disk('public')->exists($report->photo)) {
            Storage::disk('public')->delete($report->photo);
        }

        $report->delete();

        return response()->json(['message' => 'Laporan berhasil dihapus']);
    }
}
