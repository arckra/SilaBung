@extends('layouts.app')

@section('title', 'Favorit')
@section('page-title', 'Favorit')
@section('page-subtitle', 'Barang yang kamu simpan untuk dilihat nanti')

@section('page-actions')
    <a href="{{ route('customer.search') }}"
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
        <x-icon name="search" :size="15" />
        Cari Barang
    </a>
@endsection

@section('content')

@if ($items->isEmpty())
    <div class="bg-white border border-[#E3EAE3] rounded-2xl">
        <x-empty-state
            icon="heart"
            title="Belum ada barang favorit"
            description="Klik ikon hati di barang mana pun untuk menyimpannya di sini. Kamu bisa melihatnya lagi nanti tanpa harus mencari."
            actionLabel="Cari Barang"
            :actionHref="route('customer.search')" />
    </div>
@else
    <div class="mb-4">
        <p class="text-xs text-[#647164]">
            <b class="text-[#172117]">{{ $items->count() }}</b> barang tersimpan
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach ($items as $item)
            <x-item-card :item="$item" :distanceKm="$item->distance_km" />
        @endforeach
    </div>
@endif

@endsection