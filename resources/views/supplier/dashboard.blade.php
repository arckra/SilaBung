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

{{-- ===================== WELCOME BANNER ===================== --}}
<div class="relative rounded-2xl overflow-hidden mb-5 bg-gradient-to-r from-[#F0FDF4] via-white to-[#F0FDF4] border border-[#E3EAE3] p-6 sm:p-8">
    <div class="absolute right-0 top-0 bottom-0 w-[45%] hidden md:block pointer-events-none">
        <svg viewBox="0 0 300 200" class="h-full w-full opacity-70" preserveAspectRatio="xMidYMid slice">
            <path d="M0 180 Q80 100 150 140 T300 100 L300 200 L0 200 Z" fill="#DCFCE7" opacity="0.6"/>
            <g transform="translate(160 100)">
                <rect x="-55" y="-15" width="50" height="40" rx="4" fill="#16A34A" opacity="0.15"/>
                <rect x="-50" y="-20" width="40" height="10" rx="2" fill="#166534" opacity="0.25"/>
                <rect x="5" y="-5" width="45" height="35" rx="4" fill="#16A34A" opacity="0.12"/>
                <rect x="10" y="-10" width="35" height="10" rx="2" fill="#166534" opacity="0.22"/>
                <circle cx="20" cy="40" r="30" fill="#DCFCE7" opacity="0.5"/>
            </g>
        </svg>
    </div>

    <div class="relative max-w-lg">
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight mb-2">
            Halo, {{ explode(' ', auth()->user()->name)[0] }} 👋
        </h1>
        <p class="text-sm sm:text-base text-[#647164] leading-relaxed mb-5">
            Terima kasih sudah berbagi! Barang yang kamu bagikan membantu orang lain dan mengurangi sampah.
        </p>
        <a href="{{ route('supplier.items.create') }}"
           class="inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-[#166534] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#14532d] transition">
            <x-icon name="plus" :size="15" />
            Tambah Barang
        </a>
    </div>
</div>

{{-- ===================== SEARCH BARANG SAYA ===================== --}}
<div class="mb-5">
    <div class="relative">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#647164] pointer-events-none">
            <x-icon name="search" :size="17" />
        </span>
        <input type="text" id="myItemSearch" placeholder="Cari di barang saya..."
               class="w-full h-12 pl-11 pr-4 rounded-xl border border-[#E3EAE3] bg-white text-sm focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none">
    </div>
</div>

{{-- ===================== BARANG SAYA ===================== --}}
<div class="mb-3 flex items-center justify-between">
    <div>
        <h2 class="font-extrabold tracking-tight text-lg">Barang Saya</h2>
        <p class="text-xs text-[#647164] mt-0.5">Kelola stok barang yang kamu bagikan</p>
    </div>
    <a href="{{ route('supplier.items.index') }}" class="text-xs font-bold text-[#166534] hover:underline">
        Lihat semua →
    </a>
</div>

@if ($items->isEmpty())
    <div class="bg-white border border-[#E3EAE3] rounded-2xl mb-6">
        <x-empty-state icon="package" title="Belum ada barang"
            description="Yuk bagikan barang yang sudah tidak kamu gunakan."
            actionLabel="Tambah Barang Pertamamu" :actionHref="route('supplier.items.create')" />
    </div>
@else
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6" id="myItemsGrid">
        @foreach ($items->take(4) as $item)
            @php
                $cat = $item->category;
                $bg = [
                    'kardus' => '#FEF3C7', 'plastik' => '#CFFAFE', 'botol' => '#DBEAFE',
                    'kertas' => '#F1F5F9', 'kaca' => '#CCFBF1', 'kayu' => '#FDE68A',
                    'logam' => '#E2E8F0', 'elektronik' => '#EDE9FE', 'pakaian' => '#FCE7F3',
                ][$cat->slug ?? ''] ?? '#DCFCE7';
            @endphp
            <div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden flex flex-col transition hover:shadow-lg hover:border-green-300 hover:-translate-y-0.5 group">
                <div class="h-32 relative grid place-items-center" style="background-color: {{ $bg }};">
                    @if ($item->image_path)
                        <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <span class="text-5xl">{{ $cat->emoji ?? '📦' }}</span>
                    @endif

                    <div class="absolute top-2.5 left-2.5">
                        @if ($item->status === 'available')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#166534] border border-green-200">
                                Tersedia
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F5F1] text-[#647164] border border-[#E3EAE3]">
                                Habis
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('supplier.items.edit', $item) }}"
                       class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/95 backdrop-blur border border-[#E3EAE3] grid place-items-center text-[#647164] hover:text-[#166534] transition">
                        <x-icon name="edit" :size="12" />
                    </a>
                </div>

                <div class="p-3.5 flex flex-col gap-2 flex-1">
                    <span class="inline-flex w-fit px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F5F1] text-[#647164]">
                        {{ $cat->name }}
                    </span>

                    <h3 class="font-bold text-[14px] leading-snug line-clamp-1">{{ $item->name }}</h3>

                    <div class="text-[11px] text-[#647164] space-y-0.5">
                        <div class="flex items-center gap-1.5">
                            <x-icon name="package" :size="11" />
                            <b class="text-[#172117]">{{ $item->quantity }} {{ $item->unit }}</b>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <x-icon name="map-pin" :size="11" />
                            {{ $item->city ?? '—' }}
                        </div>
                    </div>

                    <div class="mt-auto pt-2 flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider
                            {{ $item->condition === 'Baik' ? 'text-[#166534]' : ($item->condition === 'Cukup' ? 'text-amber-600' : 'text-red-600') }}">
                            {{ $item->condition }}
                        </span>
                        <a href="{{ route('supplier.items.edit', $item) }}"
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

    <div class="bg-gradient-to-br from-[#DCFCE7] via-[#F0FDF4] to-white border border-[#cfe6d5] rounded-2xl p-6 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-16 w-44 h-44 opacity-40 pointer-events-none">
            <svg viewBox="0 0 200 200" class="w-full h-full">
                <path d="M100 20 C130 50 130 150 100 180 C70 150 70 50 100 20 Z" fill="#16A34A" opacity="0.15"/>
                <circle cx="100" cy="100" r="60" fill="none" stroke="#16A34A" stroke-width="2" opacity="0.3" stroke-dasharray="6 6"/>
            </svg>
        </div>
        <div class="relative max-w-xs">
            <div class="w-10 h-10 rounded-xl bg-white grid place-items-center text-[#166534] mb-4">
                <x-icon name="gift" :size="18" />
            </div>
            <h3 class="text-lg font-extrabold tracking-tight leading-tight mb-2">
                Yuk bagikan lebih banyak barang
            </h3>
            <p class="text-xs text-[#647164] leading-relaxed mb-4">
                Semakin banyak barang yang kamu bagikan, semakin besar manfaat yang tersambung ke orang lain.
            </p>
            <a href="{{ route('supplier.items.create') }}"
               class="inline-flex items-center gap-1.5 h-10 px-4 rounded-xl bg-[#166534] text-white text-xs font-bold hover:bg-[#14532d] transition">
                Tambah Barang
                <x-icon name="arrow-right" :size="13" />
            </a>
        </div>
    </div>

    <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
        <h3 class="font-extrabold tracking-tight text-sm mb-3">Kategori Populer</h3>
        <div class="space-y-1">
            @foreach ($popularCategories as $cat)
                <div class="flex items-center gap-3 px-2 py-2.5 rounded-lg">
                    <div class="w-8 h-8 rounded-lg bg-[#DCFCE7] grid place-items-center text-base">
                        {{ $cat->emoji ?? '📦' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-[13px] font-bold truncate">{{ $cat->name }}</div>
                        <div class="text-[10.5px] text-[#647164]">{{ $cat->items_count }} barang</div>
                    </div>
                </div>
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
        <a href="{{ route('supplier.requests.index') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
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

{{-- ============ PERMINTAAN MASUK ============ --}}
<div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-extrabold tracking-tight text-sm">Permintaan Masuk</h3>
        <a href="{{ route('supplier.requests.index') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
            Lihat semua →
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

{{-- ============ STOK HAMPIR HABIS ============ --}}
<div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-extrabold tracking-tight text-sm">Stok Hampir Habis</h3>
        <a href="{{ route('supplier.items.index') }}" class="text-[11px] font-bold text-[#166534] hover:underline">
            Lihat semua →
        </a>
    </div>

    @if ($lowStock->isEmpty())
        <div class="text-center py-5">
            <p class="text-[11px] text-[#647164]">Semua stok masih aman 👍</p>
        </div>
    @else
        <div class="space-y-1">
            @foreach ($lowStock as $it)
                <a href="{{ route('supplier.items.edit', $it) }}"
                   class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-[#F0FDF4] transition">
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
    /* Filter client-side sederhana untuk kotak "Cari di barang saya" */
    document.getElementById('myItemSearch')?.addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase().trim();
        document.querySelectorAll('#myItemsGrid > div').forEach(card => {
            const name = card.querySelector('h3')?.textContent.toLowerCase() || '';
            card.style.display = name.includes(q) ? '' : 'none';
        });
    });
</script>
@endpush