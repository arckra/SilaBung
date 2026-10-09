<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ----- Barang saya (preview) -----
        $items = Item::with('category')
            ->where('supplier_id', $user->id)
            ->latest()
            ->take(8)
            ->get();

        // ----- Statistik -----
        $totalItems     = Item::where('supplier_id', $user->id)->count();
        $availableItems = Item::where('supplier_id', $user->id)->available()->count();
        $givenAway      = ItemRequest::whereHas('item', fn ($q) => $q->where('supplier_id', $user->id))
                            ->whereIn('status', ['accepted', 'completed'])
                            ->sum('quantity');

        // ----- Statistik permintaan masuk -----
        $requestStats = [
            'menunggu' => ItemRequest::whereHas('item', fn ($q) => $q->where('supplier_id', $user->id))
                                ->where('status', 'pending')->count(),
            'diterima' => ItemRequest::whereHas('item', fn ($q) => $q->where('supplier_id', $user->id))
                                ->where('status', 'accepted')->count(),
            'selesai'  => ItemRequest::whereHas('item', fn ($q) => $q->where('supplier_id', $user->id))
                                ->where('status', 'completed')->count(),
        ];

        // ----- Stok hampir habis -----
        $lowStock = Item::with('category')
            ->where('supplier_id', $user->id)
            ->where('quantity', '>', 0)
            ->where('quantity', '<=', 3)
            ->orderBy('quantity')
            ->take(3)
            ->get();

        // ----- Aktivitas terbaru -----
        $activities = $this->buildActivities($user);

        // ----- Kategori populer -----
        $popularCategories = Category::withCount(['items' => fn ($q) => $q->available()])
            ->orderByDesc('items_count')
            ->take(4)
            ->get();

        return view('supplier.dashboard', compact(
            'items',
            'totalItems',
            'availableItems',
            'givenAway',
            'requestStats',
            'lowStock',
            'activities',
            'popularCategories'
        ));
    }

    protected function buildActivities($user): array
    {
        $activities = [];

        // Permintaan terbaru
        $requests = ItemRequest::with(['item', 'customer'])
            ->whereHas('item', fn ($q) => $q->where('supplier_id', $user->id))
            ->latest()
            ->take(3)
            ->get();

        foreach ($requests as $req) {
            $map = [
                'pending'   => ['tone' => 'amber', 'icon' => 'clock', 'title' => 'Permintaan baru masuk'],
                'accepted'  => ['tone' => 'green', 'icon' => 'check', 'title' => 'Permintaan diterima'],
                'rejected'  => ['tone' => 'red',   'icon' => 'x',     'title' => 'Permintaan ditolak'],
                'completed' => ['tone' => 'blue',  'icon' => 'check', 'title' => 'Transaksi selesai'],
            ];
            $m = $map[$req->status] ?? $map['pending'];

            $activities[] = [
                'tone'  => $m['tone'],
                'icon'  => $m['icon'],
                'title' => $m['title'],
                'meta'  => $req->customer->name . ' · ' . $req->quantity . ' ' . $req->item->unit,
                'at'    => $req->updated_at ?? $req->created_at,
            ];
        }

        // Barang baru ditambahkan
        $newItems = Item::where('supplier_id', $user->id)->latest()->take(2)->get();
        foreach ($newItems as $it) {
            $activities[] = [
                'tone'  => 'green',
                'icon'  => 'plus',
                'title' => 'Barang baru ditambahkan',
                'meta'  => $it->name . ' · ' . $it->quantity . ' ' . $it->unit,
                'at'    => $it->created_at,
            ];
        }

        usort($activities, fn ($a, $b) => $b['at'] <=> $a['at']);

        return array_slice($activities, 0, 4);
    }
}