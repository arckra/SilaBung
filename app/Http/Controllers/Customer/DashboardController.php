<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Support\Geo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('customer.dashboard', $this->buildSearchData($request));
    }

    public function search(Request $request)
    {
        return view('customer.search', $this->buildSearchData($request));
    }

    /**
     * Kumpulkan data untuk dashboard / halaman cari customer:
     * daftar kategori, item terfilter, dan jarak dari user.
     */
    protected function buildSearchData(Request $request): array
    {
        $user     = auth()->user();
        $term     = trim((string) $request->query('q', ''));
        $catSlug  = $request->query('category');
        $condition= $request->query('condition');

        $query = Item::with(['category', 'supplier'])->available()
                     ->search($term ?: null);

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

        // Urutkan: kalau ada koordinat → jarak terdekat; kalau tidak → terbaru.
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
}