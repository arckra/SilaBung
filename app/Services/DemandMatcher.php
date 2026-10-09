<?php

namespace App\Services;

use App\Models\DemandList;
use App\Models\DemandListItem;
use App\Models\Item;
use App\Support\Geo;

class DemandMatcher
{
    /**
     * Jalankan matching untuk seluruh item dalam demand list.
     * Return array ringkas untuk ditampilkan.
     */
    public function match(DemandList $demandList): array
    {
        $customer = $demandList->customer;
        $origin   = $customer->coordinates();

        // Hapus alokasi lama (kalau re-match)
        foreach ($demandList->items as $line) {
            $line->allocations()->delete();
        }

        $result = [];

        foreach ($demandList->items as $line) {
            $allocations = $this->matchItem($line, $origin);

            $fulfilled = array_sum(array_column($allocations, 'quantity'));

            $line->fulfilled_quantity = $fulfilled;
            $line->status = match (true) {
                $fulfilled === 0                       => 'empty',
                $fulfilled >= $line->quantity          => 'full',
                default                                => 'partial',
            };
            $line->save();

            // Simpan alokasi ke database
            foreach ($allocations as $a) {
                $line->allocations()->create([
                    'supplier_id' => $a['supplier_id'],
                    'item_id'     => $a['item_id'],
                    'quantity'    => $a['quantity'],
                    'distance_km' => $a['distance_km'],
                    'selected'    => true,
                ]);
            }

            $result[] = [
                'line'        => $line->fresh(),
                'allocations' => $allocations,
                'fulfilled'   => $fulfilled,
            ];
        }

        // Update status demand list
        $demandList->status = 'matched';
        $demandList->save();

        return $result;
    }

    /**
     * Matching untuk satu baris kebutuhan.
     * Return array: [['supplier_id' => .., 'item_id' => .., 'quantity' => .., 'distance_km' => ..], ...]
     */
    protected function matchItem(DemandListItem $line, ?array $origin): array
    {
        $remaining = $line->quantity;
        $result    = [];

        // 1. Cari kandidat item: kategori cocok, stok > 0, dan (kalau ada search_term)
        //    nama/deskripsi mengandung kata itu.
        $query = Item::with(['supplier'])
            ->available()
            ->where('category_id', $line->category_id);

        if ($line->search_term) {
            $like = '%' . $line->search_term . '%';
            $query->where(fn ($q) => $q
                ->where('name', 'like', $like)
                ->orWhere('description', 'like', $like)
            );
        }

        $candidates = $query->get()->map(function (Item $item) use ($origin) {
            $item->distance_km = Geo::distanceKm($origin, $item->coordinates());
            return $item;
        });

        // 2. Urutkan: jarak terdekat dulu, lalu stok terbesar
        $candidates = $candidates->sortBy([
            fn ($a, $b) => ($a->distance_km ?? PHP_INT_MAX) <=> ($b->distance_km ?? PHP_INT_MAX),
            fn ($a, $b) => $b->quantity <=> $a->quantity,
        ])->values();

        // 3. Greedy fill
        foreach ($candidates as $item) {
            if ($remaining <= 0) break;

            $take = min($remaining, $item->quantity);
            if ($take <= 0) continue;

            $result[] = [
                'supplier_id' => $item->supplier_id,
                'item_id'     => $item->id,
                'quantity'    => $take,
                'distance_km' => $item->distance_km,
                'item'        => $item,
            ];

            $remaining -= $take;
        }

        return $result;
    }
}