<?php

namespace App\Http\Controllers;
use App\Models\Floor;
use Illuminate\Http\Request;
use App\Exports\FloorsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Building;


class FloorController extends Controller
{
    /**
     * Tampilkan semua lantai (untuk view web admin)
     */
public function index(Request $request)
{
    $buildings = Building::all(); // untuk dropdown gedung
    $floors = [];

    if ($request->building_id) {
        $floors = Floor::where('building_id', $request->building_id)->get();
    }

    return view('floors.index', compact('buildings', 'floors'));
}


    /**
     * API: Tampilkan lantai berdasarkan gedung.
     */
 public function byBuilding($buildingId)
{
    $building = \App\Models\Building::findOrFail($buildingId);
    $floors = Floor::where('building_id', $buildingId)->get();

    return view('floors.floors_by_building', compact('building', 'floors'));
}


    /**
     * API: Detail lantai + rooms + items
     */
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

}
