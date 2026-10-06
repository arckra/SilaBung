<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\ItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    /**
     * Daftar permintaan masuk ke barang supplier ini.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $allowed = ['pending', 'accepted', 'rejected', 'completed', 'cancelled', 'all'];
        if (! in_array($status, $allowed, true)) {
            $status = 'pending';
        }

        $query = ItemRequest::with(['item.category', 'customer'])
            ->whereHas('item', fn ($q) => $q->where('supplier_id', auth()->id()));

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->latest()->paginate(12)->withQueryString();

        // Hitungan untuk tab
        $counts = [
            'pending'   => ItemRequest::whereHas('item', fn ($q) => $q->where('supplier_id', auth()->id()))
                                ->where('status', 'pending')->count(),
            'accepted'  => ItemRequest::whereHas('item', fn ($q) => $q->where('supplier_id', auth()->id()))
                                ->where('status', 'accepted')->count(),
            'rejected'  => ItemRequest::whereHas('item', fn ($q) => $q->where('supplier_id', auth()->id()))
                                ->where('status', 'rejected')->count(),
        ];

        return view('supplier.requests.index', compact('requests', 'status', 'counts'));
    }

    /**
     * Terima permintaan — stok berkurang, status jadi accepted.
     */
    public function accept(ItemRequest $itemRequest)
    {
        $this->authorizeOwnership($itemRequest);

        if (! $itemRequest->isPending()) {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $item = $itemRequest->item;

        // Stok masih cukup?
        if ($itemRequest->quantity > $item->quantity) {
            return back()->with('error', "Stok {$item->name} tidak cukup. Tersisa {$item->quantity} {$item->unit}, diminta {$itemRequest->quantity}.");
        }

        // Transaksi: pastikan update stok & status request konsisten
        DB::transaction(function () use ($itemRequest, $item) {
            // Kurangi stok
            $item->quantity = $item->quantity - $itemRequest->quantity;

            // Kalau stok habis, ubah status otomatis
            $item->refreshStatus();
            $item->save();

            // Update status request
            $itemRequest->update([
                'status'       => 'accepted',
                'responded_at' => now(),
            ]);
        });

        return back()->with('success', "Permintaan diterima. Stok {$item->name} sekarang {$item->fresh()->quantity} {$item->unit}.");
    }

    /**
     * Tolak permintaan — stok tidak berubah.
     */
    public function reject(ItemRequest $itemRequest)
    {
        $this->authorizeOwnership($itemRequest);

        if (! $itemRequest->isPending()) {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $itemRequest->update([
            'status'       => 'rejected',
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Permintaan ditolak. Stok tidak berubah.');
    }

    /**
     * Tandai permintaan selesai (barang sudah diambil customer).
     */
    public function complete(ItemRequest $itemRequest)
    {
        $this->authorizeOwnership($itemRequest);

        if ($itemRequest->status !== 'accepted') {
            return back()->with('error', 'Hanya permintaan yang sudah diterima yang bisa diselesaikan.');
        }

        $itemRequest->update(['status' => 'completed']);

        return back()->with('success', 'Permintaan ditandai selesai. Terima kasih sudah berbagi!');
    }

    /**
     * Cek bahwa request ini memang milik supplier yang login.
     */
    protected function authorizeOwnership(ItemRequest $itemRequest): void
    {
        $supplierId = $itemRequest->item->supplier_id;
        abort_unless($supplierId === auth()->id(), 403, 'Kamu tidak berhak memproses permintaan ini.');
    }
}