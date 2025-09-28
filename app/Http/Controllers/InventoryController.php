<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $items = Inventory::all();
        return view('inventory.index', compact('items'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'stok' => 'required|integer|min:0',
        ]);

        Inventory::create($request->only('nama_barang', 'stok', 'kategori'));

        return redirect()->route('inventory.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'stok' => 'required|integer|min:0',
        ]);

        $item = Inventory::findOrFail($id);
        $item->stok = $request->stok;
        $item->save();

        return redirect()->route('inventory.index')->with('success', 'Stok berhasil diperbarui');
    }

    public function delete($id)
    {
        Inventory::findOrFail($id)->delete();
        return redirect()->route('inventory.index')->with('success', 'Barang berhasil dihapus');
    }
}
