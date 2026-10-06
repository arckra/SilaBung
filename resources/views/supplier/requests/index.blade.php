@extends('layouts.app')

@section('title', 'Permintaan Masuk')
@section('page-title', 'Permintaan Masuk')
@section('page-subtitle', 'Kelola permintaan dari customer')

@section('content')

{{-- Tab status --}}
<div class="inline-flex gap-1 bg-white border border-[#E3EAE3] rounded-xl p-1 mb-5 overflow-x-auto max-w-full">
    @php
        $tabs = [
            'pending'   => ['label' => 'Menunggu', 'count' => $counts['pending']],
            'accepted'  => ['label' => 'Diterima', 'count' => $counts['accepted']],
            'rejected'  => ['label' => 'Ditolak',  'count' => $counts['rejected']],
            'all'       => ['label' => 'Semua',    'count' => null],
        ];
    @endphp
    @foreach ($tabs as $key => $t)
        <a href="{{ route('supplier.requests.index', ['status' => $key]) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold whitespace-nowrap transition
                  {{ $status === $key ? 'bg-[#DCFCE7] text-[#166534]' : 'text-[#647164] hover:text-[#166534]' }}">
            {{ $t['label'] }}
            @if ($t['count'] !== null)
                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $status === $key ? 'bg-[#166534] text-white' : 'bg-[#F1F5F1] text-[#647164]' }}">
                    {{ $t['count'] }}
                </span>
            @endif
        </a>
    @endforeach
</div>

@if ($requests->isEmpty())
    <div class="bg-white border border-[#E3EAE3] rounded-2xl">
        <x-empty-state
            icon="inbox"
            title="{{ $status === 'pending' ? 'Belum ada permintaan masuk' : 'Tidak ada permintaan di kategori ini' }}"
            description="{{ $status === 'pending' ? 'Permintaan dari customer akan muncul di sini.' : 'Coba cek tab status yang lain.' }}" />
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

                    {{-- Customer avatar --}}
                    <div class="w-12 h-12 rounded-full bg-[#166534] text-white grid place-items-center font-extrabold shrink-0">
                        {{ strtoupper(substr($req->customer->name, 0, 2)) }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-[240px]">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="font-extrabold tracking-tight">{{ $req->customer->name }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $badgeClass }}">
                                {{ $req->statusLabel() }}
                            </span>
                        </div>

                        <div class="text-sm text-[#647164] mt-1 leading-relaxed">
                            Meminta
                            <b class="text-[#172117]">{{ $req->quantity }} {{ $req->item->unit }}</b>
                            <b class="text-[#172117]">{{ $req->item->name }}</b>
                        </div>

                        <div class="text-xs text-[#647164] mt-2 space-y-0.5">
                            <div>Diajukan: {{ $req->created_at->format('d M Y, H:i') }}</div>
                            @if ($req->customer->city)
                                <div>Lokasi customer: {{ $req->customer->city }}</div>
                            @endif
                            @if ($req->responded_at)
                                <div>Direspons: {{ $req->responded_at->format('d M Y, H:i') }}</div>
                            @endif
                        </div>

                        @if ($req->note)
                            <div class="mt-2.5 px-3 py-2 rounded-lg bg-[#F8FAF8] border border-[#E3EAE3] text-xs text-[#647164] italic">
                                "{{ $req->note }}"
                            </div>
                        @endif

                        {{-- Warning stok kalau pending --}}
                        @if ($req->isPending() && $req->quantity > $req->item->quantity)
                            <div class="mt-2.5 px-3 py-2 rounded-lg bg-red-50 border border-red-200 text-xs text-red-700 font-semibold">
                                ⚠ Stok sekarang tinggal {{ $req->item->quantity }} {{ $req->item->unit }} — tidak cukup untuk permintaan ini.
                            </div>
                        @endif
                    </div>

                    {{-- Aksi --}}
                    <div class="flex flex-col gap-2 shrink-0">
                        @if ($req->isPending())
                            <form method="POST" action="{{ route('supplier.requests.accept', $req) }}">
                                @csrf @method('PATCH')
                                <button class="w-full inline-flex items-center justify-center gap-1.5 h-10 px-4 rounded-lg bg-[#16A34A] text-white text-xs font-extrabold shadow-sm shadow-green-600/20 hover:bg-[#166534] transition">
                                    <x-icon name="check" :size="14" />
                                    Terima
                                </button>
                            </form>
                            <form method="POST" action="{{ route('supplier.requests.reject', $req) }}"
                                  onsubmit="return confirm('Tolak permintaan ini?')">
                                @csrf @method('PATCH')
                                <button class="w-full inline-flex items-center justify-center gap-1.5 h-10 px-4 rounded-lg border border-red-200 bg-red-50 text-red-700 text-xs font-extrabold hover:bg-red-100 transition">
                                    <x-icon name="x" :size="14" />
                                    Tolak
                                </button>
                            </form>
                        @elseif ($req->status === 'accepted')
                            <form method="POST" action="{{ route('supplier.requests.complete', $req) }}">
                                @csrf @method('PATCH')
                                <button class="w-full inline-flex items-center justify-center gap-1.5 h-10 px-4 rounded-lg bg-[#166534] text-white text-xs font-extrabold hover:bg-[#14532d] transition">
                                    <x-icon name="check" :size="14" />
                                    Tandai Selesai
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