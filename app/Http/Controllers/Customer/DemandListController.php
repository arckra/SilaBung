<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DemandAllocation;
use App\Models\DemandList;
use App\Models\ItemRequest;
use App\Services\DemandMatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ChatGroup;
use App\Models\Conversation;

class DemandListController extends Controller
{
    /* ============ FORM BUAT WISHLIST ============ */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('customer.wishlist.create', compact('categories'));
    }

    /* ============ SIMPAN + JALANKAN MATCHING ============ */
    public function store(Request $request, DemandMatcher $matcher)
    {
        $data = $request->validate([
            'title'                => ['nullable', 'string', 'max:120'],
            'note'                 => ['nullable', 'string', 'max:500'],
            'lines'                => ['required', 'array', 'min:1', 'max:20'],
            'lines.*.category_id'  => ['required', 'exists:categories,id'],
            'lines.*.search_term'  => ['nullable', 'string', 'max:80'],
            'lines.*.quantity'     => ['required', 'integer', 'min:1', 'max:9999'],
            'lines.*.unit'         => ['required', 'in:pcs,kg,liter,ikat,set,karung'],
        ], [
            'lines.required' => 'Tambahkan minimal 1 barang yang kamu butuhkan.',
            'lines.*.category_id.required' => 'Pilih kategori untuk setiap barang.',
            'lines.*.quantity.min' => 'Jumlah minimal 1.',
        ]);

        $list = DB::transaction(function () use ($data, $request) {
            $list = DemandList::create([
                'customer_id' => auth()->id(),
                'title'       => $data['title'] ?? 'Kebutuhan ' . now()->format('d M Y'),
                'note'        => $data['note'] ?? null,
                'status'      => 'draft',
            ]);

            foreach ($data['lines'] as $line) {
                $list->items()->create([
                    'category_id' => $line['category_id'],
                    'search_term' => $line['search_term'] ?? null,
                    'quantity'    => $line['quantity'],
                    'unit'        => $line['unit'],
                ]);
            }

            return $list;
        });

        // Jalankan matching
        $matcher->match($list);

        return redirect()->route('customer.wishlist.show', $list)
            ->with('success', 'Sistem sudah mencarikan supplier terbaik untukmu.');
    }

    /* ============ HALAMAN HASIL MATCHING ============ */
    public function show(DemandList $wishlist)
    {
        abort_unless($wishlist->customer_id === auth()->id(), 403);

        $wishlist->load([
            'items.category',
            'items.allocations.supplier',
            'items.allocations.item',
        ]);

        // Kelompokkan alokasi per supplier (yang selected saja)
        $bySupplier = [];
        foreach ($wishlist->items as $line) {
            foreach ($line->allocations->where('selected', true) as $a) {
                $sid = $a->supplier_id;
                if (! isset($bySupplier[$sid])) {
                    $bySupplier[$sid] = [
                        'supplier' => $a->supplier,
                        'items'    => [],
                        'distance' => $a->distance_km,
                    ];
                }
                $bySupplier[$sid]['items'][] = [
                    'name'     => $a->item->name,
                    'category' => $a->item->category->name,
                    'quantity' => $a->quantity,
                    'unit'     => $a->item->unit,
                ];
            }
        }

        return view('customer.wishlist.show', compact('wishlist', 'bySupplier'));
    }

    /* ============ TOGGLE ALOKASI ============ */
    public function toggleAllocation(DemandAllocation $allocation)
    {
        abort_unless(
            $allocation->demandListItem->demandList->customer_id === auth()->id(),
            403
        );

        $allocation->selected = ! $allocation->selected;
        $allocation->save();

        // Re-hitung fulfilled_quantity untuk baris ini
        $line = $allocation->demandListItem;
        $line->fulfilled_quantity = $line->allocations()->where('selected', true)->sum('quantity');
        $line->status = match (true) {
            $line->fulfilled_quantity === 0                       => 'empty',
            $line->fulfilled_quantity >= $line->quantity          => 'full',
            default                                                => 'partial',
        };
        $line->save();

        return response()->json([
            'selected'  => $allocation->selected,
            'fulfilled' => $line->fulfilled_quantity,
            'status'    => $line->status,
        ]);
    }

    /* ============ KONFIRMASI → BUAT CHAT + REQUEST ============ */
    public function confirm(DemandList $wishlist)
    {
        abort_unless($wishlist->customer_id === auth()->id(), 403);
    
        if ($wishlist->status === 'confirmed') {
            return redirect()->route('customer.wishlist.show', $wishlist)
                ->with('error', 'Wishlist ini sudah pernah dikonfirmasi.');
        }
    
        $wishlist->load(['items.allocations.item', 'items.category']);
    
        $supplierIds = [];
        $chatPayload = null;
    
        DB::transaction(function () use ($wishlist, &$supplierIds, &$chatPayload) {
            foreach ($wishlist->items as $line) {
                foreach ($line->allocations->where('selected', true) as $a) {
                    ItemRequest::create([
                        'item_id'     => $a->item_id,
                        'customer_id' => auth()->id(),
                        'quantity'    => $a->quantity,
                        'status'      => 'pending',
                        'note'        => "Dari wishlist: {$wishlist->title}",
                    ]);
    
                    $supplierIds[] = $a->supplier_id;
                }
            }
    
            $supplierIds = array_values(array_unique($supplierIds));
    
            $wishlist->update([
                'status'       => 'confirmed',
                'confirmed_at' => now(),
            ]);
    
            // ============ BIKIN CHAT ============
            if (count($supplierIds) === 1) {
                // 1 supplier → DM biasa
                $conversation = Conversation::firstOrCreate(
                    [
                        'customer_id' => auth()->id(),
                        'supplier_id' => $supplierIds[0],
                    ],
                    ['item_id' => null]
                );
                $conversation->update(['last_message_at' => now()]);
    
                $chatPayload = ['type' => 'dm', 'id' => $conversation->id];
            } elseif (count($supplierIds) >= 2) {
                // 2+ supplier → grup baru
                $group = ChatGroup::create([
                    'demand_list_id' => $wishlist->id,
                    'created_by'     => auth()->id(),
                    'name'           => 'Wishlist: ' . $wishlist->title,
                    'last_message_at'=> now(),
                ]);
    
                $memberIds = array_merge([auth()->id()], $supplierIds);
                $group->members()->attach($memberIds, ['last_read_at' => now()]);
    
                $chatPayload = ['type' => 'group', 'id' => $group->id];
            }
        });
    
        if (! $chatPayload) {
            return redirect()->route('customer.wishlist.show', $wishlist)
                ->with('error', 'Tidak ada supplier yang dipilih. Konfirmasi dibatalkan.');
        }
    
        // Redirect ke chat
        if ($chatPayload['type'] === 'dm') {
            return redirect()->route('chat.index', ['c' => $chatPayload['id']])
                ->with('success', 'Wishlist dikonfirmasi. Lanjutkan koordinasi dengan supplier.');
        }
    
        return redirect()->route('chat.group', $chatPayload['id'])
            ->with('success', 'Wishlist dikonfirmasi. Grup chat dengan semua supplier sudah dibuat.');
    }

    /* ============ DAFTAR WISHLIST ============ */
    public function index()
    {
        $wishlists = DemandList::with('items.category')
            ->where('customer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('customer.wishlist.index', compact('wishlists'));
    }

    /* ============ HAPUS ============ */
    public function destroy(DemandList $wishlist)
    {
        abort_unless($wishlist->customer_id === auth()->id(), 403);
        $wishlist->delete();

        return redirect()->route('customer.wishlist.index')
            ->with('success', 'Wishlist dihapus.');
    }
}