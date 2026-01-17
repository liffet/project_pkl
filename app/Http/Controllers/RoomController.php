<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use App\Models\Building; 
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RoomsExport;

class RoomController extends Controller
{
    /**
     * Menampilkan daftar semua ruangan.
     */
    public function index()
    {
        $rooms = Room::with(['floor.building'])->get();
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $buildings = Building::all();
        $floors = Floor::all();
        return view('rooms.create', compact('buildings', 'floors'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('rooms')->where(function ($query) use ($request) {
                        return $query->where('floor_id', $request->floor_id);
                    })
                ],
                'building_id' => 'required|exists:buildings,id',
                'floor_id' => 'required|exists:floors,id'
            ], [
                'name.required' => 'Nama ruangan wajib diisi',
                'name.max' => 'Nama ruangan maksimal 255 karakter',
                'name.unique' => 'Nama ruangan sudah digunakan di lantai ini. Silakan gunakan nama lain.',
                'building_id.required' => 'Gedung wajib dipilih',
                'building_id.exists' => 'Gedung yang dipilih tidak valid',
                'floor_id.required' => 'Lantai wajib dipilih',
                'floor_id.exists' => 'Lantai yang dipilih tidak valid'
            ]);

            $room = Room::create([
                'name' => $request->name,
                'floor_id' => $request->floor_id
            ]);

            return redirect()->route('rooms.index')
                ->with('success', 'Ruangan berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Illuminate\Database\QueryException $e) {
        
            if ($e->errorInfo[1] == 1062) {
                $floor = Floor::find($request->floor_id);
                $floorName = $floor ? $floor->name : 'lantai yang dipilih';
                
                return redirect()->back()
                    ->with('warning', 'Nama ruangan "' . $request->name . '" sudah digunakan di ' . $floorName . '. Silakan gunakan nama lain.')
                    ->withInput();
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan pada database.')
                ->withInput();
        }
    }


    public function edit($id)
    {
        $room = Room::with('floor.building')->findOrFail($id); // ← TAMBAHKAN RELASI BUILDING
        $buildings = Building::all(); 
        $floors = Floor::all();
        return view('rooms.edit', compact('room', 'buildings', 'floors'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('rooms')->where(function ($query) use ($request, $id) {
                        return $query->where('floor_id', $request->floor_id)
                                     ->where('id', '!=', $id);
                    })
                ],
                'building_id' => 'required|exists:buildings,id',
                'floor_id' => 'required|exists:floors,id'
            ], [
                'name.required' => 'Nama ruangan wajib diisi',
                'name.max' => 'Nama ruangan maksimal 255 karakter',
                'name.unique' => 'Nama ruangan sudah digunakan di lantai ini. Silakan gunakan nama lain.',
                'building_id.required' => 'Gedung wajib dipilih', 
                'building_id.exists' => 'Gedung yang dipilih tidak valid',
                'floor_id.required' => 'Lantai wajib dipilih',
                'floor_id.exists' => 'Lantai yang dipilih tidak valid'
            ]);

            $room->update([
                'name' => $request->name,
                'floor_id' => $request->floor_id
            ]);

            return redirect()->route('rooms.index')
                ->with('success', 'Ruangan berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                $floor = Floor::find($request->floor_id);
                $floorName = $floor ? $floor->name : 'lantai yang dipilih';
                
                return redirect()->back()
                    ->with('warning', 'Nama ruangan "' . $request->name . '" sudah digunakan di ' . $floorName . '. Silakan gunakan nama lain.')
                    ->withInput();
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan pada database.')
                ->withInput();
        }
    }


    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil dihapus.');
    }

    public function exportExcel()
    {
        return Excel::download(new RoomsExport, 'rooms.xlsx');
    }
}