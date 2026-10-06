@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi akun dan lokasimu')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')

@php
    $user = auth()->user();
@endphp

<div class="max-w-4xl space-y-5">

    {{-- ============ HEADER CARD ============ --}}
    <div class="bg-gradient-to-br from-[#166534] to-[#16A34A] rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white/5"></div>
        <div class="absolute -left-10 -bottom-16 w-52 h-52 rounded-full bg-white/5"></div>

        <div class="relative flex items-center gap-4 flex-wrap">
            <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur grid place-items-center text-2xl font-extrabold">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-extrabold tracking-tight truncate">{{ $user->name }}</h1>
                <p class="text-white/75 text-sm truncate">{{ $user->email }}</p>
                <div class="flex items-center gap-2 mt-2.5">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-white/20">
                        {{ $user->role }}
                    </span>
                    @if ($user->city)
                        <span class="inline-flex items-center gap-1 text-xs text-white/75">
                            <x-icon name="map-pin" :size="12" />
                            {{ $user->city }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Notifikasi sukses --}}
    @if (session('status') === 'profile-updated')
        <div class="px-4 py-3 rounded-xl bg-[#DCFCE7] border border-green-200 text-[#166534] text-sm font-semibold flex items-center gap-2">
            <x-icon name="check" :size="16" /> Profil berhasil diperbarui.
        </div>
    @endif

    {{-- ============ FORM PROFIL + LOKASI ============ --}}
    <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
        @csrf
        @method('PATCH')

        <div class="bg-white border border-[#E3EAE3] rounded-2xl p-6 mb-5">
            <h2 class="font-extrabold tracking-tight mb-5">Informasi Pribadi</h2>

            <div class="space-y-4">
                <div class="grid sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold mb-1.5">
                            Nama lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name"
                               value="{{ old('name', $user->name) }}" required
                               class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                        @error('name')
                            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email"
                               value="{{ old('email', $user->email) }}" required
                               class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                        @error('email')
                            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1.5">Role</label>
                    <div class="h-11 px-3.5 rounded-xl border border-[#E3EAE3] bg-[#F8FAF8] flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-[#DCFCE7] text-[#166534]">
                            {{ $user->role }}
                        </span>
                        <span class="text-xs text-[#647164]">Role tidak dapat diubah.</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lokasi --}}
        <div class="bg-white border border-[#E3EAE3] rounded-2xl p-6 mb-5">
            <div class="flex items-start justify-between gap-3 flex-wrap mb-4">
                <div>
                    <h2 class="font-extrabold tracking-tight">Lokasi</h2>
                    <p class="text-xs text-[#647164] mt-0.5">
                        Digunakan untuk menghitung jarak ke supplier atau customer lain.
                    </p>
                </div>
                <button type="button" id="useGeoBtn"
                        class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-[#DCFCE7] text-[#166534] text-xs font-bold hover:bg-[#bbf7d0] transition">
                    <x-icon name="navigation" :size="13" />
                    Deteksi lokasi saya
                </button>
            </div>

            <div class="grid sm:grid-cols-2 gap-3.5 mb-3.5">
                <div>
                    <label class="block text-xs font-bold mb-1.5">Kota / Kecamatan</label>
                    <input type="text" name="city" id="cityInput"
                           value="{{ old('city', $user->city) }}"
                           placeholder="Cikarang Selatan"
                           class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1.5">Alamat singkat</label>
                    <input type="text" name="address"
                           value="{{ old('address', $user->address) }}"
                           placeholder="Jl. Industri No. 12"
                           class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-3.5 mb-3.5">
                <div>
                    <label class="block text-xs font-bold mb-1.5">Latitude</label>
                    <input type="text" name="latitude" id="latInput" readonly
                           value="{{ old('latitude', $user->latitude) }}"
                           class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] bg-[#F8FAF8] text-sm text-[#647164]">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1.5">Longitude</label>
                    <input type="text" name="longitude" id="lngInput" readonly
                           value="{{ old('longitude', $user->longitude) }}"
                           class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] bg-[#F8FAF8] text-sm text-[#647164]">
                </div>
            </div>

            <div id="mapPicker" class="h-64 rounded-xl overflow-hidden border border-[#E3EAE3] bg-[#EAF0EA]"></div>
            <p class="text-[11px] text-[#647164] mt-2">
                Klik peta atau geser pin untuk menentukan lokasimu.
            </p>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="h-11 px-6 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
                Simpan Perubahan
            </button>
        </div>
    </form>

    {{-- ============ UBAH PASSWORD ============ --}}
    <div class="bg-white border border-[#E3EAE3] rounded-2xl p-6">
        <h2 class="font-extrabold tracking-tight mb-1">Ubah Password</h2>
        <p class="text-xs text-[#647164] mb-5">
            Gunakan password yang panjang dan unik agar akunmu tetap aman.
        </p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4 max-w-md">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold mb-1.5">Password saat ini</label>
                <input type="password" name="current_password" autocomplete="current-password"
                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                @error('current_password', 'updatePassword')
                    <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold mb-1.5">Password baru</label>
                <input type="password" name="password" autocomplete="new-password"
                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                @error('password', 'updatePassword')
                    <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold mb-1.5">Konfirmasi password baru</label>
                <input type="password" name="password_confirmation" autocomplete="new-password"
                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                @error('password_confirmation', 'updatePassword')
                    <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="h-11 px-5 rounded-xl bg-[#166534] text-white text-sm font-bold hover:bg-[#14532d] transition">
                Ubah Password
            </button>

            @if (session('status') === 'password-updated')
                <p class="text-xs text-[#166534] font-semibold">Password berhasil diubah.</p>
            @endif
        </form>
    </div>

    {{-- ============ HAPUS AKUN ============ --}}
    <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
        <h2 class="font-extrabold tracking-tight text-red-800 mb-1">Hapus Akun</h2>
        <p class="text-xs text-red-700/80 mb-4 max-w-lg leading-relaxed">
            Setelah akunmu dihapus, semua data akan hilang permanen — termasuk barang,
            permintaan, dan favorit. Pastikan kamu benar-benar yakin.
        </p>

        <button type="button" onclick="document.getElementById('deleteAccountModal').classList.remove('hidden'); document.getElementById('deleteAccountModal').classList.add('flex');"
                class="h-10 px-5 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition">
            Hapus Akun Saya
        </button>
    </div>
</div>

{{-- Modal Hapus Akun --}}
<div id="deleteAccountModal"
     class="fixed inset-0 z-[200] hidden items-center justify-center p-5"
     style="background: rgba(23,33,23,.5); backdrop-filter: blur(3px);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[440px] p-6">
        <h3 class="font-extrabold tracking-tight text-lg mb-1">Hapus akun?</h3>
        <p class="text-sm text-[#647164] mb-5">
            Tindakan ini tidak bisa dibatalkan. Masukkan passwordmu untuk konfirmasi.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <label class="block text-xs font-bold mb-1.5">Password</label>
            <input type="password" name="password" required
                   class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-red-500 focus:ring-2 focus:ring-red-500/15 outline-none text-sm mb-4">
            @error('password', 'userDeletion')
                <p class="text-xs text-red-600 -mt-2 mb-4 font-medium">{{ $message }}</p>
            @enderror

            <div class="flex gap-2.5">
                <button type="button"
                        onclick="document.getElementById('deleteAccountModal').classList.add('hidden'); document.getElementById('deleteAccountModal').classList.remove('flex');"
                        class="flex-1 h-11 rounded-xl border border-[#E3EAE3] bg-white text-sm font-bold hover:bg-[#F8FAF8] transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 h-11 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition">
                    Hapus Permanen
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const latInput = document.getElementById('latInput');
            const lngInput = document.getElementById('lngInput');
            const cityInput = document.getElementById('cityInput');
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
                        setTimeout(() => geoBtn.innerHTML = 'Deteksi lokasi saya', 2500);
                    },
                    { timeout: 8000 }
                );
            });
        });
    </script>
@endpush