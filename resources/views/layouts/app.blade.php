<!DOCTYPE html>
<html lang="id">

<style id="silabung-dark-css">

html.dark .theme-preview-light {
    background-color: #F8FAF8 !important;
}
html.dark .theme-preview-light > div {
    background-color: #FFFFFF !important;
    border-color: #E3EAE3 !important;
}
    html.dark {
        color-scheme: dark;
    }

    /* ---------- Background & Surface ---------- */
    html.dark body,
    html.dark .bg-\[\#F8FAF8\] {
        background-color: #0D130D !important;
    }
    html.dark .bg-white {
        background-color: #1A2119 !important;
    }
    html.dark .bg-\[\#FBFDFB\] {
        background-color: #16201A !important;
    }
    html.dark .bg-\[\#F0FDF4\] {
        background-color: #132018 !important;
    }
    html.dark .bg-\[\#F1F5F1\] {
        background-color: #1B241B !important;
    }
    html.dark .bg-\[\#DCFCE7\] {
        background-color: #1A3520 !important;
    }
    html.dark .bg-\[\#166534\] {
        background-color: #14532D !important;
    }
    html.dark .bg-\[\#16A34A\] {
        background-color: #16A34A !important; /* biarkan brand tetap */
    }
    html.dark .bg-\[\#FEF3C7\] { background-color: #2A2410 !important; } /* kardus */
    html.dark .bg-\[\#CFFAFE\] { background-color: #102A2E !important; } /* plastik */
    html.dark .bg-\[\#DBEAFE\] { background-color: #0F1F2E !important; } /* botol */
    html.dark .bg-\[\#F1F5F9\] { background-color: #1B232B !important; } /* kertas */
    html.dark .bg-\[\#CCFBF1\] { background-color: #0F2C28 !important; } /* kaca */
    html.dark .bg-\[\#FDE68A\] { background-color: #2E2612 !important; } /* kayu */
    html.dark .bg-\[\#E2E8F0\] { background-color: #232A33 !important; } /* logam */
    html.dark .bg-\[\#EDE9FE\] { background-color: #1F1B33 !important; } /* elektronik */
    html.dark .bg-\[\#FCE7F3\] { background-color: #2B1A24 !important; } /* pakaian */

    /* Amber/sky/red/purple soft */
    html.dark .bg-amber-50  { background-color: #2A2410 !important; }
    html.dark .bg-amber-100 { background-color: #3A2F14 !important; }
    html.dark .bg-sky-100   { background-color: #102A38 !important; }
    html.dark .bg-red-50    { background-color: #2A1414 !important; }
    html.dark .bg-red-100   { background-color: #3A1A1A !important; }
    html.dark .bg-violet-100{ background-color: #221A3A !important; }
    html.dark .bg-green-200 { background-color: #14532D !important; }

    /* Overlay dark untuk backdrop modal */
    html.dark .bg-black\/40,
    html.dark .bg-black\/30 { background-color: rgba(0,0,0,.7) !important; }

    /* ---------- Text ---------- */
    html.dark .text-\[\#172117\] {
        color: #E7EFE7 !important;
    }
    html.dark .text-\[\#647164\] {
        color: #96A096 !important;
    }
    html.dark .text-\[\#166534\] {
        color: #7CD98F !important;
    }
    html.dark .text-\[\#16A34A\] {
        color: #4ADE80 !important;
    }
    html.dark .text-white {
        color: #FFFFFF !important;
    }
    html.dark .text-amber-600,
    html.dark .text-amber-700 { color: #FBBF24 !important; }
    html.dark .text-red-600,
    html.dark .text-red-700   { color: #F87171 !important; }
    html.dark .text-sky-600,
    html.dark .text-sky-700   { color: #38BDF8 !important; }

    /* ---------- Border ---------- */
    html.dark .border-\[\#E3EAE3\] {
        border-color: #2A3A2A !important;
    }
    html.dark .border-\[\#F1F5F1\] {
        border-color: #1F2A20 !important;
    }
    html.dark .border-\[\#cfe6d5\] {
        border-color: #1F3A26 !important;
    }
    html.dark .border-green-200,
    html.dark .border-green-300 {
        border-color: #2A5A34 !important;
    }
    html.dark .border-red-200   { border-color: #4A2424 !important; }
    html.dark .border-amber-200 { border-color: #4A3A1A !important; }
    html.dark .border-white {
        border-color: #1A2119 !important;
    }

    /* ---------- Divide ---------- */
    html.dark .divide-\[\#E3EAE3\] > :not([hidden]) ~ :not([hidden]) {
        border-color: #2A3A2A !important;
    }
    html.dark .divide-\[\#F1F5F1\] > :not([hidden]) ~ :not([hidden]) {
        border-color: #1F2A20 !important;
    }

    /* ---------- Hover ---------- */
    html.dark .hover\:bg-\[\#F0FDF4\]:hover {
        background-color: #1A2A1E !important;
    }
    html.dark .hover\:bg-\[\#F8FAF8\]:hover {
        background-color: #16201A !important;
    }
    html.dark .hover\:bg-\[\#F1F5F1\]:hover {
        background-color: #232C22 !important;
    }
    html.dark .hover\:bg-\[\#DCFCE7\]:hover {
        background-color: #1A3520 !important;
    }
    html.dark .hover\:bg-red-50:hover {
        background-color: #2A1414 !important;
    }
    html.dark .hover\:bg-red-100:hover {
        background-color: #3A1A1A !important;
    }

    /* ---------- Gradient (welcome banner) ---------- */
    html.dark .from-\[\#F0FDF4\] {
        --tw-gradient-from: #132018 !important;
        --tw-gradient-to: rgba(19, 32, 24, 0) !important;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important;
    }
    html.dark .via-white {
        --tw-gradient-to: rgba(26, 33, 25, 0) !important;
        --tw-gradient-stops: var(--tw-gradient-from), #1A2119, var(--tw-gradient-to) !important;
    }
    html.dark .to-\[\#F0FDF4\] {
        --tw-gradient-to: #132018 !important;
    }

    html.dark .from-\[\#DCFCE7\] {
        --tw-gradient-from: #1A3520 !important;
        --tw-gradient-to: rgba(26, 53, 32, 0) !important;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important;
    }
    html.dark .via-\[\#F0FDF4\] {
        --tw-gradient-stops: var(--tw-gradient-from), #132018, var(--tw-gradient-to) !important;
    }

    /* ---------- Input / Textarea / Select ---------- */
    html.dark input,
    html.dark textarea,
    html.dark select {
        color: #E7EFE7;
        background-color: #16201A;
    }
    html.dark input::placeholder,
    html.dark textarea::placeholder {
        color: #6A766A;
    }
    html.dark input:focus,
    html.dark textarea:focus,
    html.dark select:focus {
        background-color: #1A2620;
    }

    /* ---------- Shadow lebih halus ---------- */
    html.dark .shadow-md,
    html.dark .shadow-lg,
    html.dark .shadow-xl,
    html.dark .shadow-2xl {
        box-shadow: 0 10px 30px rgba(0, 0, 0, .5) !important;
    }

    /* ---------- Bubbles chat ---------- */
    html.dark .chat-bubble-theirs {
        background: #1A2119;
        border-color: #2A3A2A;
        color: #E7EFE7;
    }
    html.dark .chat-bubble-mine {
        background: #16A34A;
        color: #fff;
    }

    /* ---------- Leaflet dark-ish map ---------- */
    html.dark .leaflet-container {
        background: #0F160F !important;
    }
    html.dark .leaflet-tile {
        filter: brightness(.75) invert(1) hue-rotate(180deg) saturate(.7) !important;
    }
    html.dark .leaflet-popup-content-wrapper,
    html.dark .leaflet-popup-tip {
        background: #1A2119;
        color: #E7EFE7;
    }
    html.dark .bg-white\/90,
    html.dark .bg-white\/85,
    html.dark .bg-white\/80,
    html.dark .bg-white\/95,
    html.dark .bg-white\/70 {
        background-color: rgba(26, 33, 25, .92) !important;
    }
    html.dark .bg-white\/95 {
        background-color: rgba(26, 33, 25, .95) !important;
    }
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SILABUNG</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>

<script>
    // Cegah flash sebelum CSS dimuat
    (function () {
        try {
            if (localStorage.getItem('silabung-theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        } catch (e) {}
    })();
</script>

<body class="min-h-screen bg-[#F8FAF8] text-[#172117] antialiased">

@php
    $user = auth()->user();
    $role = $user->role;

    $nav = $role === 'supplier'
        ? [
            ['route' => 'supplier.dashboard',      'icon' => 'home',    'label' => 'Dashboard'],
            ['route' => 'supplier.items.index',    'icon' => 'package', 'label' => 'Barang Saya'],
            ['route' => 'supplier.items.create',   'icon' => 'plus',    'label' => 'Tambah Barang'],
            ['route' => 'supplier.requests.index', 'icon' => 'inbox',   'label' => 'Permintaan Masuk'],
            ['route' => 'profile.edit',            'icon' => 'settings','label' => 'Pengaturan'],
        ]
        : [
            ['route' => 'customer.dashboard',      'icon' => 'home',    'label' => 'Dashboard'],
            ['route' => 'customer.search',         'icon' => 'search',  'label' => 'Jelajahi Barang'],
            ['route' => 'customer.requests.index', 'icon' => 'inbox',   'label' => 'Permintaan Saya'],
            ['route' => 'customer.favorites',      'icon' => 'heart',   'label' => 'Favorit'],
            ['route' => 'profile.edit',            'icon' => 'settings','label' => 'Pengaturan'],
        ];

    $mobileNav = array_slice($nav, 0, 4);

    $unreadChat = \App\Models\Message::whereHas('conversation', function ($q) use ($user) {
        $q->where($user->isSupplier() ? 'supplier_id' : 'customer_id', $user->id);
    })->where('sender_id', '!=', $user->id)->whereNull('read_at')->count();
@endphp

<div class="flex min-h-screen">

    {{-- ===================== SIDEBAR KIRI ===================== --}}
    <aside class="hidden lg:flex w-[240px] shrink-0 flex-col bg-white border-r border-[#E3EAE3] sticky top-0 h-screen p-3.5">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-2.5 pt-2 pb-6">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#16A34A] to-[#166534] grid place-items-center text-white shadow-md shadow-green-500/25">
                <x-icon name="leaf" :size="17" />
            </div>
            <div class="leading-tight">
                <div class="font-extrabold tracking-tight text-[15px]">SILABUNG</div>
                <div class="text-[10px] text-[#647164] font-semibold tracking-wide">Sirkula Sambung</div>
            </div>
        </a>

        <nav class="flex flex-col gap-1 flex-1 overflow-y-auto">
            @foreach ($nav as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition
                          {{ $active
                              ? 'bg-[#DCFCE7] text-[#166534] font-bold'
                              : 'text-[#647164] hover:bg-[#F0FDF4] hover:text-[#166534]' }}">
                    <x-icon :name="$item['icon']" :size="17" />
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Bawah: ilustrasi + tagline --}}
        <div class="border-t border-[#E3EAE3] pt-4 mt-3 px-2 relative overflow-hidden">
            <div class="absolute -bottom-8 -right-6 w-24 h-24 rounded-full bg-[#DCFCE7] opacity-40"></div>
            <svg viewBox="0 0 40 40" class="w-9 h-9 text-[#16A34A] mb-3 relative">
                <path d="M20 34C12 30 8 22 10 12c8-2 18 2 22 10-2 8-6 12-12 12z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                <path d="M20 34c0-10 4-18 12-22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <p class="text-[11px] text-[#647164] leading-relaxed relative">
                Barang yang kamu gunakan hari ini, bisa jadi manfaat untuk esok.
            </p>
        </div>
    </aside>

    {{-- ===================== AREA UTAMA ===================== --}}
    <div class="flex-1 min-w-0 flex flex-col">

        {{-- TOPBAR --}}
        <header class="h-[68px] sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-[#E3EAE3] px-4 sm:px-6 flex items-center gap-3">
            <div class="min-w-0 flex-1">
                <div class="text-[15px] font-extrabold tracking-tight truncate">@yield('page-title', 'Dashboard')</div>
                @hasSection('page-subtitle')
                    <div class="text-[11px] text-[#647164] truncate">@yield('page-subtitle')</div>
                @endif
            </div>

            <div class="flex items-center gap-1.5">
                @yield('page-actions')

                {{-- Bell = link ke halaman chat --}}
                <a href="{{ route('chat.index') }}"
                   class="relative w-10 h-10 rounded-xl grid place-items-center transition
                          {{ request()->routeIs('chat.*') ? 'bg-[#DCFCE7] text-[#166534]' : 'text-[#647164] hover:bg-[#F0FDF4] hover:text-[#166534]' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    @if ($unreadChat > 0)
                        <span class="absolute top-1.5 right-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-extrabold grid place-items-center border-2 border-white">
                            {{ $unreadChat > 9 ? '9+' : $unreadChat }}
                        </span>
                    @endif
                </a>

                {{-- Profile dropdown --}}
                <div class="relative" id="profileDropdownWrap">
                    <button type="button" onclick="toggleProfileMenu(event)"
                            class="flex items-center gap-2 h-10 pl-1 pr-2.5 rounded-xl hover:bg-[#F0FDF4] transition group">
                        <div class="w-8 h-8 rounded-full bg-[#166534] text-white grid place-items-center text-[11px] font-extrabold">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:block text-left leading-tight">
                            <div class="text-[12px] font-bold truncate max-w-[110px]">{{ $user->name }}</div>
                        </div>
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#647164" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-[.open]:rotate-180">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>

                    <div id="profileMenu"
                         class="hidden absolute right-0 top-[calc(100%+8px)] w-[240px] bg-white border border-[#E3EAE3] rounded-xl shadow-xl overflow-hidden z-50">
                        <div class="px-4 py-3.5 border-b border-[#E3EAE3] bg-[#FBFDFB]">
                            <div class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-full bg-[#166534] text-white grid place-items-center text-sm font-extrabold shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[13px] font-bold truncate">{{ $user->name }}</div>
                                    <div class="text-[11px] text-[#647164] truncate">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="py-1.5">
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-[#172117] hover:bg-[#F0FDF4] hover:text-[#166534] transition">
                                <x-icon name="user" :size="15" /> Profil Saya
                            </a>
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-[#172117] hover:bg-[#F0FDF4] hover:text-[#166534] transition">
                                <x-icon name="home" :size="15" /> Dashboard
                            </a>
                            <a href="{{ route('home') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-[#172117] hover:bg-[#F0FDF4] hover:text-[#166534] transition">
                                <x-icon name="leaf" :size="15" /> Beranda
                            </a>
                        </div>

                        <div class="px-4 py-3 border-t border-[#E3EAE3]">
                            <div class="text-[10px] font-extrabold uppercase tracking-widest text-[#647164] mb-2">
                                Tema
                            </div>
                            <div class="flex gap-1.5">
                                <button type="button" onclick="setTheme('light')"
                                        data-theme-option="light"
                                        class="flex-1 flex items-center justify-center gap-1.5 h-9 rounded-lg border border-[#E3EAE3] text-[11px] font-bold text-[#172117] hover:bg-[#F0FDF4] transition">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="4"/>
                                        <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                                    </svg>
                                    Terang
                                </button>
                                <button type="button" onclick="setTheme('dark')"
                                        data-theme-option="dark"
                                        class="flex-1 flex items-center justify-center gap-1.5 h-9 rounded-lg border border-[#E3EAE3] text-[11px] font-bold text-[#172117] hover:bg-[#F0FDF4] transition">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                                    </svg>
                                    Gelap
                                </button>
                            </div>
                        </div>

                        <div class="py-1.5 border-t border-[#E3EAE3]">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 transition text-left">
                                    <x-icon name="logout" :size="15" /> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 px-4 sm:px-6 py-6 w-full pb-24 lg:pb-8">

            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-[#DCFCE7] border border-green-200 text-[#166534] text-sm font-semibold flex items-center gap-2">
                    <x-icon name="check" :size="16" /> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-semibold flex items-center gap-2">
                    <x-icon name="alert" :size="16" /> {{ session('error') }}
                </div>
            @endif

            @hasSection('right-sidebar')
                <div class="grid lg:grid-cols-[1fr_340px] gap-5 max-w-[1400px]">
                    <div class="min-w-0">
                        @yield('content')
                    </div>
                    <aside class="space-y-4">
                        @yield('right-sidebar')
                    </aside>
                </div>
            @else
                <div class="max-w-[1400px]">
                    @yield('content')
                </div>
            @endif
        </main>
    </div>
</div>

{{-- ===================== MOBILE BOTTOM NAV ===================== --}}
<nav class="lg:hidden fixed bottom-0 left-0 right-0 h-16 bg-white/95 backdrop-blur-md border-t border-[#E3EAE3] z-50 flex items-center justify-around px-1"
     style="padding-bottom: env(safe-area-inset-bottom);">
    @foreach ($mobileNav as $item)
        @php $active = request()->routeIs($item['route']); @endphp
        <a href="{{ route($item['route']) }}"
           class="flex flex-col items-center justify-center gap-0.5 flex-1 py-2 text-[10px] font-bold transition
                  {{ $active ? 'text-[#166534]' : 'text-[#647164]' }}">
            <span class="w-10 h-6 rounded-lg grid place-items-center {{ $active ? 'bg-[#DCFCE7]' : '' }}">
                <x-icon :name="$item['icon']" :size="16" />
            </span>
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>

<script>
    /* ============================================================
       Theme toggle
       ============================================================ */
    window.setTheme = function (mode) {
        const root = document.documentElement;
        if (mode === 'dark') {
            root.classList.add('dark');
            try { localStorage.setItem('silabung-theme', 'dark'); } catch (e) {}
        } else {
            root.classList.remove('dark');
            try { localStorage.setItem('silabung-theme', 'light'); } catch (e) {}
        }

        // Update semua tombol yang menandai tema aktif
        document.querySelectorAll('[data-theme-option]').forEach(el => {
            const active = el.dataset.themeOption === mode;
            el.classList.toggle('border-\\[\\#16A34A\\]', active);
            el.classList.toggle('bg-\\[\\#F0FDF4\\]', active);
            el.classList.toggle('border-\\[\\#E3EAE3\\]', !active);
            el.classList.toggle('opacity-60', !active);
            const check = el.querySelector('[data-theme-check]');
            if (check) check.style.display = active ? '' : 'none';
        });

        // Update label di profile dropdown (kalau ada)
        document.querySelectorAll('[data-theme-label]').forEach(el => {
            el.textContent = mode === 'dark' ? 'Gelap' : 'Terang';
        });
    };

    window.currentTheme = function () {
        return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    };

    // Init label saat load
    document.addEventListener('DOMContentLoaded', () => {
        const cur = window.currentTheme();
        document.querySelectorAll('[data-theme-label]').forEach(el => {
            el.textContent = cur === 'dark' ? 'Gelap' : 'Terang';
        });
    });
</script>

@stack('scripts')

<script>
    function toggleProfileMenu(e) {
        e.stopPropagation();
        const menu = document.getElementById('profileMenu');
        const wrap = document.getElementById('profileDropdownWrap');
        const isOpen = !menu.classList.contains('hidden');
        menu.classList.toggle('hidden', isOpen);
        wrap.classList.toggle('open', !isOpen);
    }
    document.addEventListener('click', (e) => {
        const wrap = document.getElementById('profileDropdownWrap');
        const menu = document.getElementById('profileMenu');
        if (wrap && menu && !wrap.contains(e.target)) {
            menu.classList.add('hidden');
            wrap.classList.remove('open');
        }
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const menu = document.getElementById('profileMenu');
            const wrap = document.getElementById('profileDropdownWrap');
            if (menu) menu.classList.add('hidden');
            if (wrap) wrap.classList.remove('open');
        }
    });
</script>
</body>
</html>