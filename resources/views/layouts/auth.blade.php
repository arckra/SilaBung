<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk') — SILABUNG</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .fade-in { animation: fadeIn .5s ease both; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
    </style>
</head>
<body class="bg-[#F8FAF8] text-[#172117] antialiased">

<div class="min-h-screen grid lg:grid-cols-2">

    {{-- ==================== LEFT PANEL (brand) ==================== --}}
    <div class="hidden lg:flex relative flex-col justify-between overflow-hidden
                bg-gradient-to-br from-[#166534] via-[#15803D] to-[#16A34A] p-12 text-white">

        {{-- Decorative blobs --}}
        <div class="absolute -right-40 -top-40 w-[520px] h-[520px] rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute -left-32 -bottom-40 w-[460px] h-[460px] rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute right-12 bottom-32 w-40 h-40 rounded-full bg-white/[0.04] pointer-events-none"></div>

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="relative flex items-center gap-3 w-fit">
            <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur grid place-items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 2l4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/>
                    <path d="M7 22l-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/>
                </svg>
            </div>
            <div>
                <div class="font-extrabold tracking-tight text-[17px] leading-tight">SILABUNG</div>
                <div class="text-[11px] text-white/70 font-semibold tracking-wide">Sirkula Sambung</div>
            </div>
        </a>

        {{-- Middle content --}}
        <div class="relative max-w-md">
            <h2 class="text-[32px] font-extrabold tracking-tight leading-[1.15] mb-5">
                Barang tak terpakai,<br>tetap tersambung manfaatnya.
            </h2>
            <p class="text-white/75 leading-relaxed text-[15px]">
                Platform yang menghubungkan barang yang sudah tidak digunakan dengan orang
                yang masih membutuhkannya — untuk dipakai kembali atau didaur ulang.
            </p>

            <div class="mt-9 space-y-4">
                @php
                    $points = [
                        ['t' => 'Temukan barang di sekitarmu', 'd' => 'Cari berdasarkan kategori & jarak terdekat.'],
                        ['t' => 'Bagikan yang sudah tak dipakai', 'd' => 'Daftarkan dalam beberapa detik.'],
                        ['t' => 'Gratis, tanpa perantara', 'd' => 'Langsung antara pemilik & pencari barang.'],
                    ];
                @endphp
                @foreach ($points as $p)
                    <div class="flex gap-3 items-start">
                        <div class="w-6 h-6 rounded-full bg-white/15 grid place-items-center shrink-0 mt-0.5">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-sm">{{ $p['t'] }}</div>
                            <div class="text-white/65 text-[13px] mt-0.5">{{ $p['d'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Footer quote --}}
        <div class="relative text-white/60 text-[12px]">
            © {{ date('Y') }} SILABUNG — Mendukung circular economy Indonesia.
        </div>
    </div>

    {{-- ==================== RIGHT PANEL (form) ==================== --}}
    <div class="flex flex-col justify-center px-5 sm:px-8 py-10 lg:py-12 bg-[#F8FAF8]">

        {{-- Mobile-only brand --}}
        <a href="{{ route('home') }}" class="lg:hidden flex items-center gap-2.5 mb-8 w-fit mx-auto">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#16A34A] to-[#166534] grid place-items-center text-white shadow-md shadow-green-500/25">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 2l4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/>
                    <path d="M7 22l-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/>
                </svg>
            </div>
            <div>
                <div class="font-extrabold tracking-tight text-[15px] leading-tight">SILABUNG</div>
                <div class="text-[10px] text-[#647164] font-semibold tracking-wide">Sirkula Sambung</div>
            </div>
        </a>

        <div class="w-full max-w-[420px] mx-auto fade-in">
            @yield('content')
        </div>
    </div>
</div>

</body>
</html>