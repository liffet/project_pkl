<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;

class BuildingController extends Controller
{
    /**
     * Mengambil semua gedung
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Building::all()
        ]);
    }

    /**
     * Mengambil lantai berdasarkan ID gedung
     * GET /api/buildings/{id}/floors
     */
    public function floors($id)
    {
        $building = Building::with('floors')->find($id);

        if (!$building) {
            return response()->json([
                'success' => false,
                'message' => 'Gedung tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'building' => $building->name,
            'total_floors' => $building->total_floors,
            'floors' => $building->floors
        ]);
    }
}
