<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    /**
     * Menampilkan semua lantai (bisa diakses user & admin)
     */
    public function index()
    {
        $floors = Floor::all();

        return response()->json([
            'success' => true,
            'data' => $floors
        ]);
    }

    /**
     * Menampilkan 1 lantai berdasarkan ID
     */
    public function show($id)
    {
        $floor = Floor::find($id);

        if (!$floor) {
            return response()->json([
                'success' => false,
                'message' => 'Lantai tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $floor
        ]);
    }

    /**
     * Menambah lantai (admin only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $floor = Floor::create([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lantai berhasil ditambahkan',
            'data' => $floor
        ], 201);
    }

    /**
     * Mengupdate lantai (admin only)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $floor = Floor::find($id);

        if (!$floor) {
            return response()->json([
                'success' => false,
                'message' => 'Lantai tidak ditemukan'
            ], 404);
        }

        $floor->update([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lantai berhasil diperbarui',
            'data' => $floor
        ]);
    }   

    /**
     * Menghapus lantai (admin only)
     */
    public function destroy($id)
    {
        $floor = Floor::find($id);

        if (!$floor) {
            return response()->json([
                'success' => false,
                'message' => 'Lantai tidak ditemukan'
            ], 404);
        }

        $floor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lantai berhasil dihapus'
        ]);
    }
}
