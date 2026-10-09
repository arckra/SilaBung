@extends('layouts.app')

@php $user = auth()->user(); @endphp

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('page-subtitle', 'Kelola akun dan preferensi kamu')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')

<div class="max-w-2xl space-y-5">

    {{-- ============ HEADER PROFIL ============ --}}
    <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5 flex items-center gap-4">
        <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
        <div class="flex items-center gap-4 flex-wrap">

            {{-- Avatar --}}
            <div class="relative shrink-0">
                <x-avatar :user="$user" :size="80" rounded="2xl" />

                {{-- Ikon pensil kecil di pojok avatar --}}
                <button type="button"
                        onclick="document.getElementById('avatarInput').click()"
                        class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-white border border-[#E3EAE3] grid place-items-center text-[#647164] hover:bg-[#F0FDF4] hover:text-[#166534] transition shadow-sm"
                        title="{{ $user->avatar_url ? 'Ganti foto' : 'Upload foto' }}">
                    <x-icon name="edit" :size="12" />
                </button>
            </div>

            {{-- Tombol Ganti Foto & Hapus Foto --}}
            <div class="flex gap-2">
            {{-- Edit / Upload --}}
            <button type="button"
                    onclick="document.getElementById('avatarInput').click()"
                    class="w-10 h-10 rounded-xl bg-[#DCFCE7] text-[#166534] grid place-items-center hover:bg-[#bbf7d0] transition"
                    title="{{ $user->avatar_url ? 'Ganti foto' : 'Upload foto' }}">
                <x-icon name="edit" :size="16" />
            </button>

            {{-- Delete (hanya kalau sudah ada foto) --}}
            @if ($user->avatar_url)
                <form method="POST" action="{{ route('profile.avatar.destroy') }}"
                      onsubmit="return confirm('Hapus foto profil?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-10 h-10 rounded-xl border border-red-200 bg-red-50 text-red-600 grid place-items-center hover:bg-red-100 transition"
                            title="Hapus foto">
                        <x-icon name="trash" :size="16" />
                    </button>
                </form>
            @endif
        </div>
        </div>

        {{-- Form upload (hidden) --}}
        <form method="POST" action="{{ route('profile.avatar.update') }}"
              enctype="multipart/form-data" class="hidden">
            @csrf
            <input type="file" name="avatar" id="avatarInput" accept="image/*"
                   onchange="this.form.submit()">
        </form>

        @if (session('status') === 'avatar-updated')
            <div class="mt-4 px-3.5 py-2.5 rounded-xl bg-[#DCFCE7] border border-green-200 text-[#166534] text-xs font-semibold flex items-center gap-2">
                <x-icon name="check" :size="14" /> Foto profil berhasil diperbarui.
            </div>
        @endif
        @if (session('status') === 'avatar-removed')
            <div class="mt-4 px-3.5 py-2.5 rounded-xl bg-[#DCFCE7] border border-green-200 text-[#166534] text-xs font-semibold flex items-center gap-2">
                <x-icon name="check" :size="14" /> Foto profil dihapus.
            </div>
        @endif
    </div>
        <div class="min-w-0 flex-1">
            <div class="font-extrabold tracking-tight truncate text-[15px]">{{ $user->name }}</div>
            <div class="text-xs text-[#647164] truncate">{{ $user->email }}</div>
            <div class="flex items-center gap-2 mt-2 flex-wrap">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-[#DCFCE7] text-[#166534]">
                    {{ $user->role }}
                </span>
                @if ($user->city)
                    <span class="text-[11px] text-[#647164] flex items-center gap-1">
                        <x-icon name="map-pin" :size="11" /> {{ $user->city }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="px-4 py-3 rounded-xl bg-[#DCFCE7] border border-green-200 text-[#166534] text-sm font-semibold flex items-center gap-2">
            <x-icon name="check" :size="16" /> Profil berhasil diperbarui.
        </div>
    @endif

    {{-- ============ SECTION: AKUN ============ --}}
    <div>
        <div class="text-[11px] font-extrabold uppercase tracking-widest text-[#647164] px-2 mb-2.5">
            Akun
        </div>

        <div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden">

            {{-- Row: Kelola Profil --}}
            <div class="border-b border-[#E3EAE3]">
                <button type="button" onclick="toggleSection('profile')"
                        class="w-full flex items-center gap-3.5 px-4 py-4 hover:bg-[#F8FAF8] transition text-left">
                    <div class="w-9 h-9 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center shrink-0">
                        <x-icon name="user" :size="16" />
                    </div>
                    <div class="flex-1 font-bold text-sm">Kelola Profil</div>
                    <svg class="chev transition-transform" id="chev-profile" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#647164" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>

                <div id="section-profile" class="hidden px-4 pb-5">
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4 pt-1">
                        @csrf
                        @method('PATCH')

                        <div class="grid sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold mb-1.5">Nama lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                                @error('name')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1.5">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                                @error('email')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold mb-1.5">Kota / Kecamatan</label>
                                <input type="text" name="city" value="{{ old('city', $user->city) }}"
                                       placeholder="Cikarang Selatan"
                                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1.5">Alamat singkat</label>
                                <input type="text" name="address" value="{{ old('address', $user->address) }}"
                                       placeholder="Jl. Industri No. 12"
                                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-3.5">
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

                        <div id="mapPicker" class="h-56 rounded-xl overflow-hidden border border-[#E3EAE3] bg-[#EAF0EA]"></div>

                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <button type="button" id="useGeoBtn"
                                    class="inline-flex items-center gap-1.5 h-10 px-4 rounded-xl bg-[#DCFCE7] text-[#166534] text-xs font-bold hover:bg-[#bbf7d0] transition">
                                <x-icon name="navigation" :size="13" />
                                Deteksi lokasi saya
                            </button>
                            <button type="submit"
                                    class="h-10 px-5 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
                                Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Row: Password & Keamanan --}}
            <div>
                <button type="button" onclick="toggleSection('password')"
                        class="w-full flex items-center gap-3.5 px-4 py-4 hover:bg-[#F8FAF8] transition text-left">
                    <div class="w-9 h-9 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center shrink-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <div class="flex-1 font-bold text-sm">Password &amp; Keamanan</div>
                    <svg class="chev transition-transform" id="chev-password" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#647164" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>

                <div id="section-password" class="hidden px-4 pb-5">
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-4 pt-1 max-w-md">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold mb-1.5">Password saat ini</label>
                            <input type="password" name="current_password" autocomplete="current-password"
                                   class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                            @error('current_password', 'updatePassword')
                                <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold mb-1.5">Password baru</label>
                            <input type="password" name="password" autocomplete="new-password"
                                   class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                            @error('password', 'updatePassword')
                                <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold mb-1.5">Konfirmasi password baru</label>
                            <input type="password" name="password_confirmation" autocomplete="new-password"
                                   class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
                        </div>

                        <div class="flex items-center gap-3 flex-wrap">
                            <button type="submit"
                                    class="h-10 px-5 rounded-xl bg-[#166534] text-white text-sm font-bold hover:bg-[#14532d] transition">
                                Ubah Password
                            </button>
                            @if (session('status') === 'password-updated')
                                <p class="text-xs text-[#166534] font-semibold">Password berhasil diubah.</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ SECTION: PREFERENSI ============ --}}
    <div>
        <div class="text-[11px] font-extrabold uppercase tracking-widest text-[#647164] px-2 mb-2.5">
            Preferensi
        </div>

        <div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden">

            {{-- ============ ROW: TEMA ============ --}}
            <div class="border-b border-[#E3EAE3]">
                <button type="button" onclick="toggleSection('theme')"
                        class="w-full flex items-center gap-3.5 px-4 py-4 hover:bg-[#F8FAF8] transition text-left">
                    <div class="w-9 h-9 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center shrink-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"/>
                            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                        </svg>
                    </div>
                    <div class="flex-1 font-bold text-sm">Tema</div>
                    <span class="text-xs text-[#647164] font-semibold mr-1" data-theme-label>Terang</span>
                    <svg class="chev transition-transform" id="chev-theme" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#647164" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>

                <div id="section-theme" class="hidden px-4 pb-5">
                    <p class="text-xs text-[#647164] mb-3.5">
                        Pilih tema tampilan. Pilihan tersimpan di perangkat ini.
                    </p>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="setTheme('light')"
                                data-theme-option="light"
                                class="flex flex-col items-start gap-2 p-4 rounded-xl border-2 border-[#E3EAE3] hover:border-[#16A34A] transition text-left">
                            <div class="theme-preview-light w-full h-16 rounded-lg bg-[#F8FAF8] border border-[#E3EAE3] flex items-center gap-1.5 px-2">
                                <div class="w-1/4 h-full rounded bg-white border border-[#E3EAE3]"></div>
                                <div class="flex-1 h-full rounded bg-white border border-[#E3EAE3]"></div>
                            </div>
                            <div class="flex items-center justify-between w-full">
                                <span class="text-[13px] font-bold">Terang</span>
                                <span data-theme-check class="w-5 h-5 rounded-full bg-[#16A34A] text-white grid place-items-center">
                                    <x-icon name="check" :size="12" />
                                </span>
                            </div>
                        </button>

                        <button type="button" onclick="setTheme('dark')"
                                data-theme-option="dark"
                                class="flex flex-col items-start gap-2 p-4 rounded-xl border-2 border-[#E3EAE3] hover:border-[#16A34A] transition text-left">
                            <div class="w-full h-16 rounded-lg bg-[#0D130D] border border-[#3A4A3A] flex items-center gap-1.5 px-2">
                                <div class="w-1/4 h-full rounded bg-[#1A2119] border border-[#3A4A3A]"></div>
                                <div class="flex-1 h-full rounded bg-[#1A2119] border border-[#3A4A3A]"></div>
                            </div>
                            <div class="flex items-center justify-between w-full">
                                <span class="text-[13px] font-bold">Gelap</span>
                                <span data-theme-check class="w-5 h-5 rounded-full bg-[#16A34A] text-white grid place-items-center" style="display:none">
                                    <x-icon name="check" :size="12" />
                                </span>
                            </div>
                        </button>
                    </div>

                    <button type="button" onclick="setTheme('light')"
                            class="mt-3 text-[11px] font-semibold text-[#647164] hover:text-[#166534] transition">
                        Kembalikan ke terang
                    </button>
                </div>
            </div>

            {{-- ============ ROW: BAHASA (row terakhir, TANPA border-b) ============ --}}
            <div>
                <button type="button" onclick="openLanguageModal()"
                        class="w-full flex items-center gap-3.5 px-4 py-4 hover:bg-[#F8FAF8] transition text-left">
                    <div class="w-9 h-9 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center shrink-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <div class="flex-1 font-bold text-sm">Bahasa</div>
                    <span class="text-xs text-[#647164] font-semibold mr-1">Indonesia</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#647164" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <div>
        <div class="text-[11px] font-extrabold uppercase tracking-widest text-[#647164] px-2 mb-2.5">
            Dukungan
        </div>

        <div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden">
            <a href="{{ route('tentang') }}"
               class="flex items-center gap-3.5 px-4 py-4 hover:bg-[#F8FAF8] transition border-b border-[#E3EAE3]">
                <div class="w-9 h-9 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center shrink-0">
                    <x-icon name="leaf" :size="16" />
                </div>
                <div class="flex-1 font-bold text-sm">Tentang Kami</div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#647164" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </a>

            <a href="mailto:halo@silabung.id"
               class="flex items-center gap-3.5 px-4 py-4 hover:bg-[#F8FAF8] transition">
                <div class="w-9 h-9 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center shrink-0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <div class="flex-1 font-bold text-sm">Pusat Bantuan</div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#647164" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- ============ ZONA BAHAYA ============ --}}
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
        <h3 class="font-extrabold tracking-tight text-red-800 text-sm mb-1">Hapus Akun</h3>
        <p class="text-xs text-red-700/80 mb-4 max-w-lg leading-relaxed">
            Setelah akunmu dihapus, semua data akan hilang permanen — termasuk barang, permintaan, dan favorit.
        </p>
        <button type="button" onclick="openDeleteModal()"
                class="h-10 px-5 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition">
            Hapus Akun Saya
        </button>
    </div>
</div>

{{-- ============ MODAL: HAPUS AKUN ============ --}}
<div id="deleteAccountModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-5"
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
                <button type="button" onclick="closeDeleteModal()"
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
{{-- ============ MODAL: BAHASA ============ --}}
<div id="languageModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-5"
     style="background: rgba(23,33,23,.5); backdrop-filter: blur(3px);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[400px] p-6">
        <h3 class="font-extrabold tracking-tight text-lg mb-1">Pilih Bahasa</h3>
        <p class="text-sm text-[#647164] mb-5">
            Pilih bahasa yang ingin digunakan di aplikasi ini.
        </p>
        <div class="space-y-2.5">
            <button type="button" onclick="closeLanguageModal()"
                    class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 border-[#16A34A] bg-[#F0FDF4] transition text-left">
                <span class="text-xl">🇮🇩</span>
                <div class="flex-1">
                    <div class="font-bold text-sm">Bahasa Indonesia</div>
                    <div class="text-[11px] text-[#647164]">Aktif</div>
                </div>
                <x-icon name="check" :size="16" />
            </button>
        </div>

        <button type="button" onclick="closeLanguageModal()"
                class="w-full mt-4 h-11 rounded-xl border border-[#E3EAE3] bg-white text-sm font-bold hover:bg-[#F8FAF8] transition">
            Tutup
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ============ Toggle accordion ============ */
    window.toggleSection = function (key) {
        const allKeys = ['profile', 'password', 'theme'];   // ← daftar lengkap
        const el   = document.getElementById('section-' + key);
        const chev = document.getElementById('chev-' + key);
        const isOpen = !el.classList.contains('hidden');

        // Tutup semua
        allKeys.forEach(k => {
            const e = document.getElementById('section-' + k);
            const c = document.getElementById('chev-' + k);
            if (e) e.classList.add('hidden');
            if (c) c.style.transform = 'rotate(0deg)';
        });

        // Kalau sebelumnya tertutup → buka
        if (!isOpen) {
            el.classList.remove('hidden');
            if (chev) chev.style.transform = 'rotate(90deg)';
        }
    };

    /* ============ Map picker ============ */
    const latInput = document.getElementById('latInput');
    const lngInput = document.getElementById('lngInput');
    const geoBtn   = document.getElementById('useGeoBtn');
    let map = null;
    let marker = null;

    const initMap = () => {
        if (map || !document.getElementById('mapPicker')) return;

        const defaultLat = parseFloat(latInput.value) || -6.2;
        const defaultLng = parseFloat(lngInput.value) || 106.8166;

        map = L.map('mapPicker').setView([defaultLat, defaultLng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        const sync = (lat, lng) => {
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
            sync(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', () => {
            const p = marker.getLatLng();
            sync(p.lat, p.lng);
        });

        geoBtn?.addEventListener('click', () => {
            if (!navigator.geolocation) return alert('Browser tidak mendukung geolokasi.');
            geoBtn.disabled = true;
            geoBtn.textContent = 'Mendeteksi...';
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const { latitude, longitude } = pos.coords;
                    marker.setLatLng([latitude, longitude]);
                    map.setView([latitude, longitude], 15);
                    sync(latitude, longitude);
                    geoBtn.disabled = false;
                    geoBtn.textContent = '✓ Terdeteksi';
                },
                () => {
                    geoBtn.disabled = false;
                    geoBtn.textContent = 'Gagal — geser pin manual';
                    setTimeout(() => geoBtn.textContent = 'Deteksi lokasi saya', 2500);
                },
                { timeout: 8000 }
            );
        });

        setTimeout(() => map.invalidateSize(), 100);
    };

    // Buka section profile otomatis kalau ada error
    @if ($errors->any() && ! $errors->updatePassword->any())
        toggleSection('profile');
        setTimeout(initMap, 100);
    @endif

    @if ($errors->updatePassword->any())
        toggleSection('password');
    @endif

    // Init map saat section profile dibuka
    document.querySelectorAll('[onclick*="toggleSection(\'profile\')"]').forEach(btn => {
        btn.addEventListener('click', () => setTimeout(initMap, 50));
    });

    /* ============ Modal helpers ============ */
    window.openDeleteModal = () => {
        const m = document.getElementById('deleteAccountModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    };
    window.closeDeleteModal = () => {
        const m = document.getElementById('deleteAccountModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    };

    window.openThemeModal = () => {
        const m = document.getElementById('themeModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    };
    window.closeThemeModal = () => {
        const m = document.getElementById('themeModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    };

    window.openLanguageModal = () => {
        const m = document.getElementById('languageModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    };
    window.closeLanguageModal = () => {
        const m = document.getElementById('languageModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    };

    // Tutup modal saat klik backdrop
    ['deleteAccountModal', 'themeModal', 'languageModal'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', (e) => {
            if (e.target.id === id) {
                e.target.classList.add('hidden');
                e.target.classList.remove('flex');
            }
        });
    });

    // Set state awal tema
    setTimeout(() => window.setTheme(window.currentTheme()), 50);
});
</script>
@endpush