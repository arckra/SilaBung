@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Butuh sesuatu untuk digunakan kembali?')
@section('page-subtitle', 'Temukan barang bekas di sekitarmu')

@section('content')

{{-- Search hero --}}
<div class="bg-white border border-[#E3EAE3] rounded-2xl p-5 mb-6 shadow-sm">
    <form method="GET" action="{{ route('customer.search') }}">
        <div class="flex gap-2.5">
            <div class="relative flex-1">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#647164] pointer-events-none">
                    <x-icon name="search" :size="18" />
                </span>
                <input type="text" name="q" value="{{ $term }}"
                       placeholder="Cari kardus, botol plastik, kayu, pakaian..."
                       class="w-full h-12 pl-11 pr-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
            </div>
            <button type="submit"
                    class="h-12 px-6 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
                Cari
            </button>
        </div>

        {{-- Kategori chips --}}
        <div class="flex gap-2 overflow-x-auto mt-4 pb-1">
            <a href="{{ route('customer.dashboard') }}"
               class="shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold border transition
                      {{ ! $catSlug ? 'bg-[#166534] text-white border-[#166534]' : 'bg-white border-[#E3EAE3] text-[#647164] hover:border-green-300' }}">
                Semua
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('customer.dashboard', ['category' => $cat->slug]) }}"
                   class="shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold border transition
                          {{ $catSlug === $cat->slug ? 'bg-[#166534] text-white border-[#166534]' : 'bg-white border-[#E3EAE3] text-[#647164] hover:border-green-300' }}">
                    {{ $cat->emoji }} {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </form>
</div>

{{-- Section title --}}
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="font-extrabold tracking-tight">
            @if ($term)
                Hasil pencarian "{{ $term }}"
            @elseif ($catSlug)
                Kategori {{ $categories->firstWhere('slug', $catSlug)?->name }}
            @else
                Rekomendasi di sekitarmu
            @endif
        </h2>
        <p class="text-xs text-[#647164] mt-0.5">{{ $items->count() }} barang ditemukan</p>
    </div>
</div>

{{-- Grid --}}
@if ($items->isEmpty())
    <div class="bg-white border border-[#E3EAE3] rounded-2xl">
        <x-empty-state
            icon="search"
            title="Belum ada barang yang cocok"
            description="Coba ubah kata kunci atau pilih kategori lain."
            actionLabel="Lihat semua barang"
            :actionHref="route('customer.dashboard')" />
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach ($items as $item)
            <x-item-card :item="$item" :distanceKm="$item->distance_km" />
        @endforeach
    </div>
@endif

@endsection