@extends('layouts.app')

@section('title', $item->name)
@section('page-title', 'Detail Barang')
@section('page-subtitle', $item->name)

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')

<a href="{{ url()->previous() }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#647164] hover:text-[#166534] mb-4">
    ← Kembali
</a>

<div class="grid lg:grid-cols-3 gap-5">

    {{-- ================= KIRI ================= --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Foto --}}
        <div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden">
            <div class="h-72 grid place-items-center"
                 style="background-color: {{ ['kardus'=>'#FEF3C7','plastik'=>'#CFFAFE','botol'=>'#DBEAFE','kertas'=>'#F1F5F9','kaca'=>'#CCFBF1','kayu'=>'#FDE68A','logam'=>'#E2E8F0','elektronik'=>'#EDE9FE','pakaian'=>'#FCE7F3'][$item->category->slug] ?? '#DCFCE7' }};">
                @if ($item->image_path)
                    <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-8xl">{{ $item->category->emoji ?? '📦' }}</span>
                @endif
            </div>
        </div>

        {{-- Info utama --}}
        <div class="bg-white border border-[#E3EAE3] rounded-2xl p-6">
            <div class="flex flex-wrap gap-2 mb-3">
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#F1F5F1] text-[#647164]">
                    {{ $item->category->name }}
                </span>
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold
                    {{ $item->condition === 'Baik' ? 'bg-[#DCFCE7] text-[#166534]' : ($item->condition === 'Cukup' ? 'bg-amber-100 text-amber-700' : 'bg-red-50 text-red-700') }}">
                    Kondisi: {{ $item->condition }}
                </span>
            </div>

            <div class="flex items-start justify-between gap-3 mb-3">
                <h1 class="text-2xl font-extrabold tracking-tight">{{ $item->name }}</h1>
            
                @php
                    $isFav = auth()->check() && auth()->user()->favorites()->where('item_id', $item->id)->exists();
                @endphp
                <form method="POST"
                      action="{{ route('customer.items.favorite', $item) }}"
                      class="shrink-0">
                    @csrf
                    <button type="submit"
                            class="w-10 h-10 rounded-xl border grid place-items-center transition cursor-pointer hover:scale-105
                                   {{ $isFav ? 'text-red-500 border-red-200 bg-red-50' : 'text-[#647164] border-[#E3EAE3] bg-white hover:text-red-500 hover:border-red-200' }}"
                            title="{{ $isFav ? 'Hapus dari favorit' : 'Simpan ke favorit' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24"
                             fill="{{ $isFav ? 'currentColor' : 'none' }}"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             style="pointer-events: none;">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
                </form>
            </div>

            <p class="text-sm text-[#647164] leading-relaxed">
                {{ $item->description ?: 'Tidak ada deskripsi.' }}
            </p>

            <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-[#E3EAE3]">
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-[#647164] mb-1">Jumlah tersedia</div>
                    <div class="text-2xl font-extrabold tracking-tight">
                        {{ $item->quantity }}
                        <span class="text-base text-[#647164] font-bold">{{ $item->unit }}</span>
                    </div>
                </div>
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-[#647164] mb-1">Kondisi</div>
                    <div class="text-2xl font-extrabold tracking-tight">{{ $item->condition }}</div>
                </div>
            </div>
        </div>

        {{-- Peta --}}
        <div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[#E3EAE3] flex items-center justify-between gap-3 flex-wrap">
                <div>
                    <h2 class="font-extrabold tracking-tight">Lokasi Supplier</h2>
                    <p class="text-xs text-[#647164] mt-0.5">
                        {{ $item->address ?: ($item->city ?: 'Lokasi belum tersedia') }}
                    </p>
                </div>
                @if ($distanceKm !== null)
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-[#DCFCE7] text-[#166534]">
                        {{ \App\Support\Geo::formatKm($distanceKm) }} dari kamu
                    </span>
                @endif
            </div>

            @if ($item->latitude && $item->longitude)
                <div id="itemMap" class="h-72 bg-[#EAF0EA] relative z-0"></div>
                <div class="px-5 py-3 border-t border-[#E3EAE3] flex flex-wrap gap-5 text-xs text-[#647164]">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-[#16A34A] border-2 border-white shadow"></span>
                        Supplier
                    </div>
                    @if ($distanceKm !== null)
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-[#0E7490] border-2 border-white shadow"></span>
                            Kamu
                        </div>
                    @endif
                    <div class="ml-auto italic">Jarak perkiraan (garis lurus)</div>
                </div>
            @else
                <div class="p-8 text-center text-sm text-[#647164]">
                    Lokasi supplier belum tersedia.
                </div>
            @endif
        </div>
    </div>

    {{-- ================= KANAN ================= --}}
    <div class="lg:col-span-1">
        <div class="lg:sticky lg:top-24 space-y-4">

            {{-- Supplier card --}}
            <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
                <div class="text-[11px] font-bold uppercase tracking-wider text-[#647164] mb-3">Supplier</div>
                <div class="flex items-center gap-3">
                    <x-avatar :user="$item->supplier" :size="48" rounded="2xl" />
                    <div class="min-w-0">
                        <div class="font-bold truncate">{{ $item->supplier->name }}</div>
                        <div class="text-xs text-[#647164] truncate">
                            {{ $item->supplier->city ?: 'Lokasi belum tersedia' }}
                        </div>
                    </div>
                </div>
            </div>
            @if (auth()->id() !== $item->supplier_id)
                @if ($item->supplier->phone)
                    <a href="https://wa.me/{{ $item->supplier->whatsapp_number }}?text=Halo%20{{ urlencode($item->supplier->name) }}%2C%20saya%20tertarik%20dengan%20{{ urlencode($item->name) }}%20di%20SILABUNG."
                       target="_blank" rel="noopener"
                       class="w-full h-12 rounded-xl bg-[#16A34A] text-white text-sm font-bold
                              shadow-md shadow-green-600/20 hover:bg-[#166534] transition flex items-center justify-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Contact Us
                    </a>
                @else
                    <div class="w-full h-12 rounded-xl bg-gray-100 text-gray-500 text-sm font-bold flex items-center justify-center gap-2 cursor-not-allowed">
                        Supplier belum mengatur nomor WhatsApp
                    </div>
                @endif
            @endif
            {{-- Jarak --}}
            @if ($distanceKm !== null)
                <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5 space-y-2">
                    <div class="flex items-center gap-2 text-[#166534]">
                        <x-icon name="map-pin" :size="16" />
                        <span class="font-bold text-sm">Jarak ke Supplier</span>
                    </div>
                    <div class="text-3xl font-extrabold tracking-tight">
                        {{ \App\Support\Geo::formatKm($distanceKm) }}
                    </div>
                    @if ($eta)
                        <div class="text-xs text-[#647164]">
                            Estimasi perjalanan {{ \App\Support\Geo::formatEta($eta) }}
                        </div>
                    @endif
                    <div class="text-[11px] text-[#647164] italic">Jarak perkiraan (garis lurus)</div>
                </div>
            @endif

            {{-- CTA Request --}}
            <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
                @if ($item->quantity <= 0 || $item->status !== 'available')
                    <div class="px-4 py-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold text-center">
                        Barang ini sudah habis.
                    </div>
                @elseif (auth()->id() === $item->supplier_id)
                    <div class="px-4 py-3 rounded-xl bg-[#F1F5F1] text-[#647164] text-xs font-semibold text-center">
                        Ini barang milikmu sendiri.
                    </div>
                @else
                    <div class="text-xs text-[#647164] mb-3">
                        Butuh berapa? Ajukan permintaan pengambilan.
                    </div>
                    <button type="button"
                            onclick="openRequestModal()"
                            class="w-full h-12 rounded-xl bg-[#16A34A] text-white font-bold text-sm shadow-md shadow-green-600/20 hover:bg-[#166534] transition flex items-center justify-center gap-2">
                        <x-icon name="gift" :size="15" />
                        Ajukan Permintaan
                    </button>
                    <p class="text-[11px] text-center text-[#647164] mt-2">
                        Gratis. Tidak ada biaya apa pun.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL REQUEST ================= --}}
<div id="requestModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-5"
     style="background: rgba(23,33,23,.42); backdrop-filter: blur(3px);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[440px] p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-3 mb-5">
            <div>
                <h3 class="font-extrabold tracking-tight text-lg">Ajukan Permintaan</h3>
                <p class="text-xs text-[#647164] mt-1">{{ $item->name }} · dari {{ $item->supplier->name }}</p>
            </div>
            <button type="button" onclick="closeRequestModal()"
                    class="w-8 h-8 rounded-lg grid place-items-center text-[#647164] hover:bg-[#F1F5F1] transition">
                <x-icon name="x" :size="16" />
            </button>
        </div>

        <form method="POST" action="{{ route('customer.items.request', $item) }}" id="requestForm">
            @csrf

            {{-- Quantity picker --}}
            <div class="mb-5">
                <label class="block text-xs font-bold mb-2">Jumlah yang diminta</label>

                <div class="flex items-center gap-3">
                    <div class="inline-flex items-center rounded-xl border border-[#E3EAE3] overflow-hidden">
                        <button type="button" onclick="stepQty(-1)"
                                class="w-11 h-12 grid place-items-center text-[#172117] hover:bg-[#F0FDF4] hover:text-[#166534] transition">
                            <x-icon name="x" :size="0" />
                            <span class="text-xl font-bold leading-none">−</span>
                        </button>
                        <input type="number" name="quantity" id="qtyInput"
                               value="1" min="1" max="{{ $item->quantity }}" required
                               class="w-20 h-12 border-x border-[#E3EAE3] text-center font-extrabold text-base outline-none focus:bg-[#F0FDF4]">
                        <button type="button" onclick="stepQty(1)"
                                class="w-11 h-12 grid place-items-center text-[#172117] hover:bg-[#F0FDF4] hover:text-[#166534] transition">
                            <span class="text-xl font-bold leading-none">+</span>
                        </button>
                    </div>

                    <div class="text-xs text-[#647164]">
                        dari <b class="text-[#172117]">{{ $item->quantity }} {{ $item->unit }}</b> tersedia
                    </div>
                </div>

                <div class="flex gap-1.5 mt-3">
                    <button type="button" onclick="setQty(1)" class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-[#F1F5F1] text-[#647164] hover:bg-[#DCFCE7] hover:text-[#166534] transition">
                        1
                    </button>
                    <button type="button" onclick="setQty(5)" class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-[#F1F5F1] text-[#647164] hover:bg-[#DCFCE7] hover:text-[#166534] transition">
                        5
                    </button>
                    <button type="button" onclick="setQty({{ min(10, $item->quantity) }})" class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-[#F1F5F1] text-[#647164] hover:bg-[#DCFCE7] hover:text-[#166534] transition">
                        10
                    </button>
                    <button type="button" onclick="setQty({{ $item->quantity }})" class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-[#F1F5F1] text-[#647164] hover:bg-[#DCFCE7] hover:text-[#166534] transition">
                        Semua ({{ $item->quantity }})
                    </button>
                </div>
            </div>

            {{-- Note --}}
            <div class="mb-5">
                <label class="block text-xs font-bold mb-2">
                    Catatan <span class="text-[#647164] font-medium">(opsional)</span>
                </label>
                <textarea name="note" rows="3" maxlength="500"
                          placeholder="Contoh: Saya butuh untuk tugas kampus, bisa diambil hari Sabtu."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm resize-y"></textarea>
            </div>

            {{-- Info --}}
            <div class="mb-5 px-3.5 py-3 rounded-xl bg-[#F0FDF4] border border-green-200 text-xs text-[#166534] leading-relaxed flex gap-2">
                <x-icon name="clock" :size="14" />
                <div>Setelah diajukan, supplier akan menerima notifikasi dan bisa menerima atau menolak permintaanmu.</div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2.5">
                <button type="button" onclick="closeRequestModal()"
                        class="flex-1 h-11 rounded-xl border border-[#E3EAE3] bg-white text-sm font-bold hover:bg-[#F8FAF8] transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 h-11 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
                    Kirim Permintaan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        /* ---------- Modal ---------- */
        function openRequestModal() {
            const m = document.getElementById('requestModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeRequestModal() {
            const m = document.getElementById('requestModal');
            m.classList.add('hidden');
            m.classList.remove('flex');
            document.body.style.overflow = '';
        }
        document.getElementById('requestModal').addEventListener('click', (e) => {
            if (e.target.id === 'requestModal') closeRequestModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeRequestModal();
        });

        /* ---------- Quantity ---------- */
        const maxQty = {{ $item->quantity }};
        function stepQty(delta) {
            const inp = document.getElementById('qtyInput');
            let v = parseInt(inp.value || '1', 10) + delta;
            if (v < 1) v = 1;
            if (v > maxQty) v = maxQty;
            inp.value = v;
        }
        function setQty(n) {
            if (n < 1) n = 1;
            if (n > maxQty) n = maxQty;
            document.getElementById('qtyInput').value = n;
        }

        /* ---------- Map ---------- */
        @if ($item->latitude && $item->longitude)
            document.addEventListener('DOMContentLoaded', () => {
                const supLat = {{ $item->latitude }};
                const supLng = {{ $item->longitude }};
                const userLat = {{ auth()->user()->latitude ?? 'null' }};
                const userLng = {{ auth()->user()->longitude ?? 'null' }};

                const map = L.map('itemMap').setView([supLat, supLng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);

                L.circleMarker([supLat, supLng], {
                    radius: 10, fillColor: '#16A34A', color: '#fff',
                    weight: 2.5, fillOpacity: 1
                }).addTo(map).bindPopup('<b>{{ $item->name }}</b><br>{{ $item->supplier->name }}');

                if (userLat && userLng) {
                    L.circleMarker([userLat, userLng], {
                        radius: 9, fillColor: '#0E7490', color: '#fff',
                        weight: 2.5, fillOpacity: 1
                    }).addTo(map).bindPopup('<b>Lokasi kamu</b>');

                    L.polyline(
                        [[userLat, userLng], [supLat, supLng]],
                        { color: '#166534', weight: 2, dashArray: '6,6', opacity: 0.55 }
                    ).addTo(map);

                    map.fitBounds([[userLat, userLng], [supLat, supLng]], { padding: [40, 40] });
                }
                setTimeout(() => {
                    map.invalidateSize();
                }, 200);
            });
        @endif
    </script>
@endpush