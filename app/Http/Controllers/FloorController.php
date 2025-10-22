<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    /**
     * Menampilkan daftar lantai.
     */
    public function index()
    {
        $floors = Floor::all();
        return view('floors.index', compact('floors'));
    }

    /**
     * Menampilkan form tambah lantai.
     */
    public function create()
    {
        return view('floors.create');
    }

    /**
     * Simpan lantai baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Floor::create([
            'name' => $request->name,
        ]);

        return redirect()->route('floors.index')->with('success', 'Lantai berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit lantai.
     */
    public function edit($id)
    {
        $floor = Floor::findOrFail($id);
        return view('floors.edit', compact('floor'));
    }

    /**
     * Update data lantai.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $floor = Floor::findOrFail($id);
        $floor->update([
            'name' => $request->name,
        ]);

        return redirect()->route('floors.index')->with('success', 'Lantai berhasil diperbarui.');
    }

    /**
     * Hapus lantai.
     */
    public function destroy($id)
    {
        $floor = Floor::findOrFail($id);
        $floor->delete();

        return redirect()->route('floors.index')->with('success', 'Lantai berhasil dihapus.');
    }
}
