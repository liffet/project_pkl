<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;
use App\Exports\FloorsExport;
use Maatwebsite\Excel\Facades\Excel;

// ...

class FloorController extends Controller
{
    /**
     * Menampilkan daftar lantai.
     */
    public function show($id)
    {
        $floor = \App\Models\Floor::with('rooms', 'items')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $floor,
        ]);
    }

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
            'name' => 'required|string|max:255|unique:floors,name'
        ], [
            'name.unique' => 'Nama lantai sudah digunakan. Silakan gunakan nama lain.'
        ]);

        // Atau bisa menggunakan session warning
        $existingFloor = Floor::where('name', $request->name)->first();
        if ($existingFloor) {
            return redirect()->back()
                ->with('warning', 'Nama lantai "' . $request->name . '" sudah digunakan. Silakan gunakan nama lain.')
                ->withInput();
        }

        Floor::create($request->all());

        return redirect()->route('floors.index')
            ->with('success', 'Lantai berhasil ditambahkan!');
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
        $floor = Floor::findOrFail($id);

        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:floors,name,' . $id,
            ], [
                'name.required' => 'Nama lantai wajib diisi',
                'name.max' => 'Nama lantai maksimal 255 karakter',
                'name.unique' => 'Nama lantai sudah digunakan. Silakan gunakan nama lain.'
            ]);

            $floor->update([
                'name' => $request->name,
            ]);

            return redirect()->route('floors.index')
                ->with('success', 'Lantai berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error duplicate entry dari database
            if ($e->errorInfo[1] == 1062) {
                return redirect()->back()
                    ->with('warning', 'Nama lantai "' . $request->name . '" sudah digunakan. Silakan gunakan nama lain.')
                    ->withInput();
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan pada database.')
                ->withInput();
        }
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

    public function exportExcel()
    {
        return Excel::download(new FloorsExport, 'data_lantai.xlsx');
    }
}
