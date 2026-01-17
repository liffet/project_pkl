<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;

class BuildingController extends Controller
{

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Building::all()
        ]);
    }


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
