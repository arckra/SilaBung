@extends('layouts.app')

@section('title', 'Dashboard Supplier')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Kelola barang dan permintaan')

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
            Terima kasih sudah berbagi! Barang yang kamu bagikan membantu orang lain dan mengurangi sampah.
        </p>
    </div>
</div>

{{-- ===================== 2. SEARCH BARANG SAYA ===================== --}}
<div class="mb-5">
    <div class="relative">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#647164] pointer-events-none">
            <x-icon name="search" :size="17" />
        </span>
        <input type="text" id="myItemSearch" placeholder="Cari di barang saya..."
               class="w-full h-12 pl-11 pr-4 rounded-xl border border-[#E3EAE3] bg-white text-sm focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none">
    </div>
</div>

{{-- ===================== 3. PERMINTAAN MASUK (Statistik) ===================== --}}
<div class="bg-white border border-[#E3EAE3] rounded-2xl p-5 mb-6 shadow-sm">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-extrabold tracking-tight text-sm">Permintaan Masuk</h3>
        <a href="{{ route('supplier.requests.index') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
            Lihat semua >
        </a>
    </div>

    <div class="grid grid-cols-3 divide-x divide-[#E3EAE3]">
        <div class="text-center px-1">
            <div class="text-2xl font-extrabold tracking-tight text-amber-500">{{ $requestStats['menunggu'] }}</div>
            <div class="text-[10px] text-[#647164] font-bold mt-0.5">Menunggu</div>
        </div>
        <div class="text-center px-1">
            <div class="text-2xl font-extrabold tracking-tight text-sky-600">{{ $requestStats['diterima'] }}</div>
            <div class="text-[10px] text-[#647164] font-bold mt-0.5">Diterima</div>
        </div>
        <div class="text-center px-1">
            <div class="text-2xl font-extrabold tracking-tight text-[#166534]">{{ $requestStats['selesai'] }}</div>
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
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl border border-[#E3EAE3] bg-white min-w-[150px] shrink-0 hover:border-green-300 hover:shadow-sm transition">
                <div class="w-9 h-9 rounded-lg bg-[#DCFCE7] grid place-items-center text-lg">
                    {{ $cat->emoji ?? '📦' }}
                </div>
                <div class="min-w-0">
                    <div class="text-[13px] font-bold text-[#172117] truncate">{{ $cat->name }}</div>
                    <div class="text-[10.5px] text-[#647164]">{{ $cat->items_count }} barang</div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- ===================== 5. AKTIVITAS TERBARU (KHUSUS MOBILE) ===================== --}}
<div class="lg:hidden bg-white border border-[#E3EAE3] rounded-2xl p-5 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-extrabold tracking-tight text-sm">Aktivitas Terbaru</h3>
        <a href="{{ route('supplier.requests.index') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
            Lihat semua >
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

{{-- ===================== 6. BARANG SAYA ===================== --}}
<div class="mb-3 flex items-center justify-between">
    <div>
        <h2 class="font-extrabold tracking-tight text-lg">Barang Saya</h2>
        <p class="text-xs text-[#647164] mt-0.5">Kelola stok barang yang kamu bagikan</p>
    </div>
    <a href="{{ route('supplier.items.index') }}" class="text-xs font-bold text-[#166534] hover:underline">
        Lihat semua >
    </a>
</div>

@if ($items->isEmpty())
    <div class="bg-white border border-[#E3EAE3] rounded-2xl mb-5">
        <x-empty-state icon="package" title="Belum ada barang"
            description="Yuk bagikan barang yang sudah tidak kamu gunakan."
            actionLabel="Tambah Barang Pertamamu" :actionHref="route('supplier.items.create')" />
    </div>
@else
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6" id="myItemsGrid">
        @foreach ($items as $item)
            @php
                $cat = $item->category;
                $bg = [
                    'kardus' => '#FEF3C7', 'plastik' => '#CFFAFE', 'botol' => '#DBEAFE',
                    'kertas' => '#F1F5F9', 'kaca' => '#CCFBF1', 'kayu' => '#FDE68A',
                    'logam' => '#E2E8F0', 'elektronik' => '#EDE9FE', 'pakaian' => '#FCE7F3',
                ][$cat->slug ?? ''] ?? '#DCFCE7';
            @endphp
            
            <a href="{{ route('supplier.items.edit', $item) }}" data-name="{{ strtolower($item->name) }}"
               class="relative group bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden flex flex-col h-full transition hover:shadow-lg hover:border-green-300 hover:-translate-y-0.5">
                
                <div class="h-32 w-full relative flex items-center justify-center shrink-0 overflow-hidden p-3" style="background-color: {{ $bg }};">
                    @if ($item->image_path)
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-contain drop-shadow-sm">
                    @else
                        <span class="text-5xl">{{ $cat->emoji ?? '📦' }}</span>
                    @endif

                    <div class="absolute top-2.5 left-2.5 z-10">
                        @if ($item->status === 'available')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#166534] border border-green-200 shadow-sm">Tersedia</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F5F1] text-[#647164] border border-[#E3EAE3] shadow-sm">Habis</span>
                        @endif
                    </div>
                </div>

                <div class="p-3.5 flex flex-col flex-1">
                    <span class="inline-flex w-fit px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F5F1] text-[#647164]">{{ $cat->name }}</span>
                    
                    <h3 class="font-bold text-[14px] leading-snug line-clamp-1 mt-1.5 group-hover:text-[#166534] transition">{{ $item->name }}</h3>
                    
                    <div class="text-[11px] text-[#647164] space-y-0.5 mt-2">
                        <div class="flex items-center gap-1.5"><x-icon name="package" :size="11" /> <b class="text-[#172117]">{{ $item->quantity }} {{ $item->unit }}</b></div>
                        <div class="flex items-center gap-1.5"><x-icon name="map-pin" :size="11" /> {{ $item->city ?? '-' }}</div>
                    </div>

                    <div class="mt-auto pt-3 flex items-center justify-between border-t border-[#E3EAE3] mt-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider
                            {{ $item->condition === 'Baik' ? 'text-[#166534]' : ($item->condition === 'Cukup' ? 'text-amber-600' : 'text-red-600') }}">
                            {{ $item->condition }}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endif

@endsection

{{-- ===================== SIDEBAR KANAN (HANYA DESKTOP) ===================== --}}
@section('right-sidebar')
{{-- 1. Aktivitas Terbaru --}}
<div class="hidden lg:block bg-white border border-[#E3EAE3] rounded-2xl p-5 mb-4">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-extrabold tracking-tight text-sm">Aktivitas Terbaru</h3>
        <a href="{{ route('supplier.requests.index') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
            Lihat semua >
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

{{-- 2. Stok Hampir Habis --}}
<div class="hidden lg:block bg-white border border-[#E3EAE3] rounded-2xl p-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-extrabold tracking-tight text-sm">Stok Hampir Habis</h3>
        <a href="{{ route('supplier.items.index') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
            Lihat semua >
        </a>
    </div>

    @if ($lowStock->isEmpty())
        <div class="text-center py-5">
            <p class="text-[11px] text-[#647164]">Semua stok masih aman 👍</p>
        </div>
    @else
        <div class="space-y-1">
            @foreach ($lowStock as $it)
                <a href="{{ route('supplier.items.edit', $it) }}" class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-[#F0FDF4] transition">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 grid place-items-center text-lg shrink-0">
                        {{ $it->category->emoji ?? '📦' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-[12.5px] font-bold truncate">{{ $it->name }}</div>
                        <div class="text-[10.5px] text-amber-600 font-bold">
                            Sisa {{ $it->quantity }} {{ $it->unit }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Fitur Pencarian Client-side
    document.getElementById('myItemSearch')?.addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase().trim();
        document.querySelectorAll('#myItemsGrid > a').forEach(card => {
            const name = card.dataset.name || '';
            card.style.display = name.includes(q) ? 'flex' : 'none';
        });
    });
</script>
@endpush