<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;
use App\Services\TelegramService;
use Illuminate\Support\Str;
use App\Exports\ItemsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Building;


class ItemWebController extends Controller
{
    // ============================
    // 🔔 CEK PERBAIKAN
    // ============================
    public function cekPerbaikan(TelegramService $telegram)
    {
        $itemHampir = Item::where('replacement_date', '<=', now()->addDays(7))->get();
        $itemJatuhTempo = Item::where('replacement_date', '<=', now())->get();

        if ($itemHampir->count() > 0) {
            $telegram->sendMessage("⚠️ Ada {$itemHampir->count()} item yang hampir jatuh tempo perbaikan.");
        }

        if ($itemJatuhTempo->count() > 0) {
            $telegram->sendMessage("⏰ Ada {$itemJatuhTempo->count()} item yang sudah jatuh tempo perbaikan.");
        }

        return view('items.cek', compact('itemHampir', 'itemJatuhTempo'));
    }

    // ============================
    // 📋 INDEX
    // ============================
    public function index(Request $request)
{
    $buildings = Building::all(); // ← TAMBAHKAN INI
    $categories = Category::all();
    $rooms = Room::all();
    $floors = Floor::all();

    $itemsQuery = Item::with(['category', 'room', 'floor', 'building'])->latest(); // ← TAMBAH 'building'

    if ($request->filled('item_name')) {
        $itemsQuery->where('name', 'like', '%' . $request->item_name . '%');
    }

    if ($request->filled('category_id')) {
        $itemsQuery->where('category_id', $request->category_id);
    }

    if ($request->filled('status')) {
        $itemsQuery->where('status', $request->status);
    }

    // ← TAMBAHKAN FILTER GEDUNG
    if ($request->filled('building_id')) {
        $itemsQuery->where('building_id', $request->building_id);
    }

    if ($request->filled('room_id')) {
        $itemsQuery->where('room_id', $request->room_id);
    }

    if ($request->filled('floor_id')) {
        $itemsQuery->where('floor_id', $request->floor_id);
    }

    $items = $itemsQuery->paginate(10)->withQueryString();

    // Statistik
    $totalItems = Item::count();
    $activeItems = Item::where('status', 'active')->count();
    $inactiveItems = Item::where('status', 'inactive')->count();
    $needMaintenance = Item::whereDate('replacement_date', '<', now())->count();

    return view('items.index', compact(
        'items',
        'categories',
        'buildings', // ← TAMBAHKAN INI
        'rooms',
        'floors',
        'totalItems',
        'activeItems',
        'inactiveItems',
        'needMaintenance'
    ));
}
// ============================
// ➕ CREATE FORM
// ============================
public function create()
{
    $buildings = Building::all();
    $categories = Category::all();
    $rooms = Room::all();
    $floors = Floor::all();
    
    return view('items.create', compact('categories', 'rooms', 'floors', 'buildings'));
}

// ============================
// 💾 STORE
// ============================
public function store(Request $request)
{
    $request->validate([
        'building_id' => 'required|exists:buildings,id', 
        'category_id' => 'required|exists:categories,id',
        'room_id' => 'required|exists:rooms,id',
        'floor_id' => 'required|exists:floors,id',
        'code' => 'nullable|string|max:50|unique:items,code',
        'name' => 'required|string|max:255',
        'status' => 'required|in:active,inactive',
        'install_date' => 'required|date',
        'replacement_date' => 'required|date|after_or_equal:install_date',
        'photo' => 'nullable|image|max:2048',
    ]);

    $data = $request->only([
        'building_id',  
        'category_id',
        'room_id',
        'floor_id',
        'code',
        'name',
        'status',
        'install_date',
        'replacement_date'
    ]);

    $data['code'] = $data['code'] ?? 'ITM-' . strtoupper(Str::random(8));

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('items', 'public');
    }

    Item::create($data);

    return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan.');
}
public function edit($id)
{
    $item = Item::findOrFail($id);
    $buildings = Building::all(); // ← TAMBAHKAN INI
    $categories = Category::all();
    $rooms = Room::all();
    $floors = Floor::all();
    
    return view('items.edit', compact('item', 'categories', 'rooms', 'floors', 'buildings'));
}

   public function update(Request $request, $id)
{
    $item = Item::findOrFail($id);

    $request->validate([
        'building_id' => 'required|exists:buildings,id', // ← TAMBAHKAN INI
        'category_id' => 'required|exists:categories,id',
        'room_id' => 'required|exists:rooms,id',
        'floor_id' => 'required|exists:floors,id',
        'name' => 'required|string|max:255',
        'status' => 'required|in:active,inactive',
        'install_date' => 'required|date',
        'replacement_date' => 'required|date|after_or_equal:install_date',
        'photo' => 'nullable|image|max:2048',
    ]);

    $data = $request->only([
        'building_id',  // ← TAMBAHKAN INI
        'category_id',
        'room_id',
        'floor_id',
        'name',
        'status',
        'install_date',
        'replacement_date'
    ]);

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('items', 'public');
    }

    $item->update($data);

    return redirect()->route('items.index')->with('success', 'Item berhasil diperbarui.');
}

   

    // ============================
    // ❌ DELETE
    // ============================
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus.');
    }

    // ============================
    // 👁️ SHOW DETAIL
    // ============================
    public function show($id)
{
    $item = Item::with(['category', 'room', 'floor', 'building'])->findOrFail($id); // ← TAMBAH 'building'
    return view('items.show', compact('item'));
}

public function search(Request $request)
{
    $query = $request->input('q');

    $items = Item::with(['category', 'room', 'floor', 'building']) // ← TAMBAH 'building'
        ->where('name', 'like', "%$query%")
        ->orWhere('code', 'like', "%$query%")
        ->orWhereHas('room', fn($q) => $q->where('name', 'like', "%$query%"))
        ->orWhereHas('floor', fn($q) => $q->where('name', 'like', "%$query%"))
        ->orWhereHas('building', fn($q) => $q->where('name', 'like', "%$query%")) // ← TAMBAH INI
        ->get();

    return view('items.search', compact('items', 'query'));
}

    public function exportExcel(Request $request)
    {
        return Excel::download(new ItemsExport($request->all()), 'items.xlsx');
    }

}
