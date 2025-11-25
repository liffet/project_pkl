<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Floor;

class FloorController extends Controller
{
    /**
     * Menampilkan semua lantai
     */
    public function index()
{
    // 🔹 Cek apakah ada request building_id
    $buildingId = request()->query('building_id');

    // 🔹 Jika ada, filter lantai berdasarkan gedung
    if ($buildingId) {
        $floors = Floor::where('building_id', $buildingId)
            ->select('id', 'name', 'building_id')
            ->get();
    } else {
        // 🔹 Jika tidak ada, jangan tampilkan semua lantai (optional)
        // Bisa kembalikan array kosong supaya tidak numpuk
        $floors = [];
    }

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
        $floor = Floor::select('id', 'name', 'building_id')->find($id);

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
}
