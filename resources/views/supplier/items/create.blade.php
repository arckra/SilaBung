@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang')
@section('page-subtitle', 'Bagikan barang yang sudah tidak digunakan')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')

<form method="POST" action="{{ route('supplier.items.store') }}" enctype="multipart/form-data" class="max-w-3xl">
    @csrf

    {{-- Error --}}
    @if ($errors->any())
        <div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <div class="font-bold mb-1">Periksa kembali data kamu:</div>
            <ul class="list-disc list-inside space-y-0.5 text-[13px]">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- Section: Informasi barang --}}
    <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5 mb-4">
        <h2 class="font-extrabold tracking-tight mb-4">Informasi Barang</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold mb-1.5">Nama barang <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Kardus Bekas"
                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
            </div>

            <div class="grid sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-bold mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required
                            class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm bg-white">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                {{ $cat->emoji }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1.5">Kondisi <span class="text-red-500">*</span></label>
                    <select name="condition" required
                            class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm bg-white">
                        @foreach (['Baik', 'Cukup', 'Perlu Perbaikan'] as $c)
                            <option value="{{ $c }}" @selected(old('condition') === $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-bold mb-1.5">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required
                           class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1.5">Satuan <span class="text-red-500">*</span></label>
                    <select name="unit" required
                            class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm bg-white">
                        @foreach (['pcs', 'kg', 'liter', 'ikat', 'set', 'karung'] as $u)
                            <option value="{{ $u }}" @selected(old('unit') === $u)>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Ceritakan kondisi barang, misalnya: kardus bekas pengiriman yang masih layak digunakan."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm resize-y">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold mb-1.5">Foto barang <span class="text-[#647164] font-medium">(opsional)</span></label>
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm file:mr-3 file:px-4 file:h-10 file:rounded-xl file:border-0 file:bg-[#DCFCE7] file:text-[#166534] file:font-bold file:cursor-pointer hover:file:bg-[#bbf7d0]">
                <p class="text-[11px] text-[#647164] mt-1.5">Kalau tidak ada, kami pakai ikon kategori sebagai gambar.</p>
            </div>
        </div>
    </div>

    {{-- Section: Lokasi --}}
    <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5 mb-4">
        <div class="flex items-start justify-between gap-3 flex-wrap mb-4">
            <div>
                <h2 class="font-extrabold tracking-tight">Lokasi Barang</h2>
                <p class="text-xs text-[#647164] mt-0.5">Digunakan customer untuk melihat jarak ke lokasimu.</p>
            </div>
            <button type="button" id="useGeoBtn"
                    class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-[#DCFCE7] text-[#166534] text-xs font-bold hover:bg-[#bbf7d0] transition">
                <x-icon name="navigation" :size="13" />
                Pakai lokasi saya
            </button>
        </div>

        <div class="grid sm:grid-cols-2 gap-3.5 mb-3.5">
            <div>
                <label class="block text-xs font-bold mb-1.5">Kota / Kecamatan</label>
                <input type="text" name="city" id="cityInput" value="{{ old('city', auth()->user()->city) }}"
                       placeholder="Cikarang Selatan"
                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold mb-1.5">Alamat singkat</label>
                <input type="text" name="address" value="{{ old('address', auth()->user()->address) }}"
                       placeholder="Jl. Industri No. 12"
                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-3.5 mb-3.5">
            <div>
                <label class="block text-xs font-bold mb-1.5">Latitude</label>
                <input type="text" name="latitude" id="latInput" readonly
                       value="{{ old('latitude', auth()->user()->latitude) }}"
                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] bg-[#F8FAF8] text-sm text-[#647164]">
            </div>
            <div>
                <label class="block text-xs font-bold mb-1.5">Longitude</label>
                <input type="text" name="longitude" id="lngInput" readonly
                       value="{{ old('longitude', auth()->user()->longitude) }}"
                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] bg-[#F8FAF8] text-sm text-[#647164]">
            </div>
        </div>

        <div id="mapPicker" class="h-64 rounded-xl overflow-hidden border border-[#E3EAE3] bg-[#EAF0EA]"></div>
        <p class="text-[11px] text-[#647164] mt-2">
            Klik peta untuk menggeser pin. Kalau dibiarkan, lokasi akan diambil dari profilmu.
        </p>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-2.5">
        <a href="{{ route('supplier.items.index') }}"
           class="h-11 px-5 rounded-xl border border-[#E3EAE3] bg-white text-sm font-bold hover:bg-[#F8FAF8] transition grid place-items-center">
            Batal
        </a>
        <button type="submit"
                class="h-11 px-6 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
            Simpan Barang
        </button>
    </div>
</form>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const latInput = document.getElementById('latInput');
            const lngInput = document.getElementById('lngInput');
            const geoBtn   = document.getElementById('useGeoBtn');

            const defaultLat = parseFloat(latInput.value) || -6.2;
            const defaultLng = parseFloat(lngInput.value) || 106.8166;

            const map = L.map('mapPicker').setView([defaultLat, defaultLng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

            const syncInputs = (lat, lng) => {
                latInput.value = lat.toFixed(7);
                lngInput.value = lng.toFixed(7);
            };

            // Kalau field sudah ada nilai, set marker ke sana.
            if (latInput.value && lngInput.value) {
                const lat = parseFloat(latInput.value);
                const lng = parseFloat(lngInput.value);
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 14);
            }

            map.on('click', (e) => {
                marker.setLatLng(e.latlng);
                syncInputs(e.latlng.lat, e.latlng.lng);
            });

            marker.on('dragend', () => {
                const p = marker.getLatLng();
                syncInputs(p.lat, p.lng);
            });

            geoBtn.addEventListener('click', () => {
                if (!navigator.geolocation) return alert('Browser tidak mendukung geolokasi.');
                geoBtn.disabled = true;
                geoBtn.innerHTML = 'Mendeteksi...';

                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const { latitude, longitude } = pos.coords;
                        marker.setLatLng([latitude, longitude]);
                        map.setView([latitude, longitude], 15);
                        syncInputs(latitude, longitude);
                        geoBtn.disabled = false;
                        geoBtn.innerHTML = '✓ Terdeteksi';
                    },
                    () => {
                        geoBtn.disabled = false;
                        geoBtn.innerHTML = 'Gagal — geser pin manual';
                        setTimeout(() => geoBtn.innerHTML = 'Pakai lokasi saya', 2500);
                    },
                    { timeout: 8000 }
                );
            });
        });
    </script>
@endpush