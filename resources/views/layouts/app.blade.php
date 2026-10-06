<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SILABUNG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-[#F8FAF8] text-[#172117] antialiased">

@php
    $user = auth()->user();
    $role = $user->role;

    $nav = $role === 'supplier'
        ? [
            ['route' => 'supplier.dashboard',     'icon' => 'home',    'label' => 'Dashboard'],
            ['route' => 'supplier.items.index',   'icon' => 'package', 'label' => 'Barang Saya'],
            ['route' => 'supplier.items.create',  'icon' => 'plus',    'label' => 'Tambah Barang'],
            ['route' => 'supplier.requests.index','icon' => 'inbox',   'label' => 'Permintaan Masuk'],
        ]
        : [
            ['route' => 'customer.dashboard',      'icon' => 'home',    'label' => 'Dashboard'],
            ['route' => 'customer.search',         'icon' => 'search',  'label' => 'Cari Barang'],
            ['route' => 'customer.requests.index', 'icon' => 'inbox',   'label' => 'Permintaan Saya'],
            ['route' => 'customer.favorites',      'icon' => 'heart',   'label' => 'Favorit'],
        ];

    $mobileNav = array_slice($nav, 0, 4);
@endphp

<div class="flex min-h-screen">

    {{-- ============ SIDEBAR (desktop) ============ --}}
    <aside class="hidden lg:flex w-[250px] shrink-0 flex-col bg-white border-r border-[#E3EAE3] sticky top-0 h-screen p-3.5">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-2.5 pt-1.5 pb-5">
            <div class="w-8 h-8 rounded-[10px] bg-gradient-to-br from-[#16A34A] to-[#166534] grid place-items-center text-white shadow-md shadow-green-500/30">
                <x-icon name="leaf" :size="16" />
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
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition
                          {{ $active
                              ? 'bg-[#DCFCE7] text-[#166534] font-bold'
                              : 'text-[#647164] hover:bg-[#F0FDF4] hover:text-[#166534]' }}">
                    <x-icon :name="$item['icon']" :size="17" />
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </aside>

    {{-- ============ MAIN AREA ============ --}}
    <div class="flex-1 min-w-0 flex flex-col">

        {{-- Topbar --}}
        <header class="h-[68px] sticky top-0 z-30 bg-white/85 backdrop-blur-md border-b border-[#E3EAE3] px-6 flex items-center gap-3">
            <div class="min-w-0">
                <div class="text-[15px] font-extrabold tracking-tight truncate">@yield('page-title', 'Dashboard')</div>
                @hasSection('page-subtitle')
                    <div class="text-[11px] text-[#647164] truncate">@yield('page-subtitle')</div>
                @endif
            </div>

            <div class="ml-auto flex items-center gap-2">
                @yield('page-actions')

                {{-- ============ PROFILE DROPDOWN ============ --}}
                <div class="relative" id="profileDropdownWrap">
                    <button type="button"
                            onclick="toggleProfileMenu(event)"
                            class="flex items-center gap-2 h-10 pl-1 pr-3 rounded-xl hover:bg-[#F0FDF4] transition group">
                        <div class="w-8 h-8 rounded-full bg-[#166534] text-white grid place-items-center text-[11px] font-extrabold">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:block text-left leading-tight">
                            <div class="text-[12px] font-bold truncate max-w-[120px]">{{ $user->name }}</div>
                            <div class="text-[9px] font-extrabold uppercase tracking-wider text-[#166534]">{{ $user->role }}</div>
                        </div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#647164" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-[.open]:rotate-180">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>

                    {{-- Dropdown --}}
                    <div id="profileMenu"
                         class="hidden absolute right-0 top-[calc(100%+8px)] w-[240px] bg-white border border-[#E3EAE3] rounded-xl shadow-xl overflow-hidden z-50">

                        {{-- Header --}}
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

                        {{-- Menu --}}
                        <div class="py-1.5">
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-[#172117] hover:bg-[#F0FDF4] hover:text-[#166534] transition">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                Profil Saya
                            </a>

                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-[#172117] hover:bg-[#F0FDF4] hover:text-[#166534] transition">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12l9-9 9 9"/>
                                    <path d="M5 10v10h14V10"/>
                                </svg>
                                Dashboard
                            </a>

                            <a href="{{ route('home') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-[#172117] hover:bg-[#F0FDF4] hover:text-[#166534] transition">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12l9-9 9 9M5 10v10h14V10"/>
                                </svg>
                                Beranda
                            </a>
                        </div>

                        {{-- Logout --}}
                        <div class="py-1.5 border-t border-[#E3EAE3]">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 transition text-left">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                        <path d="M16 17l5-5-5-5M21 12H9"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 px-6 py-6 w-full max-w-[1280px] pb-24 lg:pb-8">
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

            @yield('content')
        </main>
    </div>
</div>

{{-- ============ MOBILE BOTTOM NAV ============ --}}
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

{{-- ============ GLOBAL SCRIPT ============ --}}
<script>
    /**
     * Toggle favorit via fetch() tanpa reload halaman.
     * Dipakai oleh tombol hati di item-card dan halaman detail.
     */
    async function toggleFavorite(event, itemId, btn) {
        event.preventDefault();
        event.stopPropagation();

        // Disable sementara biar tidak dobel klik
        btn.disabled = true;

        try {
            const res = await fetch(`/customer/items/${itemId}/favorite`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept':       'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!res.ok) throw new Error('Request gagal');

            const data = await res.json();

            // Update visual
            const svg = btn.querySelector('svg');
            if (data.favorited) {
                btn.dataset.favorited = '1';
                btn.classList.remove('text-[#647164]');
                btn.classList.add('text-red-500', 'border-red-200');
                btn.title = 'Hapus dari favorit';
                if (svg) svg.setAttribute('fill', 'currentColor');
                showToast(data.message, 'success');
            } else {
                btn.dataset.favorited = '0';
                btn.classList.remove('text-red-500', 'border-red-200');
                btn.classList.add('text-[#647164]');
                btn.title = 'Simpan ke favorit';
                if (svg) svg.setAttribute('fill', 'none');
                showToast(data.message, 'success');
            }
        } catch (err) {
            showToast('Gagal mengubah favorit. Coba lagi.', 'error');
        } finally {
            btn.disabled = false;
        }
    }

    /**
     * Toast kecil di pojok kanan atas.
     */
    function showToast(message, type = 'success') {
        let root = document.getElementById('toast-root');
        if (!root) {
            root = document.createElement('div');
            root.id = 'toast-root';
            root.style.cssText = 'position:fixed;top:20px;right:20px;z-index:300;display:flex;flex-direction:column;gap:9px;';
            document.body.appendChild(root);
        }

        const colors = {
            success: 'background:#166534',
            error:   'background:#DC2626',
            warn:    'background:#B45309',
        };

        const toast = document.createElement('div');
        toast.style.cssText = `
            ${colors[type] || colors.success};
            color:#fff;
            padding:12px 16px;
            border-radius:12px;
            font-size:13px;
            font-weight:600;
            box-shadow:0 10px 30px rgba(23,33,23,.2);
            animation:slideIn .22s ease;
            max-width:340px;
        `;
        toast.textContent = message;
        root.appendChild(toast);

        setTimeout(() => {
            toast.style.transition = 'opacity .3s, transform .3s';
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(20px)';
            setTimeout(() => toast.remove(), 300);
        }, 2400);
    }
</script>

<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(20px); }
        to   { opacity: 1; transform: none; }
    }
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    function toggleProfileMenu(e) {
        e.stopPropagation();
        const menu = document.getElementById('profileMenu');
        const wrap = document.getElementById('profileDropdownWrap');
        const isOpen = !menu.classList.contains('hidden');

        if (isOpen) {
            menu.classList.add('hidden');
            wrap.classList.remove('open');
        } else {
            menu.classList.remove('hidden');
            wrap.classList.add('open');
        }
    }

    // Tutup kalau klik di luar
    document.addEventListener('click', (e) => {
        const wrap = document.getElementById('profileDropdownWrap');
        const menu = document.getElementById('profileMenu');
        if (wrap && menu && !wrap.contains(e.target)) {
            menu.classList.add('hidden');
            wrap.classList.remove('open');
        }
    });

    // Tutup kalau tekan ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const menu = document.getElementById('profileMenu');
            const wrap = document.getElementById('profileDropdownWrap');
            if (menu) menu.classList.add('hidden');
            if (wrap) wrap.classList.remove('open');
        }
    });
</script>

@stack('scripts')
</body>
</html>