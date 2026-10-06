@extends('layouts.app')

@section('title', 'Cari Barang')
@section('page-title', 'Cari Barang')
@section('page-subtitle', 'Temukan barang bekas yang masih berguna')

@section('content')

<div class="bg-white border border-[#E3EAE3] rounded-2xl p-5 mb-5 shadow-sm">
    <form method="GET" action="{{ route('customer.search') }}">
        <div class="flex gap-2.5">
            <div class="relative flex-1">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#647164] pointer-events-none">
                    <x-icon name="search" :size="18" />
                </span>
                <input type="text" name="q" value="{{ $term }}" autofocus
                       placeholder="Cari kardus, botol plastik, kayu, pakaian..."
                       class="w-full h-12 pl-11 pr-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
            </div>
            <button type="submit"
                    class="h-12 px-6 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
                Cari
            </button>
        </div>

        <div class="grid sm:grid-cols-2 gap-3 mt-4">
            <div>
                <label class="block text-xs font-bold mb-1.5">Kategori</label>
                <select name="category"
                        class="w-full h-10 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm bg-white">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->slug }}" @selected($catSlug === $cat->slug)>
                            {{ $cat->emoji }} {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold mb-1.5">Kondisi</label>
                <select name="condition"
                        class="w-full h-10 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm bg-white">
                    <option value="">Semua kondisi</option>
                    @foreach (['Baik', 'Cukup', 'Perlu Perbaikan'] as $c)
                        <option value="{{ $c }}" @selected($condition === $c)>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>

<div class="mb-4">
    <p class="text-xs text-[#647164]">{{ $items->count() }} barang ditemukan</p>
</div>

@if ($items->isEmpty())
    <div class="bg-white border border-[#E3EAE3] rounded-2xl">
        <x-empty-state
            icon="search"
            title="Belum ada barang yang cocok"
            description="Coba ubah kata kunci atau filter."
            actionLabel="Reset pencarian"
            :actionHref="route('customer.search')" />
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach ($items as $item)
            <x-item-card :item="$item" :distanceKm="$item->distance_km" />
        @endforeach
    </div>
@endif

@endsection