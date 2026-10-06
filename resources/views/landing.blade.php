<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILABUNG — Sirkula Sambung | Barang tak terpakai, tetap tersambung manfaatnya.</title>
    <meta name="description" content="SILABUNG menghubungkan barang yang tidak lagi digunakan dengan orang yang membutuhkannya, agar dapat digunakan kembali atau didaur ulang.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ============ SMOOTH SCROLL GLOBAL ============ */
    html {
        scroll-behavior: smooth;
        scroll-padding-top: 80px;   /* biar section tidak ketutup navbar */
    }

    /* ============ ENTRANCE ANIMATIONS ============ */
    .fade-up   { animation: fadeUp .8s cubic-bezier(.2,.9,.3,1) both; }
    .fade-up-2 { animation: fadeUp .8s .15s cubic-bezier(.2,.9,.3,1) both; }
    .fade-up-3 { animation: fadeUp .8s .3s cubic-bezier(.2,.9,.3,1) both; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: none; }
    }

    /* ============ CIRCULAR ICON ============ */
    .spin-slow { animation: spin 22s linear infinite; transform-origin: center; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ============ REVEAL ON SCROLL ============ */
    [data-reveal] {
        opacity: 0;
        transform: translateY(28px);
        transition: opacity .8s cubic-bezier(.2,.9,.3,1),
                    transform .8s cubic-bezier(.2,.9,.3,1);
        will-change: opacity, transform;
    }
    [data-reveal].revealed {
        opacity: 1;
        transform: none;
    }
    [data-reveal][data-delay="1"] { transition-delay: .08s; }
    [data-reveal][data-delay="2"] { transition-delay: .16s; }
    [data-reveal][data-delay="3"] { transition-delay: .24s; }
    [data-reveal][data-delay="4"] { transition-delay: .32s; }

    /* ============ NAV LINK ============ */
    .land-nav-link {
        position: relative;
        transition: color .2s ease, background .2s ease;
    }
    .land-nav-link::after {
        content: '';
        position: absolute;
        left: 14px;
        right: 14px;
        bottom: 4px;
        height: 2px;
        background: #16A34A;
        border-radius: 2px;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .28s ease;
    }
    .land-nav-link:hover::after,
    .land-nav-link.active::after {
        transform: scaleX(1);
    }
    .land-nav-link.active {
        color: #166534;
        background: #F0FDF4;
    }

    /* ============ HIGHLIGHT SECTION TARGET ============ */
    /* Saat section jadi tujuan scroll, kasih efek ring hijau sekilas */
    .section-highlight {
        animation: sectionPulse 1.6s cubic-bezier(.2,.9,.3,1) 0.3s;
    }
    @keyframes sectionPulse {
        0%   { box-shadow: 0 0 0 0 rgba(22,163,74,0); }
        30%  { box-shadow: 0 0 0 8px rgba(22,163,74,.14); }
        100% { box-shadow: 0 0 0 0 rgba(22,163,74,0); }
    }

    /* ============ PULSE RING untuk badge hero ============ */
    @keyframes pulseRing {
        0%   { box-shadow: 0 0 0 0 rgba(22,163,74,.45); }
        70%  { box-shadow: 0 0 0 12px rgba(22,163,74,0); }
        100% { box-shadow: 0 0 0 0 rgba(22,163,74,0); }
    }
    .pulse-ring { animation: pulseRing 2.4s infinite; }
    </style>
</head>
<body class="bg-[#F8FAF8] text-[#172117] antialiased">

{{-- ============ NAVBAR ============ --}}
<nav class="sticky top-0 z-50 bg-[#F8FAF8]/85 backdrop-blur-md border-b border-[#E3EAE3]">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 h-[68px] flex items-center justify-between gap-4">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#16A34A] to-[#166534] grid place-items-center text-white shadow-md shadow-green-500/25">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 2l4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/>
                    <path d="M7 22l-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/>
                </svg>
            </div>
            <div class="leading-tight">
                <div class="font-extrabold tracking-tight text-[15px]">SILABUNG</div>
                <div class="text-[10px] text-[#647164] font-semibold tracking-wide -mt-0.5">Sirkula Sambung</div>
            </div>
        </a>

        {{-- Links (desktop) --}}
        <div class="hidden md:flex items-center gap-1">
            <a href="#cara-kerja" class="land-nav-link px-3.5 py-2 rounded-lg text-sm font-semibold text-[#647164] transition">Cara Kerja</a>
            <a href="#dampak"     class="land-nav-link px-3.5 py-2 rounded-lg text-sm font-semibold text-[#647164] transition">Dampak</a>
            <a href="#tentang"    class="land-nav-link px-3.5 py-2 rounded-lg text-sm font-semibold text-[#647164] transition">Tentang</a>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-1.5 h-10 px-4 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="hidden sm:inline-flex h-10 px-4 rounded-xl text-sm font-bold text-[#172117] hover:bg-[#F0FDF4] hover:text-[#166534] transition items-center">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-1.5 h-10 px-4 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
                    Daftar Gratis
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- ============ HERO ============ --}}
<section class="relative overflow-hidden">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 pt-14 sm:pt-20 pb-16 sm:pb-24 grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

        {{-- Text --}}
        <div class="fade-up">
            <div class="inline-flex items-center gap-2 pl-1.5 pr-4 py-1.5 rounded-full bg-[#DCFCE7] text-[#166534] text-xs font-bold mb-6">
                <span class="w-6 h-6 rounded-full bg-[#16A34A] text-white grid place-items-center">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
                        <path d="M2 21c0-3 1.85-5.36 5.08-6"/>
                    </svg>
                </span>
                Platform reuse &amp; recycle untuk Indonesia
            </div>

            <h1 class="text-[34px] sm:text-5xl lg:text-[52px] font-extrabold tracking-tight leading-[1.08]">
                Barang tak terpakai,<br>
                <span class="text-[#166534]">tetap tersambung</span> manfaatnya.
            </h1>

            <p class="mt-6 text-base sm:text-lg text-[#647164] leading-relaxed max-w-[520px]">
                SILABUNG menghubungkan barang yang sudah tidak kamu gunakan dengan orang
                yang membutuhkannya — untuk dipakai kembali atau didaur ulang.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 h-12 px-6 rounded-xl bg-[#16A34A] text-white font-bold text-sm shadow-lg shadow-green-600/25 hover:bg-[#166534] hover:-translate-y-0.5 transition">
                    Mulai Sekarang
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#cara-kerja"
                   class="inline-flex items-center gap-2 h-12 px-6 rounded-xl bg-white border border-[#E3EAE3] text-[#172117] font-bold text-sm hover:border-[#16A34A] hover:text-[#166534] hover:bg-[#F0FDF4] transition">
                    Lihat Cara Kerja
                </a>
            </div>

            {{-- Mini stats --}}
            <div class="mt-10 flex flex-wrap gap-8">
                <div>
                    <div class="text-2xl font-extrabold tracking-tight text-[#166534]">100+</div>
                    <div class="text-xs text-[#647164] font-medium">Barang tersedia</div>
                </div>
                <div class="w-px bg-[#E3EAE3] hidden sm:block"></div>
                <div>
                    <div class="text-2xl font-extrabold tracking-tight text-[#166534]">10</div>
                    <div class="text-xs text-[#647164] font-medium">Kategori barang</div>
                </div>
                <div class="w-px bg-[#E3EAE3] hidden sm:block"></div>
                <div>
                    <div class="text-2xl font-extrabold tracking-tight text-[#166534]">0 Rp</div>
                    <div class="text-xs text-[#647164] font-medium">Gratis selamanya</div>
                </div>
            </div>
        </div>

        {{-- Visual --}}
        <div class="fade-up-2 relative flex items-center justify-center min-h-[360px] sm:min-h-[420px]">
            {{-- Glow --}}
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(22,163,74,0.16),transparent_65%)] rounded-full"></div>
            {{-- Circular economy illustration --}}
            <svg viewBox="0 0 400 400" class="relative w-full max-w-[420px]" aria-hidden="true">
                {{-- Outer circle dashed --}}
                <circle cx="200" cy="200" r="150" fill="none" stroke="#DCFCE7" stroke-width="2" stroke-dasharray="6 8"/>
                <circle cx="200" cy="200" r="115" fill="none" stroke="#DCFCE7" stroke-width="2"/>
                {{-- Rotating arrows --}}
                <g class="spin-slow">
                    <path d="M 200 40 A 160 160 0 0 1 340 130" fill="none" stroke="#16A34A" stroke-width="2.5" stroke-linecap="round" opacity="0.5"/>
                    <path d="M 360 200 A 160 160 0 0 1 270 340" fill="none" stroke="#16A34A" stroke-width="2.5" stroke-linecap="round" opacity="0.4"/>
                    <path d="M 200 360 A 160 160 0 0 1 60 270" fill="none" stroke="#16A34A" stroke-width="2.5" stroke-linecap="round" opacity="0.5"/>
                    <path d="M 40 200 A 160 160 0 0 1 130 60" fill="none" stroke="#16A34A" stroke-width="2.5" stroke-linecap="round" opacity="0.4"/>
                </g>

                {{-- Center icon --}}
                <circle cx="200" cy="200" r="62" fill="#166534"/>
                <g transform="translate(200 200)">
                    <g transform="translate(-22 -22)" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none">
                        <path d="M32 4l6 6-6 6"/>
                        <path d="M3 17v-2a6 6 0 0 1 6-6h29"/>
                        <path d="M12 40l-6-6 6-6"/>
                        <path d="M41 27v2a6 6 0 0 1-6 6H6"/>
                    </g>
                </g>

                {{-- Orbit items --}}
                <g>
                    <circle cx="200" cy="50" r="26" fill="#fff" stroke="#E3EAE3" stroke-width="1.5"/>
                    <text x="200" y="58" text-anchor="middle" font-size="22">📦</text>
                </g>
                <g>
                    <circle cx="350" cy="200" r="26" fill="#fff" stroke="#E3EAE3" stroke-width="1.5"/>
                    <text x="350" y="208" text-anchor="middle" font-size="22">🍶</text>
                </g>
                <g>
                    <circle cx="200" cy="350" r="26" fill="#fff" stroke="#E3EAE3" stroke-width="1.5"/>
                    <text x="200" y="358" text-anchor="middle" font-size="22">🪵</text>
                </g>
                <g>
                    <circle cx="50" cy="200" r="26" fill="#fff" stroke="#E3EAE3" stroke-width="1.5"/>
                    <text x="50" y="208" text-anchor="middle" font-size="22">👕</text>
                </g>

                {{-- Small orbit items --}}
                <g>
                    <circle cx="95" cy="95" r="20" fill="#fff" stroke="#E3EAE3" stroke-width="1.5"/>
                    <text x="95" y="102" text-anchor="middle" font-size="16">📄</text>
                </g>
                <g>
                    <circle cx="305" cy="95" r="20" fill="#fff" stroke="#E3EAE3" stroke-width="1.5"/>
                    <text x="305" y="102" text-anchor="middle" font-size="16">🥫</text>
                </g>
                <g>
                    <circle cx="305" cy="305" r="20" fill="#fff" stroke="#E3EAE3" stroke-width="1.5"/>
                    <text x="305" y="312" text-anchor="middle" font-size="16">🔌</text>
                </g>
                <g>
                    <circle cx="95" cy="305" r="20" fill="#fff" stroke="#E3EAE3" stroke-width="1.5"/>
                    <text x="95" y="312" text-anchor="middle" font-size="16">🫙</text>
                </g>
            </svg>
        </div>
    </div>
</section>

{{-- ============ CARA KERJA ============ --}}
<section id="cara-kerja" class="bg-white border-y border-[#E3EAE3]">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 py-16 sm:py-20">
        <div class="text-center max-w-2xl mx-auto mb-14" data-reveal>
            <div class="inline-block px-3 py-1 rounded-full bg-[#DCFCE7] text-[#166534] text-[11px] font-extrabold tracking-widest uppercase mb-4">
                Cara Kerja
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                Dari tidak terpakai, jadi bermanfaat
            </h2>
            <p class="mt-4 text-[#647164] leading-relaxed">
                Empat langkah sederhana yang menghubungkan pemilik barang dengan orang yang membutuhkannya.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $steps = [
                    ['n' => '01', 'title' => 'Bagikan', 'desc' => 'Supplier mendaftarkan barang yang sudah tidak digunakan — lengkap dengan jumlah, kondisi, dan lokasi.', 'icon' => 'package'],
                    ['n' => '02', 'title' => 'Temukan', 'desc' => 'Customer mencari barang yang dibutuhkan lewat kata kunci atau kategori, diurutkan berdasarkan jarak terdekat.', 'icon' => 'search'],
                    ['n' => '03', 'title' => 'Hubungkan', 'desc' => 'Customer melihat detail supplier, lokasi, dan jarak. Lalu mengajukan permintaan pengambilan barang.', 'icon' => 'map-pin'],
                    ['n' => '04', 'title' => 'Gunakan Kembali', 'desc' => 'Supplier menerima permintaan. Barang kembali digunakan atau didaur ulang — bukan dibuang sia-sia.', 'icon' => 'leaf'],
                ];
            @endphp

            @foreach ($steps as $s)
                <div class="bg-[#F8FAF8] border border-[#E3EAE3] rounded-2xl p-6 relative overflow-hidden group hover:border-[#16A34A] hover:bg-[#F0FDF4] transition" data-reveal data-delay="{{ $loop->iteration }}">
                    <div class="text-[11px] font-extrabold tracking-widest text-[#16A34A] mb-4">
                        LANGKAH {{ $s['n'] }}
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#DCFCE7] text-[#166534] grid place-items-center mb-4 group-hover:bg-[#16A34A] group-hover:text-white transition">
                        @if ($s['icon'] === 'package')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <path d="M3.3 7L12 12l8.7-5M12 22V12"/>
                            </svg>
                        @elseif ($s['icon'] === 'search')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>
                            </svg>
                        @elseif ($s['icon'] === 'map-pin')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        @else
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
                                <path d="M2 21c0-3 1.85-5.36 5.08-6"/>
                            </svg>
                        @endif
                    </div>
                    <h3 class="font-extrabold text-base mb-2 tracking-tight">{{ $s['title'] }}</h3>
                    <p class="text-sm text-[#647164] leading-relaxed">{{ $s['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ DAMPAK ============ --}}
<section id="dampak" class="max-w-6xl mx-auto px-5 sm:px-6 py-16 sm:py-20" data-reveal>
    <div class="bg-gradient-to-br from-[#166534] to-[#16A34A] rounded-3xl p-8 sm:p-12 text-white relative overflow-hidden" data-reveal>
        {{-- Decorative circles --}}
        <div class="absolute -right-24 -top-24 w-80 h-80 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-20 w-72 h-72 rounded-full bg-white/5 pointer-events-none"></div>

        <div class="relative">
            <div class="max-w-xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                    Setiap barang yang tersambung, satu langkah lebih kecil jejaknya.
                </h2>
                <p class="mt-4 text-white/75 leading-relaxed">
                    SILABUNG mengubah barang yang sebelumnya berpotensi menjadi sampah menjadi
                    resource yang bisa ditemukan, dipakai kembali, dan bermanfaat bagi orang lain.
                </p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
                @php
                    $impact = [
                        ['v' => '120+', 'l' => 'Barang diselamatkan'],
                        ['v' => '35',   'l' => 'Supplier aktif'],
                        ['v' => '80+',  'l' => 'Customer terbantu'],
                        ['v' => '60+',  'l' => 'Barang digunakan kembali'],
                    ];
                @endphp
                @foreach ($impact as $i)
                    <div>
                        <div class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-none">{{ $i['v'] }}</div>
                        <div class="text-xs text-white/70 font-medium mt-1.5">{{ $i['l'] }}</div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 text-[11px] text-white/50 italic">
                *Angka di atas adalah data demo untuk keperluan presentasi.
            </div>
        </div>
    </div>
</section>

{{-- ============ TENTANG / VALUE PROP ============ --}}
<section id="tentang" class="bg-white border-y border-[#E3EAE3]" data-reveal>
    <div class="max-w-6xl mx-auto px-5 sm:px-6 py-16 sm:py-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-block px-3 py-1 rounded-full bg-[#DCFCE7] text-[#166534] text-[11px] font-extrabold tracking-widest uppercase mb-4">
                    Kenapa SILABUNG
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                    Bukan marketplace.<br>
                    Ini platform <span class="text-[#166534]">reuse-first</span>.
                </h2>
                <p class="mt-5 text-[#647164] leading-relaxed">
                    Kami tidak fokus pada transaksi uang. Fokus kami adalah menghubungkan
                    barang yang masih punya nilai dengan orang yang benar-benar membutuhkannya —
                    mahasiswa, pengrajin, komunitas, UMKM, dan rumah tangga.
                </p>

                <div class="mt-8 space-y-4">
                    @php
                        $values = [
                            ['t' => 'Resource matching', 'd' => 'Menemukan barang berdasarkan kebutuhan, bukan sekadar katalog.'],
                            ['t' => 'Location-based discovery', 'd' => 'Customer melihat jarak dan lokasi supplier sebelum mengambil.'],
                            ['t' => 'Reuse sebelum recycle', 'd' => 'Pakai kembali dulu, baru daur ulang. Urutan itu penting.'],
                            ['t' => 'Gratis & tanpa perantara', 'd' => 'Tidak ada biaya, tidak ada komisi. Langsung antar pengguna.'],
                        ];
                    @endphp
                    @foreach ($values as $v)
                        <div class="flex gap-3.5">
                            <div class="w-6 h-6 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center shrink-0 mt-0.5">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6L9 17l-5-5"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-[15px] tracking-tight">{{ $v['t'] }}</div>
                                <div class="text-sm text-[#647164] mt-0.5 leading-relaxed">{{ $v['d'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Mock card --}}
            <div class="relative">
                <div class="absolute -inset-6 bg-[radial-gradient(circle_at_50%_50%,rgba(22,163,74,0.1),transparent_70%)] rounded-full"></div>

                <div class="relative bg-[#F8FAF8] border border-[#E3EAE3] rounded-2xl p-5 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 text-xs font-bold text-[#647164]">
                            <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                            Rekomendasi di sekitarmu
                        </div>
                        <div class="text-[10px] font-extrabold tracking-wider text-[#166534] bg-[#DCFCE7] px-2 py-0.5 rounded-full">
                            ± 3 KM
                        </div>
                    </div>

                    @php
                        $previewItems = [
                            ['e' => '📦', 'n' => 'Kardus Bekas', 's' => '10 pcs · Baik', 'd' => '2,1 km'],
                            ['e' => '🍶', 'n' => 'Botol Plastik', 's' => '25 pcs · Baik', 'd' => '4,3 km'],
                            ['e' => '🪵', 'n' => 'Kayu Bekas', 's' => '8 pcs · Cukup', 'd' => '5,1 km'],
                        ];
                    @endphp

                    <div class="space-y-2.5">
                        @foreach ($previewItems as $p)
                            <div class="bg-white border border-[#E3EAE3] rounded-xl p-3.5 flex items-center gap-3">
                                <div class="w-11 h-11 rounded-lg bg-[#DCFCE7] grid place-items-center text-xl shrink-0">
                                    {{ $p['e'] }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-sm truncate">{{ $p['n'] }}</div>
                                    <div class="text-[11px] text-[#647164]">{{ $p['s'] }}</div>
                                </div>
                                <div class="text-[11px] font-extrabold text-[#166534] bg-[#DCFCE7] px-2 py-1 rounded-full shrink-0">
                                    {{ $p['d'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-4 border-t border-[#E3EAE3] text-center text-[11px] text-[#647164] font-semibold">
                        Contoh tampilan pencarian
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ CTA FINAL ============ --}}
<section class="max-w-6xl mx-auto px-5 sm:px-6 py-16 sm:py-20" data-reveal>
    <div class="bg-gradient-to-br from-[#DCFCE7] via-white to-[#F0FDF4] border border-[#cfe6d5] rounded-3xl px-8 sm:px-14 py-14 sm:py-16 text-center relative overflow-hidden" data-reveal>
        <div class="absolute right-0 top-0 w-64 h-64 bg-[radial-gradient(circle,rgba(22,163,74,0.12),transparent_70%)]"></div>

        <div class="relative max-w-2xl mx-auto">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                Ada barang menganggur di rumah?
            </h2>
            <p class="mt-4 text-[#647164] text-base leading-relaxed">
                Bagikan sekarang. Mungkin ada mahasiswa, pengrajin, atau komunitas yang sedang membutuhkannya.
            </p>

            <div class="mt-8 flex flex-wrap gap-3 justify-center">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 h-12 px-7 rounded-xl bg-[#16A34A] text-white font-bold text-sm shadow-lg shadow-green-600/25 hover:bg-[#166534] hover:-translate-y-0.5 transition">
                    Daftar & Bagikan Barang
                </a>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 h-12 px-7 rounded-xl bg-white border border-[#E3EAE3] text-[#172117] font-bold text-sm hover:border-[#16A34A] hover:text-[#166534] transition">
                    Sudah punya akun? Masuk
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ============ FOOTER ============ --}}
<footer class="border-t border-[#E3EAE3] bg-white" data-reveal>
    <div class="max-w-6xl mx-auto px-5 sm:px-6 py-12">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Brand --}}
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#16A34A] to-[#166534] grid place-items-center text-white">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 2l4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/>
                            <path d="M7 22l-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-extrabold tracking-tight">SILABUNG</div>
                        <div class="text-[10px] text-[#647164] font-semibold tracking-wide">Sirkula Sambung</div>
                    </div>
                </div>
                <p class="text-sm text-[#647164] leading-relaxed max-w-sm">
                    Platform yang menghubungkan barang tak terpakai dengan orang yang membutuhkannya,
                    agar tetap bermanfaat dan tidak langsung menjadi sampah.
                </p>
            </div>

            {{-- Menu --}}
            <div>
                <div class="text-[11px] font-extrabold uppercase tracking-widest text-[#166534] mb-4">Platform</div>
                <ul class="space-y-2.5 text-sm text-[#647164]">
                    <li><a href="#cara-kerja" class="hover:text-[#166534]">Cara Kerja</a></li>
                    <li><a href="#dampak"     class="hover:text-[#166534]">Dampak</a></li>
                    <li><a href="#tentang"    class="hover:text-[#166534]">Tentang</a></li>
                </ul>
            </div>

            {{-- Akun --}}
            <div>
                <div class="text-[11px] font-extrabold uppercase tracking-widest text-[#166534] mb-4">Akun</div>
                <ul class="space-y-2.5 text-sm text-[#647164]">
                    <li><a href="{{ route('login') }}"    class="hover:text-[#166534]">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-[#166534]">Daftar</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-12 pt-6 border-t border-[#E3EAE3] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#647164]">
            <div>© {{ date('Y') }} SILABUNG — Sirkula Sambung.</div>
            <div class="flex items-center gap-1.5">
                Dibuat untuk mendukung <span class="font-bold text-[#166534]">circular economy</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
                </svg>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ================================================================
       1) SMOOTH SCROLL ke section saat klik menu navbar
       ================================================================ */
    const navLinks = document.querySelectorAll('.land-nav-link');
    const OFFSET   = 80; // tinggi navbar + sedikit margin

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (!href || !href.startsWith('#')) return;

            const target = document.querySelector(href);
            if (!target) return;

            e.preventDefault();

            // Hitung posisi target
            const top = target.getBoundingClientRect().top + window.pageYOffset - OFFSET;

            // Scroll halus
            window.scrollTo({ top, behavior: 'smooth' });

            // Update URL tanpa reload
            history.pushState(null, '', href);

            // Highlight section yang dituju
            target.classList.remove('section-highlight');
            // force reflow biar animasi bisa diputar ulang
            void target.offsetWidth;
            target.classList.add('section-highlight');
            setTimeout(() => target.classList.remove('section-highlight'), 2000);
        });
    });

    /* ================================================================
       2) REVEAL ON SCROLL — section muncul saat masuk viewport
       ================================================================ */
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -40px 0px',
    });

    document.querySelectorAll('[data-reveal]').forEach(el => revealObserver.observe(el));

    /* ================================================================
       3) HIGHLIGHT NAV LINK AKTIF sesuai section yang terlihat
       ================================================================ */
    const sections = document.querySelectorAll('section[id]');

    const setActiveLink = () => {
        const fromTop = window.scrollY + 120;

        let currentId = '';
        sections.forEach(sec => {
            if (sec.offsetTop <= fromTop) {
                currentId = sec.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            const href = link.getAttribute('href') || '';
            link.classList.toggle('active', href === '#' + currentId);
        });
    };

    window.addEventListener('scroll', setActiveLink, { passive: true });
    setActiveLink();

    /* ================================================================
       4) PARALLAX RINGAN di hero visual (opsional)
       ================================================================ */
    const heroVisual = document.querySelector('.hero-visual');
    if (heroVisual && window.innerWidth > 900) {
        window.addEventListener('scroll', () => {
            const y = window.scrollY;
            if (y < 700) {
                heroVisual.style.transform = `translateY(${y * 0.12}px)`;
            }
        }, { passive: true });
    }

    /* ================================================================
       5) Kalau halaman dibuka dengan hash di URL (#cara-kerja),
          scroll langsung ke section itu dengan animasi.
       ================================================================ */
    if (window.location.hash) {
        setTimeout(() => {
            const target = document.querySelector(window.location.hash);
            if (target) {
                const top = target.getBoundingClientRect().top + window.pageYOffset - OFFSET;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        }, 100);
    }
});
</script>
</body>
</html>