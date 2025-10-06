<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\TelegramService;
use Illuminate\Support\Str;

class ItemWebController extends Controller
{

    // ============================
    // WEB METHOD (BLADE)
    // ============================

    public function cekPerbaikan(TelegramService $telegram)
    {
        // Cari item yang hampir jatuh tempo (misal 7 hari lagi)
        $itemHampir = Item::where('tanggal_perbaikan', '<=', now()->addDays(7))->get();

        // Cari item yang sudah jatuh tempo
        $itemJatuhTempo = Item::where('tanggal_perbaikan', '<=', now())->get();

        // Kirim notif ke Telegram
        if ($itemHampir->count() > 0) {
            $telegram->sendMessage("⚠️ Ada {$itemHampir->count()} item yang hampir jatuh tempo perbaikan.");
        }

        if ($itemJatuhTempo->count() > 0) {
            $telegram->sendMessage("⏰ Ada {$itemJatuhTempo->count()} item yang sudah jatuh tempo perbaikan.");
        }

        // Tampilkan data di view juga kalau perlu
        return view('items.cek', compact('itemHampir', 'itemJatuhTempo'));
    }

    public function index()
    {
        $items = Item::with('category')->latest()->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'category_id'      => 'required|exists:categories,id',
        'code'             => 'nullable|string|max:50|unique:items,code',
        'name'             => 'required|string|max:255',
        'room'             => 'nullable|string|max:100',
        'floor'            => 'nullable|string|max:50',
        'status'           => 'required|in:active,inactive',   // ✅ validasi status
        'install_date'     => 'required|date',
        'replacement_date' => 'required|date|after_or_equal:install_date',
        'photo'            => 'nullable|image|max:2048',
    ]);

    $data = $request->only([
        'category_id','code','name','room','floor','status','install_date','replacement_date'
    ]);
    $data['code'] = $data['code'] ?? 'ITM-' . strtoupper(Str::random(8));

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('items', 'public');
    }

    Item::create($data);

    return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan');
}


    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        return view('items.edit', compact('item','categories'));
    }

        public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:255',
            'room'             => 'nullable|string|max:100',
            'floor'            => 'nullable|string|max:50',
            'status'           => 'required|in:active,inactive',   // ✅ validasi status
            'install_date'     => 'required|date',
            'replacement_date' => 'required|date|after_or_equal:install_date',
            'photo'            => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'category_id','name','room','floor','status','install_date','replacement_date'
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Item berhasil diupdate');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $items = \App\Models\Item::with('category')
            ->where('name', 'like', "%$query%")
            ->orWhere('code', 'like', "%$query%")
            ->orWhere('room', 'like', "%$query%")
            ->orWhere('floor', 'like', "%$query%")
            ->get();

        return view('items.search', compact('items', 'query'));
    }

    public function destroy($id)
    {
        Item::findOrFail($id)->delete();
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus');
    }

    public function show($id)
    {
        $item = Item::with('category')->findOrFail($id);
        return view('items.show', compact('item'));
    }
}
