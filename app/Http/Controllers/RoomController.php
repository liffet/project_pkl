<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
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
        $rooms = Room::with('floor')->get();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Menampilkan form tambah ruangan.
     */
  // RoomController.php

public function create()
{
    $floors = Floor::all();
    return view('rooms.create', compact('floors'));
}

public function store(Request $request)
{
    try {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Validasi unique: nama ruangan harus unik per lantai
                Rule::unique('rooms')->where(function ($query) use ($request) {
                    return $query->where('floor_id', $request->floor_id);
                })
            ],
            'floor_id' => 'required|exists:floors,id'
        ], [
            'name.required' => 'Nama ruangan wajib diisi',
            'name.max' => 'Nama ruangan maksimal 255 karakter',
            'name.unique' => 'Nama ruangan sudah digunakan di lantai ini. Silakan gunakan nama lain.',
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
        // Tangkap error duplicate entry dari database
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

    /**
     * Menampilkan form edit ruangan.
     */
 // RoomController.php

public function edit($id)
{
    $room = Room::with('floor')->findOrFail($id);
    $floors = Floor::all();
    return view('rooms.edit', compact('room', 'floors'));
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
                // Validasi unique: nama ruangan harus unik per lantai, kecuali record ini sendiri
                Rule::unique('rooms')->where(function ($query) use ($request, $id) {
                    return $query->where('floor_id', $request->floor_id)
                                 ->where('id', '!=', $id);
                })
            ],
            'floor_id' => 'required|exists:floors,id'
        ], [
            'name.required' => 'Nama ruangan wajib diisi',
            'name.max' => 'Nama ruangan maksimal 255 karakter',
            'name.unique' => 'Nama ruangan sudah digunakan di lantai ini. Silakan gunakan nama lain.',
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
        // Tangkap error duplicate entry dari database
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

    /**
     * Hapus ruangan.
     */
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
