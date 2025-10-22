<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;

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
    public function create()
    {
        $floors = Floor::all();
        return view('rooms.create', compact('floors'));
    }

    /**
     * Simpan ruangan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:rooms,name',
            'floor_id' => 'required|exists:floors,id',
        ]);

        Room::create($validated);

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit ruangan.
     */
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        $floors = Floor::all();
        return view('rooms.edit', compact('room', 'floors'));
    }

    /**
     * Update data ruangan.
     */
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:rooms,name,' . $id,
            'floor_id' => 'required|exists:floors,id',
        ]);

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
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
}
