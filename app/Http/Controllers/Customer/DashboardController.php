<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Support\Geo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user   = auth()->user();
        $origin = $user->coordinates();

        // ----- Barang rekomendasi -----
        $query = Item::with(['category', 'supplier'])->available();
        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->query('category')));
        }

        $items = $query->latest()->take(8)->get()->map(function ($item) use ($origin) {
            $item->distance_km = Geo::distanceKm($origin, $item->coordinates());
            return $item;
        });

        if ($origin) {
            $items = $items->sortBy(fn ($i) => $i->distance_km ?? PHP_INT_MAX)->values();
        }

        // ----- Statistik pengajuan -----
        $stats = [
            'aktif'    => ItemRequest::where('customer_id', $user->id)->where('status', 'pending')->count(),
            'diproses' => ItemRequest::where('customer_id', $user->id)->where('status', 'accepted')->count(),
            'selesai'  => ItemRequest::where('customer_id', $user->id)->where('status', 'completed')->count(),
        ];

        // ----- Favorit preview -----
        $favorites = Item::with(['category', 'supplier'])
            ->whereHas('favoritedBy', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->take(3)
            ->get();

        // ----- Aktivitas terbaru -----
        $activities = $this->buildActivities($user);

        // ----- Kategori populer -----
        $popularCategories = Category::withCount(['items' => fn ($q) => $q->available()])
            ->orderByDesc('items_count')
            ->take(4)
            ->get();

        return view('customer.dashboard', [
            'categories'        => Category::orderBy('name')->get(),
            'items'             => $items,
            'stats'             => $stats,
            'favorites'         => $favorites,
            'activities'        => $activities,
            'popularCategories' => $popularCategories,
            'term'              => '',
            'catSlug'           => $request->query('category'),
            'condition'         => null,
        ]);
    }

    public function search(Request $request)
    {
        return view('customer.search', $this->buildSearchData($request));
    }

    protected function buildSearchData(Request $request): array
    {
        $user      = auth()->user();
        $term      = trim((string) $request->query('q', ''));
        $catSlug   = $request->query('category');
        $condition = $request->query('condition');

        $query = Item::with(['category', 'supplier'])->available()->search($term ?: null);

        if ($catSlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $catSlug));
        }
        if ($condition && in_array($condition, ['Baik', 'Cukup', 'Perlu Perbaikan'], true)) {
            $query->where('condition', $condition);
        }

        $origin = $user->coordinates();

        $items = $query->get()->map(function (Item $item) use ($origin) {
            $item->distance_km = Geo::distanceKm($origin, $item->coordinates());
            return $item;
        });

        if ($origin) {
            $items = $items->sortBy(fn ($i) => $i->distance_km ?? PHP_INT_MAX)->values();
        } else {
            $items = $items->sortByDesc('created_at')->values();
        }

        return [
            'categories' => Category::orderBy('name')->get(),
            'items'      => $items,
            'term'       => $term,
            'catSlug'    => $catSlug,
            'condition'  => $condition,
        ];
    }

    /** Bangun timeline aktivitas customer. */
    protected function buildActivities($user): array
    {
        $activities = [];

        // Ambil 5 request terbaru
        $requests = ItemRequest::with('item')
            ->where('customer_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        foreach ($requests as $req) {
            $map = [
                'pending'   => ['tone' => 'amber',  'icon' => 'clock',       'title' => 'Menunggu konfirmasi'],
                'accepted'  => ['tone' => 'green',  'icon' => 'check',       'title' => 'Pengajuan diterima'],
                'rejected'  => ['tone' => 'red',    'icon' => 'x',           'title' => 'Pengajuan ditolak'],
                'completed' => ['tone' => 'blue',   'icon' => 'check',       'title' => 'Transaksi selesai'],
                'cancelled' => ['tone' => 'gray',   'icon' => 'x',           'title' => 'Pengajuan dibatalkan'],
            ];
            $m = $map[$req->status] ?? $map['pending'];

            $activities[] = [
                'tone'  => $m['tone'],
                'icon'  => $m['icon'],
                'title' => $m['title'],
                'meta'  => $req->item->name . ' · ' . $req->quantity . ' ' . $req->item->unit,
                'at'    => $req->updated_at ?? $req->created_at,
            ];
        }

        usort($activities, fn ($a, $b) => $b['at'] <=> $a['at']);

        return array_slice($activities, 0, 4);
    }
}