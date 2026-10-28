<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan — SILABUNG</title>
    <meta name="description" content="Syarat dan ketentuan penggunaan platform SILABUNG.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        (function () {
            try {
                if (localStorage.getItem('silabung-theme') === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; scroll-padding-top: 80px; }
        
        /* Gaya untuk teks paragraf */
        .tos-content p { margin-bottom: 1rem; line-height: 1.7; color: #647164; }
        .tos-content ul { list-style-type: disc; margin-left: 1.5rem; margin-bottom: 1rem; color: #647164; }
        .tos-content li { margin-bottom: 0.5rem; }
    </style>

    <style id="silabung-dark-css">
        html.dark { color-scheme: dark; }
        html.dark body { background-color: #0D130D !important; color: #E7EFE7 !important; }
        html.dark .bg-white { background-color: #1A2119 !important; }
        html.dark .bg-\[\#F8FAF8\] { background-color: #0D130D !important; }
        html.dark .text-\[\#172117\] { color: #E7EFE7 !important; }
        html.dark .text-\[\#647164\] { color: #96A096 !important; }
        html.dark .text-\[\#166534\] { color: #7CD98F !important; }
        html.dark .border-\[\#E3EAE3\] { border-color: #2A3A2A !important; }
        html.dark .hover\:bg-\[\#F0FDF4\]:hover { background-color: #1A2A1E !important; }
        html.dark .bg-\[\#F8FAF8\]\/90 { background-color: rgba(13, 19, 13, .92) !important; }
        html.dark nav .text-\[\#172117\] { color: #E7EFE7 !important; }
        html.dark .tos-content p, html.dark .tos-content ul, html.dark .tos-content li { color: #96A096 !important; }
    </style>
</head>
<body class="bg-[#F8FAF8] text-[#172117] antialiased">

{{-- ============ NAVBAR ============ --}}
<nav class="sticky top-0 z-50 bg-[#F8FAF8]/90 backdrop-blur-md border-b border-[#E3EAE3]">
    <div class="max-w-5xl mx-auto px-5 h-[68px] flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#16A34A] to-[#166534] grid place-items-center text-white">
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

        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}"
               class="hidden sm:inline-flex h-10 px-4 rounded-xl text-sm font-bold text-[#647164] hover:bg-[#F0FDF4] hover:text-[#166534] transition items-center">
                Beranda
            </a>
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center h-10 px-4 rounded-xl bg-[#16A34A] text-white text-sm font-bold hover:bg-[#166534] transition">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center h-10 px-4 rounded-xl bg-[#16A34A] text-white text-sm font-bold hover:bg-[#166534] transition">
                    Masuk
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- ============ KONTEN UTAMA ============ --}}
<main class="max-w-3xl mx-auto px-5 pt-16 pb-12">
    
    <div class="mb-10 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#DCFCE7] text-[#166534] mb-6 shadow-sm">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
                <polyline points="10 9 9 9 8 9"/>
            </svg>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">Syarat & Ketentuan</h1>
    </div>

    <div class="bg-white border border-[#E3EAE3] rounded-2xl p-6 sm:p-10 shadow-sm tos-content">
        
        <h2 class="text-lg font-extrabold text-[#172117] mb-3">1. Pendahuluan</h2>
        <p>
            Selamat datang di <b>SILABUNG (Sirkula Sambung)</b>. Dengan mengakses dan menggunakan platform kami, Anda dianggap telah membaca, memahami, dan menyetujui seluruh Syarat dan Ketentuan yang tertulis di halaman ini. Jika Anda tidak setuju dengan salah satu poin, mohon untuk tidak menggunakan layanan kami.
        </p>

        <h2 class="text-lg font-extrabold text-[#172117] mt-8 mb-3">2. Definisi Layanan</h2>
        <p>
            SILABUNG adalah platform berbasis web yang mempertemukan individu yang ingin menyumbangkan barang layak pakai (<b>Supplier</b>) dengan individu yang membutuhkan barang tersebut (<b>Customer</b>). SILABUNG tidak memungut biaya, komisi, atau transaksi finansial apa pun dari pengguna.
        </p>

        <h2 class="text-lg font-extrabold text-[#172117] mt-8 mb-3">3. Kewajiban Pengguna</h2>
        <p><b>A. Kewajiban Supplier (Pemberi Barang):</b></p>
        <ul>
            <li>Supplier wajib memastikan bahwa barang yang diunggah adalah barang yang <b>benar-benar miliknya</b> dan bukan barang ilegal/curian.</li>
            <li>Barang yang dibagikan harus dalam kondisi layak pakai, bersih, dan tidak berbahaya bagi penerima.</li>
            <li>Supplier wajib mengunggah foto asli untuk keperluan verifikasi (KTM/KRS/SKL) demi mencegah penyalahgunaan identitas.</li>
            <li>Supplier wajib mengonfirmasi serah terima barang melalui fitur kode verifikasi di platform.</li>
        </ul>
        
        <p class="mt-4"><b>B. Kewajiban Customer (Penerima Barang):</b></p>
        <ul>
            <li>Customer dilarang menyalahgunakan platform untuk memperjualbelikan kembali barang donasi secara komersial tanpa izin dari Supplier.</li>
            <li>Customer wajib mengajukan permintaan dengan jujur sesuai dengan kebutuhan yang sebenarnya.</li>
            <li>Customer bertanggung jawab untuk mengambil barang sesuai dengan waktu dan lokasi yang telah disepakati.</li>
        </ul>

        <h2 class="text-lg font-extrabold text-[#172117] mt-8 mb-3">4. Batasan Tanggung Jawab</h2>
        <p>
            SILABUNG hanya berfungsi sebagai jembatan penghubung. Segala bentuk kerusakan barang, kecelakaan, atau perselisihan yang terjadi selama proses komunikasi dan serah terima barang adalah tanggung jawab penuh dari masing-masing pengguna yang terlibat. SILABUNG tidak menanggung kompensasi dalam bentuk apa pun.
        </p>

        <h2 class="text-lg font-extrabold text-[#172117] mt-8 mb-3">5. Penghapusan Akun & Sanksi</h2>
        <p>
            Kami berhak untuk menolak, membekukan, atau menghapus akun pengguna secara sepihak apabila ditemukan indikasi kecurangan, manipulasi data (seperti pemalsuan dokumen verifikasi), atau pelanggaran terhadap syarat dan ketentuan ini.
        </p>

        <h2 class="text-lg font-extrabold text-[#172117] mt-8 mb-3">6. Perubahan Ketentuan</h2>
        <p>
            SILABUNG dapat memperbarui Syarat dan Ketentuan ini dari waktu ke waktu tanpa pemberitahuan sebelumnya. Pengguna disarankan untuk meninjau halaman ini secara berkala.
        </p>
    </div>
</main>

{{-- ============ FOOTER ============ --}}
<footer class="border-t border-[#E3EAE3] bg-white mt-auto">
    <div class="max-w-5xl mx-auto px-5 py-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#647164]">
        <div>© {{ date('Y') }} SILABUNG — Sirkula Sambung</div>
        <div class="flex items-center gap-5">
            <a href="{{ route('tentang') }}" class="hover:text-[#166534]">Tentang Kami</a>
        </div>
    </div>
</footer>

</body>
</html>