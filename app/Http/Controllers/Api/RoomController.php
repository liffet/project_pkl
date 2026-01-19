<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;

class RoomController extends Controller
{

    public function index()
    {
        $rooms = Room::with('floor')->get();

        return response()->json([
            'success' => true,
            'data' => $rooms,
        ]);
    }

   
    public function show($id)
    {
        $room = Room::with('floor')->find($id);

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $room,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:rooms,name',
            'floor_id' => 'required|exists:floors,id',
        ]);

        $room = Room::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ruangan berhasil ditambahkan',
            'data' => $room,
        ], 201);
    }

 
    public function update(Request $request, $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:rooms,name,' . $id,
            'floor_id' => 'required|exists:floors,id',
        ]);

        $room->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ruangan berhasil diperbarui',
            'data' => $room,
        ]);
    }

 
    public function destroy($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan tidak ditemukan',
            ], 404);
        }

        $room->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ruangan berhasil dihapus',
        ]);
    }
}
