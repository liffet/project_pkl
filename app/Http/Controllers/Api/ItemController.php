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
        $items = Item::with('category')->latest()->get();

        $data = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'category' => $item->category->name ?? '-',
                'room' => $item->room,
                'floor' => $item->floor,
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
            'category_id'       => 'required|exists:categories,id',
            'code'              => 'nullable|string|max:50|unique:items,code',
            'name'              => 'required|string|max:255',
            'room'              => 'nullable|string|max:100',
            'floor'             => 'nullable|string|max:50',
            'status'            => 'required|in:active,inactive',
            'install_date'      => 'required|date',
            'replacement_date'  => 'required|date|after_or_equal:install_date',
            'photo'             => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'category_id', 'code', 'name', 'room', 'floor',
            'status', 'install_date', 'replacement_date'
        ]);

        // Auto generate code kalau kosong
        $data['code'] = $data['code'] ?? 'ITM-' . strtoupper(Str::random(8));

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('items', 'public');
        }

        $item = Item::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan',
            'data' => $item
        ], 201);
    }

    // =========================
    // PUT (Admin Only)
    // =========================
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'category_id'       => 'sometimes|exists:categories,id',
            'code'              => 'sometimes|string|max:50|unique:items,code,' . $item->id,
            'name'              => 'sometimes|string|max:255',
            'room'              => 'nullable|string|max:100',
            'floor'             => 'nullable|string|max:50',
            'status'            => 'required|in:active,inactive',
            'install_date'      => 'sometimes|date',
            'replacement_date'  => 'sometimes|date|after_or_equal:install_date',
            'photo'             => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'category_id', 'code', 'name', 'room', 'floor',
            'status', 'install_date', 'replacement_date'
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('items', 'public');
        }

        $item->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil diupdate',
            'data' => $item
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
                     ->with('category')
                     ->get();

        $data = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'category' => $item->category->name ?? '-',
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
