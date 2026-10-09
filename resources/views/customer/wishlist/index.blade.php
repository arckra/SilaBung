@extends('layouts.app')

@section('title', 'Wishlist')
@section('page-title', 'Wishlist Saya')
@section('page-subtitle', 'Daftar kebutuhan yang sudah kamu cari')

@section('page-actions')
    <a href="{{ route('customer.wishlist.create') }}"
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
        <x-icon name="plus" :size="15" />
        Buat Wishlist
    </a>
@endsection

@section('content')

@if ($wishlists->isEmpty())
    <div class="bg-white border border-[#E3EAE3] rounded-2xl">
        <x-empty-state
            icon="target"
            title="Belum ada wishlist"
            description="Buat wishlist kalau kamu butuh beberapa barang sekaligus. Sistem akan otomatis mencarikan supplier terbaik."
            actionLabel="Buat Wishlist Pertama"
            :actionHref="route('customer.wishlist.create')" />
    </div>
@else
    <div class="space-y-3">
        @foreach ($wishlists as $wl)
            @php
                $statusMap = [
                    'draft'     => ['label' => 'Draft',     'class' => 'bg-[#F1F5F1] text-[#647164]'],
                    'matched'   => ['label' => 'Siap',      'class' => 'bg-amber-100 text-amber-700'],
                    'confirmed' => ['label' => 'Dikonfirmasi', 'class' => 'bg-[#DCFCE7] text-[#166534]'],
                    'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-red-50 text-red-700'],
                ];
                $st = $statusMap[$wl->status];
            @endphp

            <a href="{{ route('customer.wishlist.show', $wl) }}"
               class="block bg-white border border-[#E3EAE3] rounded-2xl p-5 hover:shadow-md hover:border-green-300 transition">
                <div class="flex items-start gap-4 flex-wrap">
                    <div class="w-11 h-11 rounded-xl bg-[#DCFCE7] text-[#166534] grid place-items-center shrink-0">
                        <x-icon name="target" :size="20" />
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <div class="flex items-center gap-2 flex-wrap mb-1.5">
                            <h3 class="font-extrabold tracking-tight">{{ $wl->title }}</h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $st['class'] }}">
                                {{ $st['label'] }}
                            </span>
                        </div>
                        <div class="text-xs text-[#647164]">
                            {{ $wl->items->count() }} barang · dibuat {{ $wl->created_at->diffForHumans() }}
                        </div>
                        <div class="text-[11px] text-[#647164] mt-1.5 truncate">
                            {{ $wl->items->pluck('category.name')->unique()->take(4)->join(', ') }}
                        </div>
                    </div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#647164" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </div>
            </a>
        @endforeach
    </div>

    @if ($wishlists->hasPages())
        <div class="mt-5">{{ $wishlists->links() }}</div>
    @endif
@endif

@endsection