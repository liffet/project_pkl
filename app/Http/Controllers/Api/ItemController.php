<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    // =========================
    // GET (User + Admin)
    // =========================
    public function index()
{
    $items = Item::with(['category', 'building', 'floor', 'room'])->latest()->get();

    $data = $items->map(function ($item) {
        return [
            'id' => $item->id,
            'code' => $item->code,
            'name' => $item->name,

            'category_id' => $item->category_id,
            'building_id' => $item->building_id,
            'floor_id' => $item->floor_id,
            'room_id' => $item->room_id, // ← WAJIB ADA!

            'category' => $item->category ? [
                'id' => $item->category->id,
                'name' => $item->category->name
            ] : null,

            'building' => $item->building ? [
                'id' => $item->building->id,
                'name' => $item->building->name
            ] : null,

            'floor' => $item->floor ? [
                'id' => $item->floor->id,
                'name' => $item->floor->name    
            ] : null,

            'room' => $item->room ? [
                'id' => $item->room->id,
                'name' => $item->room->name
            ] : null,

            'status' => $item->status,
            'install_date' => $item->install_date,
            'replacement_date' => $item->replacement_date,
            'photo' => $item->photo ? asset('storage/' . $item->photo) : null,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $data,
    ]);
}


    // =========================
    // POST (Admin Only)
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'building_id' => 'required|exists:buildings,id',      // ← TAMBAHKAN INI
            'floor_id' => 'required|exists:floors,id',         // ← UBAH DARI 'floor' JADI 'floor_id'
            'room_id' => 'required|exists:rooms,id',          // ← UBAH DARI 'room' JADI 'room_id'
            'code' => 'nullable|string|max:50|unique:items,code',
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'install_date' => 'required|date',
            'replacement_date' => 'required|date|after_or_equal:install_date',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'category_id',
            'building_id',      // ← TAMBAHKAN INI
            'floor_id',         // ← UBAH
            'room_id',          // ← UBAH
            'code',
            'name',
            'status',
            'install_date',
            'replacement_date'
        ]);

        // Auto generate code kalau kosong
        $data['code'] = $data['code'] ?? 'ITM-' . strtoupper(Str::random(8));

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('items', 'public');
        }

        $item = Item::create($data);
        $item->load(['category', 'building', 'floor', 'room']); // ← LOAD RELASI

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan',
            'data' => [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'category' => $item->category->name ?? '-',
                'building' => $item->building->name ?? '-',
                'floor' => $item->floor->name ?? '-',
                'room' => $item->room->name ?? '-',
                'status' => $item->status,
                'install_date' => $item->install_date,
                'replacement_date' => $item->replacement_date,
                'photo' => $item->photo ? asset('storage/' . $item->photo) : null,
            ]
        ], 201);
    }

    // =========================
    // PUT (Admin Only)
    // =========================
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'building_id' => 'sometimes|exists:buildings,id',     // ← TAMBAHKAN INI
            'floor_id' => 'sometimes|exists:floors,id',        // ← UBAH
            'room_id' => 'sometimes|exists:rooms,id',         // ← UBAH
            'code' => 'sometimes|string|max:50|unique:items,code,' . $item->id,
            'name' => 'sometimes|string|max:255',
            'status' => 'required|in:active,inactive',
            'install_date' => 'sometimes|date',
            'replacement_date' => 'sometimes|date|after_or_equal:install_date',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'category_id',
            'building_id',      // ← TAMBAHKAN INI
            'floor_id',         // ← UBAH
            'room_id',          // ← UBAH
            'code',
            'name',
            'status',
            'install_date',
            'replacement_date'
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('items', 'public');
        }

        $item->update($data);
        $item->load(['category', 'building', 'floor', 'room']); // ← LOAD RELASI

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil diupdate',
            'data' => [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'category' => $item->category->name ?? '-',
                'building' => $item->building->name ?? '-',
                'floor' => $item->floor->name ?? '-',
                'room' => $item->room->name ?? '-',
                'status' => $item->status,
                'install_date' => $item->install_date,
                'replacement_date' => $item->replacement_date,
                'photo' => $item->photo ? asset('storage/' . $item->photo) : null,
            ]
        ]);
    }

    // =========================
    // DELETE (Admin Only)
    // =========================
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus'
        ]);
    }

    // =========================
    // Barang Hampir Expired (User + Admin)
    // =========================
    public function soonExpired()
    {
        $items = Item::whereDate('replacement_date', '<=', now()->addDays(7))
            ->with(['category', 'building', 'floor', 'room']) // ← TAMBAHKAN RELASI
            ->get();

        $data = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'category' => $item->category->name ?? '-',
                'building' => $item->building->name ?? '-', // ← TAMBAHKAN INI
                'floor' => $item->floor->name ?? '-',
                'room' => $item->room->name ?? '-',
                'status' => $item->status,
                'replacement_date' => $item->replacement_date,
                'photo' => $item->photo ? asset('storage/' . $item->photo) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}