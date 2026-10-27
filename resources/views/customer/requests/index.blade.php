@extends('layouts.app')

@section('title', 'Permintaan Saya')
@section('page-title', 'Permintaan Saya')
@section('page-subtitle', 'Status permintaan pengambilan barangmu')

@section('page-actions')
    <a href="{{ route('customer.search') }}"
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
        <x-icon name="search" :size="15" />
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

                        {{-- TOMBOL KODE AMBIL (Hanya muncul jika status accepted) --}}
                        @if ($req->status === 'accepted')
                            <button type="button"
                                    onclick="openPickupModal(this)"
                                    data-code="{{ $req->pickup_code }}"
                                    data-item="{{ $req->item->name }}"
                                    data-qty="{{ $req->quantity }} {{ $req->item->unit }}"
                                    data-supplier="{{ $req->item->supplier->name }}"
                                    data-phone="{{ $req->item->supplier->phone ?? 'Belum ada nomor' }}"
                                    data-location="{{ $req->item->address ?? $req->item->city ?? 'Lokasi tidak tersedia' }}"
                                    data-url="{{ route('customer.requests.complete', $req) }}"
                                    class="inline-flex items-center justify-center gap-1.5 h-9 px-3.5 rounded-lg bg-[#16A34A] text-white text-xs font-bold hover:bg-[#166534] transition shadow-sm shadow-green-600/20">
                                <x-icon name="check" :size="14" />
                                Kode Ambil
                            </button>
                        @endif
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

{{-- ============ MODAL: PETUNJUK PENGAMBILAN ============ --}}
<div id="pickupModal" class="fixed inset-0 z-[999] hidden items-center justify-center p-5"
     style="background: rgba(23,33,23,.5); backdrop-filter: blur(3px);">
    
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[480px] p-6 sm:p-8 animate-[pop_.2s_ease-out]">
        
        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h3 class="font-extrabold tracking-tight text-xl text-[#172117]">Petunjuk Pengambilan Barang</h3>
                <p class="text-xs text-[#647164] mt-1">Tunjukkan kode verifikasi ini saat mengambil barang di lokasi supplier.</p>
            </div>
            <button type="button" onclick="closePickupModal()" class="w-8 h-8 rounded-full bg-[#F1F5F1] grid place-items-center text-[#647164] hover:bg-[#E3EAE3] transition shrink-0">
                <x-icon name="x" :size="16" />
            </button>
        </div>

        {{-- Kode Box --}}
        <div class="bg-[#F0FDF4] border-2 border-[#DCFCE7] rounded-2xl p-5 text-center mb-5 relative overflow-hidden">
            <div class="text-[10px] font-extrabold uppercase tracking-widest text-[#166534] mb-2">Kode Verifikasi Pengambilan</div>
            <div class="text-3xl font-extrabold tracking-[0.1em] text-[#14532d] mb-1 font-mono" id="modalCode">SLB-XX-0000</div>
            <div class="text-sm font-semibold text-[#166534]" id="modalItemName">Nama Barang</div>
        </div>

        {{-- Detail List --}}
        <div class="space-y-3 mb-6">
            
            {{-- Lokasi --}}
            <div class="flex gap-3.5 p-3.5 rounded-xl bg-[#F8FAF8] border border-[#E3EAE3]">
                <div class="w-8 h-8 rounded-full bg-white border border-[#E3EAE3] grid place-items-center text-[#166534] shrink-0 mt-0.5">
                    <x-icon name="map-pin" :size="14" />
                </div>
                <div>
                    <div class="text-[11px] font-extrabold uppercase tracking-wider text-[#647164] mb-0.5">Titik Temu / Lokasi:</div>
                    <div class="text-sm font-semibold text-[#172117] leading-snug" id="modalLocation">Alamat Supplier</div>
                </div>
            </div>

            {{-- Kontak --}}
            <div class="flex gap-3.5 p-3.5 rounded-xl bg-[#F8FAF8] border border-[#E3EAE3]">
                <div class="w-8 h-8 rounded-full bg-white border border-[#E3EAE3] grid place-items-center text-[#166534] shrink-0 mt-0.5">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold uppercase tracking-wider text-[#647164] mb-0.5">Kontak Supplier:</div>
                    <div class="text-sm font-semibold text-[#172117] leading-snug" id="modalContact">Nama & No HP</div>
                </div>
            </div>

            {{-- Jadwal --}}
            <div class="flex gap-3.5 p-3.5 rounded-xl bg-[#F8FAF8] border border-[#E3EAE3]">
                <div class="w-8 h-8 rounded-full bg-white border border-[#E3EAE3] grid place-items-center text-[#166534] shrink-0 mt-0.5">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold uppercase tracking-wider text-[#647164] mb-0.5">Jadwal Pengambilan:</div>
                    <div class="text-sm font-semibold text-[#172117] leading-snug">Senin - Sabtu: 09:00 - 17:00 WIB <span class="text-[#647164] font-normal">(Harap konfirmasi kedatangan terlebih dahulu)</span></div>
                </div>
            </div>

        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-3 pt-4 border-t border-[#E3EAE3]">
            <button type="button" onclick="closePickupModal()" class="text-xs font-bold text-[#647164] hover:text-[#172117] transition">
                Tutup
            </button>
            <form method="POST" id="modalCompleteForm" action="">
                @csrf
                @method('PATCH')
                <button type="submit" onclick="return confirm('Konfirmasi bahwa kamu sudah mengambil barang ini?')"
                        class="h-10 px-5 rounded-xl bg-[#16A34A] text-white text-xs font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
                    Tandai Pengambilan Selesai
                </button>
            </form>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function openPickupModal(btn) {
        // Ambil data dari atribut tombol
        const code = btn.dataset.code;
        const item = btn.dataset.item;
        const qty = btn.dataset.qty;
        const supplier = btn.dataset.supplier;
        const phone = btn.dataset.phone;
        const location = btn.dataset.location;
        const url = btn.dataset.url;

        // Isi data ke dalam modal
        document.getElementById('modalCode').textContent = code;
        document.getElementById('modalItemName').textContent = `${qty} ${item}`;
        document.getElementById('modalLocation').textContent = location;
        document.getElementById('modalContact').textContent = `${supplier} (${phone})`;
        
        // Set action form untuk tombol "Selesai"
        document.getElementById('modalCompleteForm').action = url;

        // Tampilkan modal
        const modal = document.getElementById('pickupModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePickupModal() {
        const modal = document.getElementById('pickupModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush

@endsection