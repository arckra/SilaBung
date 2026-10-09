@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang di SILABUNG')

@php
    $toneMap = [
        'green' => 'bg-[#DCFCE7] text-[#166534]',
        'amber' => 'bg-amber-100 text-amber-700',
        'red'   => 'bg-red-100 text-red-700',
        'blue'  => 'bg-sky-100 text-sky-700',
        'gray'  => 'bg-[#F1F5F1] text-[#647164]',
    ];
@endphp

@section('content')

{{-- ===================== WELCOME BANNER ===================== --}}
<div class="relative rounded-2xl overflow-hidden mb-5 bg-gradient-to-r from-[#F0FDF4] via-white to-[#F0FDF4] border border-[#E3EAE3] p-6 sm:p-8">
    <div class="absolute right-0 top-0 bottom-0 w-[45%] hidden md:block pointer-events-none">
        <svg viewBox="0 0 300 200" class="h-full w-full opacity-70" preserveAspectRatio="xMidYMid slice">
            <path d="M0 180 Q80 100 150 140 T300 100 L300 200 L0 200 Z" fill="#DCFCE7" opacity="0.6"/>
            <g transform="translate(150 100)">
                <ellipse cx="0" cy="30" rx="55" ry="40" fill="#16A34A" opacity="0.1"/>
                <path d="M-30 -20 C-30 -55 30 -55 30 -20 L35 30 L-35 30 Z" fill="#16A34A" opacity="0.15"/>
                <path d="M-15 -30 C-15 -60 0 -70 0 -70 C0 -70 15 -60 15 -30 Z" fill="#166534" opacity="0.5"/>
                <path d="M-40 -10 C-55 -25 -60 -40 -50 -45 C-40 -50 -30 -35 -25 -20 Z" fill="#16A34A" opacity="0.4"/>
                <path d="M40 -10 C55 -25 60 -40 50 -45 C40 -50 30 -35 25 -20 Z" fill="#16A34A" opacity="0.4"/>
            </g>
        </svg>
    </div>

    <div class="relative max-w-lg">
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight mb-2">
            Halo, {{ explode(' ', auth()->user()->name)[0] }} 👋
        </h1>
        <p class="text-sm sm:text-base text-[#647164] leading-relaxed mb-5">
            Selamat datang kembali di SILABUNG. Yuk temukan barang yang masih bisa digunakan dan berikan manfaat baru!
        </p>
        <a href="{{ route('customer.search') }}"
           class="inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-[#166534] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#14532d] transition">
            <x-icon name="plus" :size="15" />
            Jelajahi Barang
        </a>
    </div>
</div>

{{-- ===================== SEARCH & FILTER ===================== --}}
<form method="GET" action="{{ route('customer.search') }}" class="mb-5">
    <div class="flex gap-2">
        <div class="relative flex-1">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#647164] pointer-events-none">
                <x-icon name="search" :size="17" />
            </span>
            <input type="text" name="q" placeholder="Cari barang, material, atau kebutuhan..."
                   class="w-full h-12 pl-11 pr-11 rounded-xl border border-[#E3EAE3] bg-white text-sm focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 grid place-items-center rounded-lg text-[#647164] hover:text-[#166534] hover:bg-[#F0FDF4] transition">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="6" x2="20" y2="6"/>
                    <line x1="4" y1="12" x2="20" y2="12"/>
                    <line x1="4" y1="18" x2="20" y2="18"/>
                    <circle cx="9" cy="6" r="2" fill="currentColor"/>
                    <circle cx="15" cy="12" r="2" fill="currentColor"/>
                    <circle cx="9" cy="18" r="2" fill="currentColor"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Category chips --}}
    <div class="flex gap-2 overflow-x-auto mt-3 pb-1 -mx-1 px-1">
        <a href="{{ route('customer.dashboard') }}"
           class="shrink-0 px-4 py-2 rounded-full text-xs font-bold border transition
                  {{ ! $catSlug ? 'bg-[#166534] text-white border-[#166534]' : 'bg-white border-[#E3EAE3] text-[#647164] hover:border-green-300' }}">
            Semua
        </a>
        @foreach ($categories->take(7) as $cat)
            <a href="{{ route('customer.dashboard', ['category' => $cat->slug]) }}"
               class="shrink-0 px-4 py-2 rounded-full text-xs font-bold border transition
                      {{ $catSlug === $cat->slug ? 'bg-[#166534] text-white border-[#166534]' : 'bg-white border-[#E3EAE3] text-[#647164] hover:border-green-300' }}">
                {{ $cat->name }}
            </a>
        @endforeach
        @if ($categories->count() > 7)
            <a href="{{ route('customer.search') }}"
               class="shrink-0 px-4 py-2 rounded-full text-xs font-bold border border-[#E3EAE3] bg-white text-[#647164] hover:border-green-300 transition">
                Lainnya ▾
            </a>
        @endif
    </div>
</form>

{{-- ===================== BARANG DI SEKITAR ===================== --}}
<div class="mb-3 flex items-center justify-between">
    <div>
        <h2 class="font-extrabold tracking-tight text-lg">Barang di sekitar kamu</h2>
        <p class="text-xs text-[#647164] mt-0.5">Pilihan barang terbaru untuk kamu</p>
    </div>
    <a href="{{ route('customer.search') }}" class="text-xs font-bold text-[#166534] hover:underline">
        Lihat semua →
    </a>
</div>

@if ($items->isEmpty())
    <div class="bg-white border border-[#E3EAE3] rounded-2xl mb-5">
        <x-empty-state icon="search" title="Belum ada barang tersedia"
            description="Coba jelajahi kategori lain atau kembali lagi nanti." />
    </div>
@else
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
        @foreach ($items->take(4) as $item)
            @php
                $cat = $item->category;
                $bg = [
                    'kardus' => '#FEF3C7', 'plastik' => '#CFFAFE', 'botol' => '#DBEAFE',
                    'kertas' => '#F1F5F9', 'kaca' => '#CCFBF1', 'kayu' => '#FDE68A',
                    'logam' => '#E2E8F0', 'elektronik' => '#EDE9FE', 'pakaian' => '#FCE7F3',
                ][$cat->slug ?? ''] ?? '#DCFCE7';
                $distText = $item->distance_km !== null
                    ? ($item->distance_km < 1 ? round($item->distance_km * 1000) . ' m' : number_format($item->distance_km, 1, ',', '.') . ' km')
                    : null;
                $isFav = auth()->user()->favorites()->where('item_id', $item->id)->exists();
            @endphp
            <div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden flex flex-col transition hover:shadow-lg hover:border-green-300 hover:-translate-y-0.5 group">
                <div class="h-32 relative grid place-items-center" style="background-color: {{ $bg }};">
                    <a href="{{ route('customer.items.show', $item) }}" class="absolute inset-0 grid place-items-center">
                        @if ($item->image_path)
                            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="absolute inset-0 w-full h-full object-cover">
                        @else
                            <span class="text-5xl">{{ $cat->emoji ?? '📦' }}</span>
                        @endif
                    </a>

                    <button type="button"
                            onclick="toggleFavorite(event, {{ $item->id }}, this)"
                            data-favorited="{{ $isFav ? '1' : '0' }}"
                            class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/95 backdrop-blur border border-[#E3EAE3] grid place-items-center transition
                                   {{ $isFav ? 'text-red-500 border-red-200' : 'text-[#647164] hover:text-red-500' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ $isFav ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
                </div>

                <div class="p-3.5 flex flex-col gap-2 flex-1">
                    <span class="inline-flex w-fit px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F5F1] text-[#647164]">
                        {{ $cat->name }}
                    </span>

                    <a href="{{ route('customer.items.show', $item) }}">
                        <h3 class="font-bold text-[14px] leading-snug line-clamp-1 hover:text-[#166534] transition">
                            {{ $item->name }}
                        </h3>
                    </a>

                    <div class="text-[11px] text-[#647164] space-y-0.5">
                        <div class="flex items-center gap-1.5">
                            <x-icon name="package" :size="11" />
                            <b class="text-[#172117]">{{ $item->quantity }} {{ $item->unit }}</b>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <x-icon name="map-pin" :size="11" />
                            {{ $item->city ?? 'Lokasi belum tersedia' }}
                        </div>
                    </div>

                    <div class="mt-auto pt-2 flex items-center justify-between">
                        <span class="text-[10px] text-[#647164] font-semibold">
                            {{ $distText ? '± ' . $distText : '—' }}
                        </span>
                        <a href="{{ route('customer.items.show', $item) }}"
                           class="w-6 h-6 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center group-hover:bg-[#16A34A] group-hover:text-white transition">
                            <x-icon name="arrow-right" :size="11" />
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- ===================== CTA + KATEGORI POPULER ===================== --}}
<div class="grid md:grid-cols-[1.4fr_1fr] gap-4">

    {{-- CTA --}}
    <div class="bg-gradient-to-br from-[#DCFCE7] via-[#F0FDF4] to-white border border-[#cfe6d5] rounded-2xl p-6 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-16 w-44 h-44 opacity-40 pointer-events-none">
            <svg viewBox="0 0 200 200" class="w-full h-full">
                <path d="M100 20 C130 50 130 150 100 180 C70 150 70 50 100 20 Z" fill="#16A34A" opacity="0.15"/>
                <circle cx="100" cy="100" r="60" fill="none" stroke="#16A34A" stroke-width="2" opacity="0.3" stroke-dasharray="6 6"/>
            </svg>
        </div>
        <div class="relative max-w-xs">
            <div class="w-10 h-10 rounded-xl bg-white grid place-items-center text-[#166534] mb-4">
                <x-icon name="leaf" :size="18" />
            </div>
            <h3 class="text-lg font-extrabold tracking-tight leading-tight mb-2">
                Mari jaga lingkungan bersama-sama
            </h3>
            <p class="text-xs text-[#647164] leading-relaxed mb-4">
                Setiap barang yang kamu gunakan kembali adalah langkah kecil untuk bumi yang lebih baik.
            </p>
            <a href="{{ route('customer.search') }}"
               class="inline-flex items-center gap-1.5 h-10 px-4 rounded-xl bg-[#166534] text-white text-xs font-bold hover:bg-[#14532d] transition">
                Pelajari lebih lanjut
                <x-icon name="arrow-right" :size="13" />
            </a>
        </div>
    </div>

    {{-- Kategori populer --}}
    <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
        <h3 class="font-extrabold tracking-tight text-sm mb-3">Kategori Populer</h3>
        <div class="space-y-1">
            @foreach ($popularCategories as $cat)
                <a href="{{ route('customer.dashboard', ['category' => $cat->slug]) }}"
                   class="flex items-center gap-3 px-2 py-2.5 rounded-lg hover:bg-[#F0FDF4] transition">
                    <div class="w-8 h-8 rounded-lg bg-[#DCFCE7] grid place-items-center text-base">
                        {{ $cat->emoji ?? '📦' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-[13px] font-bold truncate">{{ $cat->name }}</div>
                        <div class="text-[10.5px] text-[#647164]">{{ $cat->items_count }} barang</div>
                    </div>
                    <x-icon name="arrow-right" :size="12" />
                </a>
            @endforeach
        </div>
    </div>
</div>

@endsection

@section('right-sidebar')

{{-- ============ AKTIVITAS TERBARU ============ --}}
<div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-extrabold tracking-tight text-sm">Aktivitas Terbaru</h3>
        <a href="{{ route('customer.requests.index') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
            Lihat semua →
        </a>
    </div>

    @if (empty($activities))
        <p class="text-xs text-[#647164] text-center py-6">Belum ada aktivitas.</p>
    @else
        <div class="relative">
            <div class="absolute left-[15px] top-3 bottom-3 w-px bg-[#E3EAE3]"></div>
            <div class="space-y-4">
                @foreach ($activities as $act)
                    @php $tone = $toneMap[$act['tone']] ?? $toneMap['gray']; @endphp
                    <div class="flex gap-3 relative">
                        <div class="w-8 h-8 rounded-full grid place-items-center shrink-0 {{ $tone }} relative z-10 border-2 border-white">
                            <x-icon :name="$act['icon']" :size="13" />
                        </div>
                        <div class="min-w-0 flex-1 pt-0.5">
                            <div class="text-[12.5px] font-bold leading-tight">{{ $act['title'] }}</div>
                            <div class="text-[10.5px] text-[#647164] truncate mt-0.5">{{ $act['meta'] }}</div>
                            <div class="text-[10px] text-[#647164] mt-0.5">{{ $act['at']->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

{{-- ============ PENGAJUAN SAYA ============ --}}
<div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-extrabold tracking-tight text-sm">Pengajuan Saya</h3>
        <a href="{{ route('customer.requests.index') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
            Lihat semua →
        </a>
    </div>

    <div class="grid grid-cols-3 divide-x divide-[#E3EAE3]">
        <div class="text-center px-1">
            <div class="text-2xl font-extrabold tracking-tight text-amber-500">{{ $stats['aktif'] }}</div>
            <div class="text-[10px] text-[#647164] font-bold mt-0.5">Aktif</div>
        </div>
        <div class="text-center px-1">
            <div class="text-2xl font-extrabold tracking-tight text-sky-600">{{ $stats['diproses'] }}</div>
            <div class="text-[10px] text-[#647164] font-bold mt-0.5">Diproses</div>
        </div>
        <div class="text-center px-1">
            <div class="text-2xl font-extrabold tracking-tight text-[#166534]">{{ $stats['selesai'] }}</div>
            <div class="text-[10px] text-[#647164] font-bold mt-0.5">Selesai</div>
        </div>
    </div>
</div>

{{-- ============ BARANG FAVORIT ============ --}}
<div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-extrabold tracking-tight text-sm">Barang Favorit</h3>
        <a href="{{ route('customer.favorites') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
            Lihat semua →
        </a>
    </div>

    @if ($favorites->isEmpty())
        <div class="text-center py-5">
            <p class="text-[11px] text-[#647164] mb-3">Belum ada barang favorit.</p>
            <a href="{{ route('customer.search') }}"
               class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg bg-[#DCFCE7] text-[#166534] text-[11px] font-bold hover:bg-[#bbf7d0] transition">
                Jelajahi Barang
            </a>
        </div>
    @else
        <div class="space-y-1">
            @foreach ($favorites as $fav)
                <a href="{{ route('customer.items.show', $fav) }}"
                   class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-[#F0FDF4] transition group">
                    <div class="w-10 h-10 rounded-lg bg-[#DCFCE7] grid place-items-center text-lg shrink-0">
                        {{ $fav->category->emoji ?? '📦' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-[12.5px] font-bold truncate">{{ $fav->name }}</div>
                        <div class="text-[10.5px] text-[#647164]">{{ $fav->quantity }} {{ $fav->unit }}</div>
                    </div>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#DC2626" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </a>
            @endforeach
        </div>
    @endif
</div>

@endsection