<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    /**
     * Daftar permintaan milik customer yang login.
     */
    public function index()
    {
        $requests = ItemRequest::with(['item.category', 'item.supplier'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('customer.requests.index', compact('requests'));
    }

    /**
     * Ajukan permintaan baru untuk sebuah barang.
     */
    public function store(Request $request, Item $item)
    {
        // 1. Validasi dasar
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:9999'],
            'note'     => ['nullable', 'string', 'max:500'],
        ], [
            'quantity.required' => 'Jumlah barang wajib diisi.',
            'quantity.min'      => 'Jumlah minimal 1.',
            'quantity.integer'  => 'Jumlah harus berupa angka.',
        ]);

        $user = auth()->user();

        // 2. Customer tidak boleh meminta barang miliknya sendiri
        if ($item->supplier_id === $user->id) {
            return back()->with('error', 'Kamu tidak bisa mengajukan permintaan untuk barangmu sendiri.');
        }

        // 3. Barang harus tersedia
        if ($item->status !== 'available') {
            return back()->with('error', 'Barang ini sedang tidak tersedia.');
        }

        // 4. Jumlah tidak boleh melebihi stok
        if ($data['quantity'] > $item->quantity) {
            return back()->with('error', "Jumlah yang kamu minta ({$data['quantity']}) melebihi stok yang tersedia ({$item->quantity}).");
        }

        // 5. Cegah duplikat: satu customer hanya boleh punya 1 request pending
        //    untuk barang yang sama.
        $existing = ItemRequest::where('item_id', $item->id)
            ->where('customer_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return back()->with('error', 'Kamu sudah punya permintaan yang masih menunggu untuk barang ini.');
        }

        // 6. Simpan
        ItemRequest::create([
            'item_id'     => $item->id,
            'customer_id' => $user->id,
            'quantity'    => $data['quantity'],
            'note'        => $data['note'] ?? null,
            'status'      => 'pending',
        ]);

        return redirect()
            ->route('customer.requests.index')
            ->with('success', 'Permintaan berhasil dikirim. Tunggu konfirmasi dari supplier ya.');
    }

    /**
     * Batalkan permintaan yang masih pending.
     */
    public function cancel(ItemRequest $request)
    {
        abort_unless($request->customer_id === auth()->id(), 403);

        if (! $request->isPending()) {
            return back()->with('error', 'Hanya permintaan yang masih menunggu yang bisa dibatalkan.');
        }

        $request->update(['status' => 'cancelled']);

        return back()->with('success', 'Permintaan dibatalkan.');
    }
}