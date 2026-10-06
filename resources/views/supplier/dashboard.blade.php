@extends('layouts.app')

@section('title', 'Dashboard Supplier')
@section('page-title', 'Bagikan barang yang masih punya nilai')
@section('page-subtitle', 'Kelola stok dan permintaan dari customer')

@section('page-actions')
    <a href="{{ route('supplier.items.create') }}"
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
        <x-icon name="plus" :size="15" />
        Tambah Barang
    </a>
@endsection

@section('content')

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
    <x-stat-card label="Total barang"        :value="$totalItems"     icon="package"     tone="green" />
    <x-stat-card label="Barang tersedia"      :value="$availableItems" icon="check"       tone="blue" />
    <x-stat-card label="Permintaan masuk"     :value="$pendingReqs"    icon="inbox"       tone="amber" />
    <x-stat-card label="Barang berhasil diberikan" :value="$givenAway" icon="gift"        tone="purple" />
</div>

{{-- Barang terbaru --}}
<div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden mb-6">
    <div class="flex items-center justify-between px-5 py-4 border-b border-[#E3EAE3]">
        <div>
            <h2 class="font-extrabold tracking-tight">Barang Terbaru</h2>
            <p class="text-xs text-[#647164] mt-0.5">5 barang terakhir yang kamu bagikan</p>
        </div>
        <a href="{{ route('supplier.items.index') }}"
           class="text-xs font-bold text-[#166534] hover:underline">Lihat semua →</a>
    </div>

    @if ($recentItems->isEmpty())
        <x-empty-state
            icon="package"
            title="Belum ada barang yang kamu bagikan"
            description="Yuk mulai bagikan barang yang sudah tidak kamu gunakan."
            actionLabel="Tambah Barang"
            :actionHref="route('supplier.items.create')" />
    @else
        <div class="divide-y divide-[#E3EAE3]">
            @foreach ($recentItems as $item)
                <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-[#FBFDFB] transition">
                    <div class="w-11 h-11 rounded-xl grid place-items-center text-xl"
                         style="background-color: {{ $item->category->emoji ? '#DCFCE7' : '#F1F5F1' }};">
                        {{ $item->category->emoji ?? '📦' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-sm truncate">{{ $item->name }}</div>
                        <div class="text-[11px] text-[#647164]">
                            {{ $item->category->name }} • {{ $item->condition }}
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="font-extrabold text-sm">{{ $item->quantity }} {{ $item->unit }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-wider
                            {{ $item->status === 'available' ? 'text-[#166534]' : 'text-[#647164]' }}">
                            {{ $item->status === 'available' ? 'Tersedia' : 'Habis' }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Permintaan terbaru --}}
<div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-[#E3EAE3]">
        <div>
            <h2 class="font-extrabold tracking-tight">Permintaan Terbaru</h2>
            <p class="text-xs text-[#647164] mt-0.5">Permintaan yang masuk ke barangmu</p>
        </div>
        <a href="{{ route('supplier.requests.index') }}"
           class="text-xs font-bold text-[#166534] hover:underline">Lihat semua →</a>
    </div>

    @if ($recentRequests->isEmpty())
        <x-empty-state
            icon="inbox"
            title="Belum ada permintaan"
            description="Permintaan dari customer akan muncul di sini." />
    @else
        <div class="divide-y divide-[#E3EAE3]">
            @foreach ($recentRequests as $req)
                <div class="flex items-center gap-3 px-5 py-3.5">
                    <div class="w-9 h-9 rounded-full bg-[#166534] text-white grid place-items-center text-[11px] font-extrabold">
                        {{ strtoupper(substr($req->customer->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-sm truncate">{{ $req->customer->name }}</div>
                        <div class="text-[11px] text-[#647164] truncate">
                            minta <b class="text-[#172117]">{{ $req->quantity }} {{ $req->item->unit }}</b> {{ $req->item->name }}
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                        @if($req->status === 'pending') bg-amber-100 text-amber-700
                        @elseif($req->status === 'accepted') bg-[#DCFCE7] text-[#166534]
                        @elseif($req->status === 'rejected') bg-red-50 text-red-700
                        @else bg-sky-100 text-sky-700 @endif">
                        {{ $req->statusLabel() }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection