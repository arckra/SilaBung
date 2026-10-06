<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Support\Geo;

class ItemController extends Controller
{
    public function show(Item $item)
    {
        // Customer hanya boleh lihat item yang tersedia (atau miliknya sendiri, jika ada).
        abort_if($item->status !== 'available' && $item->quantity <= 0, 404);

        $item->load(['category', 'supplier']);

        $user   = auth()->user();
        $origin = $user->coordinates();

        $distanceKm = Geo::distanceKm($origin, $item->coordinates());
        $eta        = Geo::estimateMinutes($distanceKm);

        return view('customer.items.show', compact('item', 'distanceKm', 'eta'));
    }
}