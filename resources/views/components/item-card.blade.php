@props(['item', 'distanceKm' => null])

@php
    $cat = $item->category;
    $emoji = $cat->emoji ?? '📦';
    $bg = [
        'kardus'     => '#FEF3C7', 'plastik'   => '#CFFAFE', 'botol'    => '#DBEAFE',
        'kertas'     => '#F1F5F9', 'kaca'      => '#CCFBF1', 'kayu'     => '#FDE68A',
        'logam'      => '#E2E8F0', 'elektronik'=> '#EDE9FE', 'pakaian'  => '#FCE7F3',
        'lainnya'    => '#DCFCE7',
    ][$cat->slug ?? 'lainnya'] ?? '#DCFCE7';

    $distText = $distanceKm !== null
        ? ($distanceKm < 1 ? round($distanceKm * 1000) . ' m' : number_format($distanceKm, 1, ',', '.') . ' km')
        : null;

    // Apakah user sudah memfavoritkan item ini? (single query per card, di-cache di memory)
    $isFavorited = auth()->check()
        && auth()->user()->favorites()->where('item_id', $item->id)->exists();
@endphp

<div class="group bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden flex flex-col transition hover:shadow-lg hover:border-green-300 hover:-translate-y-0.5 relative">

    {{-- Thumbnail --}}
    <div class="h-36 grid place-items-center border-b border-[#E3EAE3] relative"
         style="background-color: {{ $bg }};">
        <a href="{{ route('customer.items.show', $item) }}" class="absolute inset-0 grid place-items-center">
            @if ($item->image_path)
                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}"
                     class="absolute inset-0 w-full h-full object-cover">
            @else
                <span class="text-5xl">{{ $emoji }}</span>
            @endif
        </a>

        {{-- Jarak --}}
        @if ($distText)
            <div class="absolute top-2.5 left-2.5 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/95 backdrop-blur border border-green-200 text-[11px] font-extrabold text-[#166534] pointer-events-none">
                <x-icon name="map-pin" :size="11" />
                {{ $distText }}
            </div>
        @endif

        {{-- Tombol favorit --}}
        <button type="button"
                onclick="toggleFavorite(event, {{ $item->id }}, this)"
                data-favorited="{{ $isFavorited ? '1' : '0' }}"
                class="fav-btn absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/95 backdrop-blur border border-[#E3EAE3] grid place-items-center transition
                       {{ $isFavorited ? 'text-red-500 border-red-200' : 'text-[#647164] hover:text-red-500 hover:border-red-200' }}"
                title="{{ $isFavorited ? 'Hapus dari favorit' : 'Simpan ke favorit' }}"
                aria-label="Favorit">
            <svg width="15" height="15" viewBox="0 0 24 24"
                 fill="{{ $isFavorited ? 'currentColor' : 'none' }}"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </button>
    </div>

    {{-- Body --}}
    <div class="p-4 flex flex-col gap-2.5 flex-1">
        <a href="{{ route('customer.items.show', $item) }}" class="block">
            <h3 class="font-bold text-[15px] leading-snug tracking-tight line-clamp-1 hover:text-[#166534] transition">
                {{ $item->name }}
            </h3>
        </a>

        <div class="flex flex-wrap gap-1.5">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-[#F1F5F1] text-[#647164]">
                {{ $cat->name ?? 'Lainnya' }}
            </span>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold
                {{ $item->condition === 'Baik' ? 'bg-[#DCFCE7] text-[#166534]' : ($item->condition === 'Cukup' ? 'bg-amber-100 text-amber-700' : 'bg-red-50 text-red-700') }}">
                {{ $item->condition }}
            </span>
        </div>

        <div class="text-xs text-[#647164] space-y-1 mt-0.5">
            <div class="flex items-center gap-1.5">
                <x-icon name="package" :size="12" />
                Tersedia <b class="text-[#172117]">{{ $item->quantity }} {{ $item->unit }}</b>
            </div>
            <div class="flex items-center gap-1.5">
                <x-icon name="user" :size="12" />
                {{ $item->supplier->name ?? 'Supplier' }}
            </div>
            <div class="flex items-center gap-1.5">
                <x-icon name="map-pin" :size="12" />
                {{ $item->city ?? 'Lokasi belum tersedia' }}
            </div>
        </div>

        <div class="mt-auto pt-3 border-t border-[#E3EAE3] flex items-center justify-between">
            <a href="{{ route('customer.items.show', $item) }}"
               class="text-[11px] text-[#647164] font-semibold hover:text-[#166534] transition">
                Lihat Detail
            </a>
            <a href="{{ route('customer.items.show', $item) }}"
               class="w-7 h-7 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center group-hover:bg-[#16A34A] group-hover:text-white transition">
                <x-icon name="arrow-right" :size="13" />
            </a>
        </div>
    </div>
</div>