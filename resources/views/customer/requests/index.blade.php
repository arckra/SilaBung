@extends('layouts.app')

@section('title', 'Permintaan Saya')
@section('page-title', 'Permintaan Saya')
@section('page-subtitle', 'Status permintaan pengambilan barangmu')

@section('page-actions')
    <a href="{{ route('customer.search') }}"
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
        <x-icon name="search" :size="15" />
        Cari Barang
    </a>
@endsection

@section('content')

@if ($requests->isEmpty())
    <div class="bg-white border border-[#E3EAE3] rounded-2xl">
        <x-empty-state
            icon="inbox"
            title="Belum ada permintaan"
            description="Kamu belum pernah mengajukan permintaan pengambilan barang. Yuk cari barang yang kamu butuhkan."
            actionLabel="Cari Barang"
            :actionHref="route('customer.search')" />
    </div>
@else
    <div class="space-y-3">
        @foreach ($requests as $req)
            @php
                $badgeClass = [
                    'pending'   => 'bg-amber-100 text-amber-700',
                    'accepted'  => 'bg-[#DCFCE7] text-[#166534]',
                    'rejected'  => 'bg-red-50 text-red-700',
                    'completed' => 'bg-sky-100 text-sky-700',
                    'cancelled' => 'bg-[#F1F5F1] text-[#647164]',
                ][$req->status] ?? 'bg-[#F1F5F1] text-[#647164]';
            @endphp

            <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
                <div class="flex flex-wrap gap-4 items-start">

                    {{-- Icon --}}
                    <div class="w-14 h-14 rounded-2xl bg-[#DCFCE7] grid place-items-center text-2xl shrink-0">
                        {{ $req->item->category->emoji ?? '📦' }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-[220px]">
                        <div class="flex items-center gap-2 flex-wrap mb-1.5">
                            <h3 class="font-extrabold tracking-tight">{{ $req->item->name }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $badgeClass }}">
                                {{ $req->statusLabel() }}
                            </span>
                        </div>

                        <div class="text-xs text-[#647164] space-y-1">
                            <div>Supplier: <b class="text-[#172117]">{{ $req->item->supplier->name }}</b></div>
                            <div>Jumlah diminta: <b class="text-[#172117]">{{ $req->quantity }} {{ $req->item->unit }}</b></div>
                            <div>Diajukan: {{ $req->created_at->format('d M Y, H:i') }}</div>
                            @if ($req->responded_at)
                                <div>Direspons: {{ $req->responded_at->format('d M Y, H:i') }}</div>
                            @endif
                        </div>

                        @if ($req->note)
                            <div class="mt-2.5 px-3 py-2 rounded-lg bg-[#F8FAF8] border border-[#E3EAE3] text-xs text-[#647164] italic">
                                "{{ $req->note }}"
                            </div>
                        @endif
                    </div>

                    {{-- Aksi --}}
                    <div class="flex flex-col gap-2 shrink-0">
                        <a href="{{ route('customer.items.show', $req->item) }}"
                           class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg border border-[#E3EAE3] bg-white text-xs font-bold hover:border-[#16A34A] hover:text-[#166534] transition">
                            Lihat Barang
                        </a>
                        @if ($req->isPending())
                            <form method="POST" action="{{ route('customer.requests.cancel', $req) }}"
                                  onsubmit="return confirm('Batalkan permintaan ini?')">
                                @csrf @method('PATCH')
                                <button class="w-full inline-flex items-center justify-center gap-1.5 h-9 px-3.5 rounded-lg border border-red-200 bg-red-50 text-xs font-bold text-red-700 hover:bg-red-100 transition">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($requests->hasPages())
        <div class="mt-5">{{ $requests->links() }}</div>
    @endif
@endif

@endsection