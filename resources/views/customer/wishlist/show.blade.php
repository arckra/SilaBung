@extends('layouts.app')

@section('title', 'Hasil Pencarian')
@section('page-title', $wishlist->title)
@section('page-subtitle', 'Sistem sudah mencarikan supplier untuk kebutuhanmu')

@section('content')

@if (session('success'))
    <div class="mb-5 px-4 py-3 rounded-xl bg-[#DCFCE7] border border-green-200 text-[#166534] text-sm font-semibold">
        {{ session('success') }}
    </div>
@endif

@if ($wishlist->status === 'confirmed')
    <div class="mb-5 px-4 py-3.5 rounded-2xl bg-[#F0FDF4] border border-[#cfe6d5] flex gap-3">
        <div class="w-9 h-9 rounded-full bg-[#DCFCE7] grid place-items-center text-[#166534] shrink-0">
            <x-icon name="check" :size="16" />
        </div>
        <div class="text-sm">
            <div class="font-bold text-[#166534] mb-0.5">Wishlist sudah dikonfirmasi</div>
            <div class="text-[#647164] text-xs">
                Permintaan sudah dikirim ke supplier. Buka menu <b>Permintaan Saya</b> untuk memantau status.
            </div>
        </div>
    </div>
@endif

<div class="grid lg:grid-cols-[1fr_360px] gap-5">

    {{-- ============ KIRI: HASIL PER ITEM ============ --}}
    <div class="space-y-4">

        <div class="text-[11px] font-extrabold uppercase tracking-widest text-[#647164] px-1">
            Rincian per Barang
        </div>

        @foreach ($wishlist->items as $line)
            @php
                $statusMap = [
                    'full'    => ['label' => 'Terpenuhi',  'class' => 'bg-[#DCFCE7] text-[#166534]'],
                    'partial' => ['label' => 'Sebagian',   'class' => 'bg-amber-100 text-amber-700'],
                    'empty'   => ['label' => 'Tidak ada',  'class' => 'bg-red-50 text-red-700'],
                    'pending' => ['label' => 'Menunggu',   'class' => 'bg-[#F1F5F1] text-[#647164]'],
                ];
                $st = $statusMap[$line->status];
            @endphp

            <div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#E3EAE3] flex items-center gap-3 flex-wrap">
                    <div class="w-10 h-10 rounded-xl bg-[#DCFCE7] grid place-items-center text-lg">
                        {{ $line->category->emoji ?? '📦' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-extrabold tracking-tight text-sm">
                            {{ $line->category->name }}
                            @if ($line->search_term) <span class="text-[#647164] font-medium">· "{{ $line->search_term }}"</span> @endif
                        </div>
                        <div class="text-[11px] text-[#647164]">
                            Butuh {{ $line->quantity }} {{ $line->unit }} · Terpenuhi {{ $line->fulfilled_quantity }} {{ $line->unit }}
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $st['class'] }}">
                        {{ $st['label'] }}
                    </span>
                </div>

                @if ($line->allocations->isEmpty())
                    <div class="p-5 text-sm text-[#647164] text-center">
                        Tidak ada supplier yang punya stok untuk barang ini.
                    </div>
                @else
                    <div class="divide-y divide-[#E3EAE3]">
                        @foreach ($line->allocations as $a)
                            <div class="px-5 py-3.5 flex items-center gap-3" data-allocation="{{ $a->id }}">
                                <input type="checkbox"
                                       onchange="toggleAllocation({{ $a->id }}, this)"
                                       {{ $a->selected ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-[#E3EAE3] text-[#16A34A] focus:ring-[#16A34A]/30 shrink-0">
                                <div class="w-9 h-9 rounded-full bg-[#166534] text-white grid place-items-center text-[11px] font-extrabold shrink-0">
                                    {{ strtoupper(substr($a->supplier->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-[13px] font-bold truncate">{{ $a->supplier->name }}</div>
                                    <div class="text-[11px] text-[#647164] truncate">
                                        {{ $a->item->name }}
                                        @if ($a->distance_km !== null)
                                            · ± {{ number_format($a->distance_km, 1, ',', '.') }} km
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="font-extrabold text-sm">{{ $a->quantity }}</div>
                                    <div class="text-[10px] text-[#647164]">{{ $a->item->unit }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        {{-- Action --}}
        @if ($wishlist->status !== 'confirmed')
            <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5 flex flex-wrap items-center justify-between gap-3">
                <div class="text-xs text-[#647164]">
                    Suka hasilnya? Konfirmasi untuk mengirim permintaan ke semua supplier terpilih.
                </div>
                <form method="POST" action="{{ route('customer.wishlist.confirm', $wishlist) }}">
                    @csrf
                    <button type="submit"
                            class="h-11 px-6 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition flex items-center gap-2">
                        <x-icon name="check" :size="15" />
                        Konfirmasi Wishlist
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- ============ KANAN: RINGKASAN PER SUPPLIER ============ --}}
    <aside class="space-y-4">

        <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
            <div class="text-[11px] font-extrabold uppercase tracking-widest text-[#647164] mb-3">
                Ringkasan Supplier
            </div>

            @if (empty($bySupplier))
                <p class="text-xs text-[#647164] text-center py-6">
                    Belum ada supplier yang cocok.
                </p>
            @else
                <div class="space-y-3">
                    @foreach ($bySupplier as $sid => $group)
                        <div class="border border-[#E3EAE3] rounded-xl p-3.5">
                            <div class="flex items-center gap-2.5 mb-2.5">
                                <div class="w-9 h-9 rounded-full bg-[#166534] text-white grid place-items-center text-[11px] font-extrabold shrink-0">
                                    {{ strtoupper(substr($group['supplier']->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-[13px] font-bold truncate">{{ $group['supplier']->name }}</div>
                                    @if ($group['distance'] !== null)
                                        <div class="text-[10.5px] text-[#647164]">
                                            ± {{ number_format($group['distance'], 1, ',', '.') }} km
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-1.5 text-[12px] text-[#647164]">
                                @foreach ($group['items'] as $it)
                                    <div class="flex justify-between gap-2">
                                        <span class="truncate">{{ $it['name'] }}</span>
                                        <span class="font-bold text-[#172117] shrink-0">
                                            {{ $it['quantity'] }} {{ $it['unit'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
            <div class="text-[11px] font-extrabold uppercase tracking-widest text-[#647164] mb-2">
                Cara Kerja
            </div>
            <ol class="space-y-2 text-xs text-[#647164] leading-relaxed list-decimal list-inside">
                <li>Sistem pilih supplier terdekat dulu, lalu yang stoknya paling banyak.</li>
                <li>Kalau supplier pertama tidak cukup, sistem lanjut ke supplier berikutnya.</li>
                <li>Kamu bisa tidak centang supplier yang tidak diinginkan.</li>
                <li>Setelah dikonfirmasi, semua supplier terpilih menerima permintaanmu.</li>
            </ol>
        </div>
    </aside>
</div>

@endsection

@push('scripts')
<script>
async function toggleAllocation(id, cb) {
    const original = cb.checked;
    cb.disabled = true;

    try {
        const res = await fetch(`/customer/allocations/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!res.ok) throw new Error('Gagal');
        const data = await res.json();
        cb.checked = data.selected;
    } catch (e) {
        cb.checked = original;
        alert('Gagal mengubah pilihan. Coba lagi.');
    } finally {
        cb.disabled = false;
    }
}
</script>
@endpush