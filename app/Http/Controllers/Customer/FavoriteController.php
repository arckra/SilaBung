<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Item;
use App\Support\Geo;

class FavoriteController extends Controller
{
    /**
     * Daftar barang favorit milik customer yang login.
     */
    public function index()
    {
        $user   = auth()->user();
        $origin = $user->coordinates();

        $items = Item::with(['category', 'supplier'])
            ->whereHas('favoritedBy', fn ($q) => $q->where('user_id', $user->id))
            ->get()
            ->map(function (Item $item) use ($origin) {
                $item->distance_km = Geo::distanceKm($origin, $item->coordinates());
                return $item;
            });

        // Urutkan: yang punya jarak dulu, terdekat dulu.
        if ($origin) {
            $items = $items->sortBy(fn ($i) => $i->distance_km ?? PHP_INT_MAX)->values();
        } else {
            $items = $items->sortByDesc('created_at')->values();
        }

        return view('customer.favorites.index', compact('items'));
    }

    /**
     * Toggle favorit — kalau sudah ada, hapus. Kalau belum, tambah.
     * Return JSON supaya bisa dipanggil dari fetch() tanpa reload.
     */
    public function toggle(Item $item)
    {
        $user = auth()->user();

        $existing = Favorite::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'favorited' => false,
                'message'   => 'Dihapus dari favorit.',
            ]);
        }

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        return response()->json([
            'favorited' => true,
            'message'   => 'Ditambahkan ke favorit.',
        ]);
    }
}