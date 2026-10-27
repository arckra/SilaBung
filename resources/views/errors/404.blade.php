<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_silabung3.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Animasi kotak melayang */
        .float-group {
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-10px); }
        }

        /* Animasi garis putus-putus radar */
        .spin-dashed {
            animation: spin 25s linear infinite;
            transform-origin: center;
        }
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
        
        /* Animasi daun jatuh */
        .leaf-fall {
            animation: fall 6s ease-in-out infinite;
        }
        @keyframes fall {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.6; }
            50%      { transform: translateY(15px) rotate(15deg); opacity: 1; }
        }
    </style>
</head>
<body class="min-h-screen bg-[#F8FAF8] text-[#172117] antialiased flex flex-col relative overflow-hidden">

    {{-- Dekorasi Background --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#DCFCE7] rounded-full opacity-50 blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-[#DCFCE7] rounded-full opacity-50 blur-3xl"></div>
    </div>

    {{-- Navbar Mini --}}
    <header class="relative z-10 w-full px-6 py-6 flex items-center justify-between max-w-6xl mx-auto">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#16A34A] to-[#166534] grid place-items-center text-white shadow-md shadow-green-500/20">
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
    </header>

    {{-- Konten Utama --}}
    <main class="relative z-10 flex-1 flex flex-col items-center justify-center px-5 text-center py-10">
        
        {{-- Ilustrasi SVG --}}
        <div class="relative w-64 h-64 sm:w-72 sm:h-72 mb-6 flex items-center justify-center">
            <svg viewBox="0 0 200 200" class="w-full h-full">
                {{-- Garis Putus-putus (Radar) --}}
                <circle cx="100" cy="100" r="85" fill="none" stroke="#cfe6d5" stroke-width="2" stroke-dasharray="6 8" class="spin-dashed"/>

                {{-- Elemen Melayang --}}
                <g class="float-group">
                    {{-- Bayangan Kotak --}}
                    <ellipse cx="100" cy="150" rx="35" ry="8" fill="#172117" opacity="0.08" />

                    {{-- Kotak Kardus --}}
                    <rect x="65" y="70" width="70" height="60" rx="6" fill="#FEF3C7" stroke="#F59E0B" stroke-width="2.5"/>
                    
                    {{-- Tutup Kotak --}}
                    <path d="M60 70 L140 70 L130 60 L70 60 Z" fill="#FDE68A" stroke="#F59E0B" stroke-width="2.5" stroke-linejoin="round"/>
                    
                    {{-- Lakban --}}
                    <rect x="92" y="60" width="16" height="70" fill="#F59E0B" opacity="0.3"/>
                    <path d="M92 60 L108 60" stroke="#F59E0B" stroke-width="2.5"/>

                    {{-- Tanda Tanya (?) --}}
                    <circle cx="100" cy="100" r="14" fill="#fff" stroke="#16A34A" stroke-width="3"/>
                    <text x="100" y="108" font-family="'Plus Jakarta Sans', sans-serif" font-size="18" font-weight="800" fill="#16A34A" text-anchor="middle">?</text>

                    {{-- Daun Hijau Kiri --}}
                    <g class="leaf-fall" style="animation-delay: 0s;">
                        <path d="M40 90 C30 70 40 50 55 55 C65 75 55 95 40 90 Z" fill="#16A34A" opacity="0.8"/>
                        <path d="M40 90 C42 75 48 65 55 55" stroke="#166534" stroke-width="1.5" fill="none"/>
                    </g>

                    {{-- Daun Hijau Kanan --}}
                    <g class="leaf-fall" style="animation-delay: 2s;">
                        <path d="M150 130 C165 140 165 165 145 160 C135 145 140 135 150 130 Z" fill="#16A34A" opacity="0.6"/>
                        <path d="M150 130 C150 142 148 152 145 160" stroke="#166534" stroke-width="1.5" fill="none"/>
                    </g>
                </g>
            </svg>
        </div>

        {{-- Teks --}}
        <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight text-[#166534] mb-4">
            404
        </h1>
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-4">
            Ups, halaman ini hilang!
        </h2>
        <p class="text-[#647164] text-sm sm:text-base max-w-md mx-auto leading-relaxed mb-8">
            Sepertinya halaman yang kamu cari sudah dipindahkan, dihapus, atau mungkin didaur ulang menjadi sesuatu yang baru.
        </p>

        {{-- Tombol Aksi --}}
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <a href="{{ route('home') }}"
               class="inline-flex items-center justify-center gap-2 h-12 px-6 rounded-xl bg-[#16A34A] text-white font-bold text-sm shadow-lg shadow-green-600/20 hover:bg-[#166534] hover:-translate-y-0.5 transition w-full sm:w-auto">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Kembali ke Beranda
            </a>

            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center justify-center gap-2 h-12 px-6 rounded-xl bg-white border border-[#E3EAE3] text-[#172117] font-bold text-sm hover:border-[#16A34A] hover:text-[#166534] transition w-full sm:w-auto">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Ke Dashboard
                </a>
            @endauth
        </div>
    </main>

    {{-- Footer --}}
    <footer class="relative z-10 text-center py-6 text-xs text-[#647164]">
        © {{ date('Y') }} SILABUNG — Sirkula Sambung.
    </footer>

</body>
</html>