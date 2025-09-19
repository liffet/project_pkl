<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return response()->json(Item::with('category')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'install_date' => 'required|date',
            'replacement_date' => 'required|date|after_or_equal:install_date',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['category_id','name','install_date','replacement_date']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('items', 'public');
        }

        $item = Item::create($data);

        return response()->json($item, 201);
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'install_date' => 'sometimes|date',
            'replacement_date' => 'sometimes|date',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['category_id','name','install_date','replacement_date']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('items', 'public');
        }

        $item->update($data);

        return response()->json($item);
    }

    public function destroy($id)
    {
        Item::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
 
    // Barang yang hampir expired (misal H-7)
    public function soonExpired()
    {
        $items = Item::whereDate('replacement_date', '<=', now()->addDays(7))
                     ->with('category')
                     ->get();

        return response()->json($items);
    }
}
