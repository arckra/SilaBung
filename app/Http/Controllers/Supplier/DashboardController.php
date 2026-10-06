<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalItems    = Item::where('supplier_id', $user->id)->count();
        $availableItems= Item::where('supplier_id', $user->id)->available()->count();
        $pendingReqs   = ItemRequest::whereHas('item', fn ($q) => $q->where('supplier_id', $user->id))
                                    ->where('status', 'pending')->count();
        $givenAway     = ItemRequest::whereHas('item', fn ($q) => $q->where('supplier_id', $user->id))
                                    ->whereIn('status', ['accepted', 'completed'])
                                    ->sum('quantity');

        $recentItems = Item::with('category')
                        ->where('supplier_id', $user->id)
                        ->latest()->take(5)->get();

        $recentRequests = ItemRequest::with(['item', 'customer'])
                            ->whereHas('item', fn ($q) => $q->where('supplier_id', $user->id))
                            ->latest()->take(5)->get();

        return view('supplier.dashboard', compact(
            'totalItems', 'availableItems', 'pendingReqs', 'givenAway',
            'recentItems', 'recentRequests'
        ));
    }
}