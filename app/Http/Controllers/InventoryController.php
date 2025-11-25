<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function index()
    {
        $items = Inventory::all();
        return view('inventory.index', compact('items'));
    }

    // ========== STORE ==========
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|max:255',
            'stok'        => 'required|integer|min:0|max:9999',
            'kategori'    => 'nullable|string',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambarPath = null;

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('uploads', 'public');
        }

        Inventory::create([
            'nama_barang' => $request->nama_barang,
            'stok'        => $request->stok,
            'kategori'    => $request->kategori,
            'gambar'      => $gambarPath,
        ]);

        return redirect()->route('inventory.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    // ========== UPDATE ==========
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string',
            'stok'        => 'required|integer|min:0|max:9999',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $item = Inventory::findOrFail($id);

        if ($request->hasFile('gambar')) {

            if ($item->gambar && Storage::disk('public')->exists($item->gambar)) {
                Storage::disk('public')->delete($item->gambar);
            }

            $item->gambar = $request->file('gambar')->store('uploads', 'public');
        }

        $item->nama_barang = $request->nama_barang;
        $item->kategori    = $request->kategori;
        $item->stok        = $request->stok;

        $item->save();

        return redirect()->route('inventory.index')
            ->with('success', 'Data Daftar Barang Berhasil Diperbarui');
    }

    // ========== DESTROY ==========
    public function destroy($id)
    {
        $item = Inventory::findOrFail($id);

        if ($item->gambar && Storage::disk('public')->exists($item->gambar)) {
            Storage::disk('public')->delete($item->gambar);
        }

        $item->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Barang berhasil dihapus');
    }
}
