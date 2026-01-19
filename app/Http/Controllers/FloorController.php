<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;
use App\Exports\FloorsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Building;


class FloorController extends Controller
{

    public function index(Request $request)
    {
        $buildings = Building::all(); 
        $floors = [];

        if ($request->building_id) {
            $floors = Floor::where('building_id', $request->building_id)->get();
        }

        return view('floors.index', compact('buildings', 'floors'));
    }

    public function byBuilding($buildingId)
    {
        $building = \App\Models\Building::findOrFail($buildingId);
        $floors = Floor::where('building_id', $buildingId)->get();

        return view('floors.floors_by_building', compact('building', 'floors'));
    }


    public function show($id)
    {
        $floor = Floor::with('rooms', 'items')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $floor,
        ]);
    }



    public function exportExcel($building_id)
    {
        return Excel::download(new \App\Exports\FloorsByBuildingExport($building_id), 'lantai.xlsx');
    }

    public function floors(Request $request)
    {
        $buildings = Building::all(); 

        $floors = collect(); 

        if ($request->building_id) {
            $floors = Floor::where('building_id', $request->building_id)->get();
        }

        return view('floors.index', compact('buildings', 'floors'));
    }
}
