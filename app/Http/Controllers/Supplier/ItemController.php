<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')
                    ->where('supplier_id', auth()->id())
                    ->latest()
                    ->paginate(12);

        return view('supplier.items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('supplier.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:120'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'quantity'    => ['required', 'integer', 'min:1', 'max:99999'],
            'unit'        => ['required', 'string', 'max:20'],
            'condition'   => ['required', 'in:Baik,Cukup,Perlu Perbaikan'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'city'        => ['nullable', 'string', 'max:80'],
            'address'     => ['nullable', 'string', 'max:255'],
            'latitude'    => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'   => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }
        unset($data['image']);

        $data['supplier_id'] = auth()->id();
        $data['status']      = 'available';

        // Fallback lokasi dari profil supplier kalau form kosong
        $supplier = auth()->user();
        $data['city']      ??= $supplier->city;
        $data['address']   ??= $supplier->address;
        $data['latitude']  ??= $supplier->latitude;
        $data['longitude'] ??= $supplier->longitude;

        Item::create($data);

        return redirect()
            ->route('supplier.items.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        abort_unless($item->supplier_id === auth()->id(), 403);

        $categories = Category::orderBy('name')->get();
        return view('supplier.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        abort_unless($item->supplier_id === auth()->id(), 403);
    
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:120'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'quantity'    => ['required', 'integer', 'min:0', 'max:99999'],
            'unit'        => ['required', 'string', 'max:20'],
            'condition'   => ['required', 'in:Baik,Cukup,Perlu Perbaikan'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'city'        => ['nullable', 'string', 'max:80'],
            'address'     => ['nullable', 'string', 'max:255'],
            'latitude'    => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'   => ['nullable', 'numeric', 'between:-180,180'],
        ]);
    
        // Handle upload foto baru
        if ($request->hasFile('image')) {
            // Hapus foto lama kalau ada
            if ($item->image_path && \Storage::disk('public')->exists($item->image_path)) {
                \Storage::disk('public')->delete($item->image_path);
            }
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }
        unset($data['image']);
    
        $item->fill($data);
        $item->refreshStatus();   // kalau quantity diubah jadi 0 → status jadi unavailable
        $item->save();
    
        return redirect()
            ->route('supplier.items.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }
    
    public function destroy(Item $item)
    {
        abort_unless($item->supplier_id === auth()->id(), 403);
        $item->delete();

        return back()->with('success', 'Barang berhasil dihapus.');
    }
}