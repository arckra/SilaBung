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

{{-- ===================== 1. WELCOME BANNER (Hanya Desktop) ===================== --}}
<div class="relative rounded-2xl overflow-hidden mb-5 bg-gradient-to-r from-[#F0FDF4] via-white to-[#F0FDF4] border border-[#E3EAE3] p-6 sm:p-8 hidden sm:block">
    <div class="relative max-w-lg">
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight mb-2">
            Halo, {{ explode(' ', auth()->user()->name)[0] }}!
        </h1>
        <p class="text-sm sm:text-base text-[#647164] leading-relaxed mb-5">
            Selamat datang kembali di SILABUNG. Yuk temukan barang yang masih bisa digunakan dan berikan manfaat baru!
        </p>
    </div>
</div>

{{-- ===================== 2. SEARCH ===================== --}}
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
                    <line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/>
                    <circle cx="9" cy="6" r="2" fill="currentColor"/><circle cx="15" cy="12" r="2" fill="currentColor"/><circle cx="9" cy="18" r="2" fill="currentColor"/>
                </svg>
            </button>
        </div>
    </div>
</form>

{{-- ===================== 3. PENGAJUAN SAYA ===================== --}}
<div class="bg-white border border-[#E3EAE3] rounded-2xl p-5 mb-6 shadow-sm">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-extrabold tracking-tight text-sm">Pengajuan Saya</h3>
        <a href="{{ route('customer.requests.index') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
            Lihat semua
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

{{-- ===================== 4. KATEGORI POPULER ===================== --}}
<div class="mb-8">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-extrabold tracking-tight text-sm">Kategori Populer</h3>
    </div>
    
    <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide -mx-1 px-1">
        @foreach ($popularCategories as $cat)
            <a href="{{ route('customer.dashboard', ['category' => $cat->slug]) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl border border-[#E3EAE3] bg-white min-w-[150px] shrink-0 hover:border-green-300 hover:shadow-sm transition">
                <div class="w-9 h-9 rounded-lg bg-[#DCFCE7] grid place-items-center text-lg">
                    {{ $cat->emoji ?? 'ðŸ“¦' }}
                </div>
                <div class="min-w-0">
                    <div class="text-[13px] font-bold text-[#172117] truncate">{{ $cat->name }}</div>
                    <div class="text-[10.5px] text-[#647164]">{{ $cat->items_count }} barang</div>
                </div>
            </a>
        @endforeach
    </div>
</div>

{{-- ===================== 5. AKTIVITAS TERBARU (KHUSUS MOBILE) ===================== --}}
<div class="lg:hidden bg-white border border-[#E3EAE3] rounded-2xl p-5 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-extrabold tracking-tight text-sm">Aktivitas Terbaru</h3>
        <a href="{{ route('customer.requests.index') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
            Lihat semua â†’
        </a>
    </div>

    @if (empty($activities))
        <p class="text-xs text-[#647164] text-center py-4">Belum ada aktivitas.</p>
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

{{-- ===================== 6. BARANG DI SEKITAR KAMU ===================== --}}
<div class="mb-3 flex items-center justify-between">
    <div>
        <h2 class="font-extrabold tracking-tight text-lg">Barang di sekitar kamu</h2>
        <p class="text-xs text-[#647164] mt-0.5">Pilihan barang terbaru untuk kamu</p>
    </div>
    <a href="{{ route('customer.search') }}" class="text-xs font-bold text-[#166534] hover:underline">
        Lihat semua
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
            
            {{-- Tambahkan class 'relative group' di sini --}}
            <div class="relative group bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden flex flex-col h-full transition hover:shadow-lg hover:border-green-300 hover:-translate-y-0.5">
                
                {{-- OVERLAY LINK: Menutupi seluruh kotak dengan link transparan --}}
                <a href="{{ route('customer.items.show', $item) }}" class="absolute inset-0 z-10" aria-label="Lihat detail {{ $item->name }}"></a>

                {{-- Bagian Gambar --}}
                <div class="h-32 w-full relative flex items-center justify-center shrink-0 overflow-hidden p-3" style="background-color: {{ $bg }};">
                    @if ($item->image_path)
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-contain drop-shadow-sm">
                    @else
                        <span class="text-5xl">{{ $cat->emoji ?? '📦' }}</span>
                    @endif

                    {{-- Form Favorit: Tambahkan 'z-20' agar bisa diklik di atas overlay --}}
                    <form method="POST" action="{{ route('customer.items.favorite', $item) }}" class="absolute top-2.5 right-2.5 z-20 m-0">
                        @csrf
                        <button type="submit"
                                class="w-7 h-7 rounded-full bg-white/95 backdrop-blur border grid place-items-center transition hover:scale-110 cursor-pointer
                                       {{ $isFav ? 'text-red-500 border-red-200' : 'text-[#647164] border-[#E3EAE3] hover:text-red-500' }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ $isFav ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="pointer-events: none;">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Bagian Teks --}}
                <div class="p-3.5 flex flex-col flex-1">
                    <span class="inline-flex w-fit px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F5F1] text-[#647164]">{{ $cat->name }}</span>
                    
                    <h3 class="font-bold text-[14px] leading-snug line-clamp-1 mt-1.5 group-hover:text-[#166534] transition">{{ $item->name }}</h3>
                    
                    <div class="text-[11px] text-[#647164] space-y-0.5 mt-2">
                        <div class="flex items-center gap-1.5"><x-icon name="package" :size="11" /> <b class="text-[#172117]">{{ $item->quantity }} {{ $item->unit }}</b></div>
                        <div class="flex items-center gap-1.5"><x-icon name="map-pin" :size="11" /> {{ $item->city ?? 'Lokasi belum tersedia' }}</div>
                    </div>

                    {{-- Bagian Bawah: HAPUS tombol panah, sisakan teks jarak saja --}}
                    <div class="mt-auto pt-3 flex items-center justify-between">
                        <span class="text-[10px] text-[#647164] font-semibold">{{ $distText ? $distText : '-' }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection

{{-- ===================== SIDEBAR KANAN (HANYA DESKTOP) ===================== --}}
@section('right-sidebar')
{{-- Class hidden lg:block berarti ini hanya muncul di Desktop --}}
<div class="hidden lg:block bg-white border border-[#E3EAE3] rounded-2xl p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-extrabold tracking-tight text-sm">Aktivitas Terbaru</h3>
        <a href="{{ route('customer.requests.index') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
            Lihat semua â†’
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
@endsection