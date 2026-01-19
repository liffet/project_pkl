<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Floor;

class FloorController extends Controller
{

    public function index()
{
    
    $buildingId = request()->query('building_id');

    
    if ($buildingId) {
        $floors = Floor::where('building_id', $buildingId)
            ->select('id', 'name', 'building_id')
            ->get();
    } else {
       
        $floors = [];
    }

    return response()->json([
        'success' => true,
        'data' => $floors
    ]);
}


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
