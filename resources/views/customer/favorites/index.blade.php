@extends('layouts.app')

@section('title', 'Favorit')
@section('page-title', 'Favorit')
@section('page-subtitle', 'Barang yang kamu simpan untuk dilihat nanti')

@section('content')

@if ($items->isEmpty())
    <div class="bg-white border border-[#E3EAE3] rounded-2xl">
        <x-empty-state
            icon="heart"
            title="Belum ada barang favorit"
            description="Klik ikon hati di barang mana pun untuk menyimpannya di sini."
            actionLabel="Cari Barang"
            :actionHref="route('customer.search')" />
    </div>
@else

    <div class="mb-3">
        <p class="text-xs text-[#647164]">
            <b class="text-[#172117]" id="favCount">{{ $items->count() }}</b> barang tersimpan
        </p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
        @foreach ($items as $item)
            @php
                $cat = $item->category;
                $bg = [
                    'kardus'     => '#FEF3C7', 'plastik'   => '#CFFAFE', 'botol'    => '#DBEAFE',
                    'kertas'     => '#F1F5F9', 'kaca'      => '#CCFBF1', 'kayu'     => '#FDE68A',
                    'logam'      => '#E2E8F0', 'elektronik'=> '#EDE9FE', 'pakaian'  => '#FCE7F3',
                    'lainnya'    => '#DCFCE7',
                ][$cat->slug ?? 'lainnya'] ?? '#DCFCE7';

                $distText = $item->distance_km !== null
                    ? ($item->distance_km < 1
                        ? round($item->distance_km * 1000) . ' m'
                        : number_format($item->distance_km, 1, ',', '.') . ' km')
                    : null;
            @endphp

            <div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden flex flex-col transition hover:shadow-md hover:border-green-300"
                 data-item-id="{{ $item->id }}">

                {{-- Thumbnail --}}
                <div class="relative h-24 sm:h-28 border-b border-[#E3EAE3]"
                     style="background-color: {{ $bg }};">

                    {{-- Link ke detail --}}
                    <a href="{{ route('customer.items.show', $item) }}"
                       class="absolute inset-0 grid place-items-center"
                       style="z-index: 1;">
                        @if ($item->image_path)
                            <img src="{{ Storage::url($item->image_path) }}"
                                 alt="{{ $item->name }}"
                                 class="absolute inset-0 w-full h-full object-cover">
                        @else
                            <span class="text-3xl">{{ $cat->emoji ?? '📦' }}</span>
                        @endif
                    </a>

                    <form method="POST"
                          action="{{ route('customer.items.unfavorite', $item) }}"
                          onsubmit="return confirm('Hapus dari favorit?')"
                          style="position: absolute; top: 6px; right: 6px; z-index: 50; margin: 0;">
                        @csrf
                        <button type="submit"
                                class="w-7 h-7 rounded-full bg-white border border-red-200 text-red-500 grid place-items-center shadow-sm hover:bg-red-50 active:scale-90 transition cursor-pointer"
                                title="Hapus dari favorit"
                                aria-label="Hapus dari favorit">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="pointer-events: none;">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </button>
                    </form>

                    {{-- Jarak --}}
                    @if ($distText)
                        <div class="absolute bottom-1.5 left-1.5 inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full bg-white/95 backdrop-blur text-[9px] font-extrabold text-[#166534]"
                             style="z-index: 20; pointer-events: none;">
                            <x-icon name="map-pin" :size="9" />
                            {{ $distText }}
                        </div>
                    @endif
                </div>

                {{-- Body --}}
                <div class="p-2.5 flex flex-col gap-1.5 flex-1">
                    <a href="{{ route('customer.items.show', $item) }}" class="block">
                        <h3 class="font-bold text-[12.5px] leading-tight line-clamp-2 hover:text-[#166534] transition min-h-[32px]">
                            {{ $item->name }}
                        </h3>
                    </a>

                    <div class="flex flex-wrap gap-1">
                        <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold bg-[#F1F5F1] text-[#647164]">
                            {{ $cat->name }}
                        </span>
                    </div>

                    <div class="text-[10.5px] text-[#647164] mt-0.5 truncate">
                        <b class="text-[#172117]">{{ $item->quantity }} {{ $item->unit }}</b>
                        · {{ $item->supplier->name ?? '—' }}
                    </div>

                    <div class="mt-auto pt-1.5 border-t border-[#E3EAE3] flex items-center justify-between">
                        <a href="{{ route('customer.items.show', $item) }}"
                           class="text-[10px] font-semibold text-[#647164] hover:text-[#166534] transition">
                            Lihat
                        </a>
                        <a href="{{ route('customer.items.show', $item) }}"
                           class="w-5 h-5 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center hover:bg-[#16A34A] hover:text-white transition">
                            <x-icon name="arrow-right" :size="10" />
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endif

@endsection

@push('scripts')
<script>
/**
 * Hapus dari favorit — fungsi self-contained, tidak bergantung fungsi global.
 */
function removeFavorite(event, btn) {
    event.preventDefault();
    event.stopPropagation();

    if (!confirm('Hapus dari favorit?')) return;

    const itemId = btn.dataset.itemId;
    const url    = btn.dataset.url;
    const card   = document.querySelector(`[data-item-id="${itemId}"]`);

    btn.disabled = true;
    btn.style.opacity = '0.5';

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');

    if (!csrfMeta) {
        alert('CSRF token tidak ditemukan. Refresh halaman.');
        btn.disabled = false;
        btn.style.opacity = '';
        return;
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfMeta.content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    })
    .then(res => {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    })
    .then(data => {
        // Karena ini halaman favorit, setelah di-unfavorite → hapus card
        if (!data.favorited && card) {
            card.style.transition = 'opacity .3s, transform .3s';
            card.style.opacity = '0';
            card.style.transform = 'scale(.9)';
            setTimeout(() => {
                card.remove();
                updateFavCount();
            }, 300);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Gagal menghapus dari favorit. Coba lagi.');
        btn.disabled = false;
        btn.style.opacity = '';
    });
}

function updateFavCount() {
    const remaining = document.querySelectorAll('[data-item-id]').length;
    const el = document.getElementById('favCount');
    if (el) el.textContent = remaining;

    if (remaining === 0) {
        setTimeout(() => window.location.reload(), 400);
    }
}
</script>
@endpush