<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami — SILABUNG</title>
    <meta name="description" content="Kenapa kami membuat SILABUNG, apa yang kami kerjakan, dan siapa di belakangnya.">

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
        article p,
        section p {
            text-align: justify;
            text-justify: inter-word;
            hyphens: auto;
        }
    </style>

    <style id="silabung-dark-css">
        html.dark { color-scheme: dark; }
        html.dark body { background-color: #0D130D !important; color: #E7EFE7 !important; }
        html.dark .bg-white { background-color: #1A2119 !important; }
        html.dark .bg-\[\#F8FAF8\] { background-color: #0D130D !important; }
        html.dark .bg-\[\#F0FDF4\] { background-color: #132018 !important; }
        html.dark .bg-\[\#DCFCE7\] { background-color: #1A3520 !important; }
        html.dark .text-\[\#172117\] { color: #E7EFE7 !important; }
        html.dark .text-\[\#647164\] { color: #96A096 !important; }
        html.dark .text-\[\#166534\] { color: #7CD98F !important; }
        html.dark .border-\[\#E3EAE3\] { border-color: #2A3A2A !important; }
        html.dark .divide-\[\#E3EAE3\] > :not([hidden]) ~ :not([hidden]) { border-color: #2A3A2A !important; }
        html.dark .hover\:bg-\[\#F0FDF4\]:hover { background-color: #1A2A1E !important; }
        html.dark .bg-white\/90 { background-color: rgba(26, 33, 25, .92) !important; }
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
                <a href="{{ route('register') }}"
                   class="inline-flex items-center h-10 px-4 rounded-xl bg-[#16A34A] text-white text-sm font-bold hover:bg-[#166534] transition">
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</nav>

<header class="max-w-3xl mx-auto px-5 pt-16 pb-10">
    <div class="text-[11px] font-extrabold uppercase tracking-widest text-[#16A34A] mb-3">
        Tentang Kami
    </div>
    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight mb-5">
        SILABUNG mulai dari masalah sederhana: barang menumpuk di kos, tapi tidak tahu harus dikasih ke siapa.
    </h1>
    <p class="text-[#647164] text-base leading-relaxed max-w-2xl">
        Kami mahasiswa Universitas Pelita Bangsa. Project ini dibuat karena kami sendiri mengalami masalah itu —
        dan ternyata bukan cuma kami.
    </p>
</header>

<section class="max-w-3xl mx-auto px-5 py-10 border-t border-[#E3EAE3]">
    <h2 class="text-xl font-extrabold tracking-tight mb-5">Kenapa project ini dibuat</h2>

    <div class="space-y-4 text-[#647164] text-[15px] leading-relaxed">
        <p>
            Semuanya mulai dari hal yang cukup sepele. Di kos, di rumah, di kamar barang terus menumpuk.
            Pakaian yang sudah jarang dipakai, buku yang tidak dibuka lagi, peralatan yang tidak terpakai,
            kardus bekas paket yang menumpuk di sudut kamar. Tidak rusak, tapi juga tidak dipakai.
        </p>
        <p>
            Kami bingung harus diapakan. Dibuang sayang. Disimpan terus ya cuma jadi beban. Sering kali akhirnya
            dibiarkan bertumpuk, sampai akhirnya memang dibuang karena sudah tidak ada tempat lagi.
        </p>
        <p>
            Sementara di sisi lain, ada banyak orang yang justru membutuhkan barang-barang layak pakai seperti itu.
            Mahasiswa perantau yang baru mulai hidup sendiri, keluarga yang sedang kesulitan, penghuni panti yang
            butuh pakaian atau perlengkapan. Kebutuhan mereka nyata, tapi sering tidak terpenuhi.
        </p>
        <p>
            Setelah kami pikir-pikir, masalahnya bukan karena tidak ada yang mau memberi. Masalahnya karena
            <b class="text-[#172117]">pemberi dan penerima tidak saling terhubung</b>. Orang yang ingin mendonasikan
            barang bingung harus menyalurkan ke mana. Kalau pun tahu tempatnya, prosesnya sering dianggap ribet —
            harus kirim ke sana, harus antar ke sini, harus daftar di situ. Akhirnya barangnya ya tetap tersimpan.
        </p>
    </div>
</section>

<section class="max-w-3xl mx-auto px-5 py-10 border-t border-[#E3EAE3]">
    <h2 class="text-xl font-extrabold tracking-tight mb-5">Apa yang SILABUNG lakukan</h2>

    <div class="space-y-4 text-[#647164] text-[15px] leading-relaxed">
        <p>
            <b class="text-[#172117]">SILABUNG — Sirkula Sambung</b> adalah platform yang mempertemukan orang yang
            ingin menyalurkan barang layak pakai dengan orang yang membutuhkannya. Langsung, tanpa perantara,
            dan tanpa biaya.
        </p>
        <p>
            Cara kerjanya sederhana. Pemberi cukup mengunggah barang yang ingin disalurkan — lengkap dengan foto,
            kondisi, jumlah, dan lokasinya. Penerima yang sedang membutuhkan cukup mencari dan mengajukan permintaan.
            Setelah keduanya sepakat, mereka mengonfirmasi serah terima di platform.
        </p>
        <p>
            Dengan begitu, barang tidak berhenti sebagai sampah. Barangnya terus <b class="text-[#172117]">beredar
            (sirkula)</b> dan <b class="text-[#172117]">tersambung (sambung)</b> ke orang yang benar-benar
            membutuhkannya.
        </p>
        <p>
            Ada dua peran di SILABUNG. <b class="text-[#172117]">Supplier</b> adalah yang membagikan barang.
            <b class="text-[#172117]">Customer</b> adalah yang mencari dan mengajukan permintaan. Peran ini
            dipilih sekali saat pertama kali daftar, dan tidak bisa diubah — supaya data dan relasinya tetap jelas.
        </p>
    </div>
</section>

<section class="max-w-3xl mx-auto px-5 py-10 border-t border-[#E3EAE3]">
    <h2 class="text-xl font-extrabold tracking-tight mb-5">Berdasarkan penelitian terdahulu</h2>

    <div class="space-y-4 text-[#647164] text-[15px] leading-relaxed">
        <p>
            Konsep penyaluran barang bekas layak pakai sebenarnya bukan hal baru. Sudah ada penelitian-penelitian
            sebelumnya yang membahas soal ini bagaimana barang yang tidak terpakai bisa disalurkan kembali ke
            masyarakat yang membutuhkan.
        </p>
        <p>
            SILABUNG terinspirasi dari penelitian-penelitian tersebut, dan mencoba mengembangkannya dalam skala
            yang lebih kecil. Kami fokuskan dulu ke satu lingkungan yang kami kenal betul:
            <b class="text-[#172117]">mahasiswa Universitas Pelita Bangsa</b>.
        </p>
        <p>
            Kami pilih lingkungan ini karena satu alasan mahasiswa cenderung impulsif dalam membeli. Beli karena
            sedang tren, karena diskon, karena butuh sebentar. Setelah beberapa bulan, barangnya berhenti dipakai
            tapi tidak dibuang karena masih sayang. Akhirnya menumpuk.
        </p>
        <p>
            Dari situ kami berharap SILABUNG bisa ikut membantu: mengurangi penumpukan barang yang tidak terpakai
            di sekitar kos dan rumah, sekaligus menumbuhkan budaya berbagi di lingkungan kampus.
        </p>
    </div>
</section>

<section class="max-w-3xl mx-auto px-5 py-10 border-t border-[#E3EAE3]">
    <h2 class="text-xl font-extrabold tracking-tight mb-6">Prinsip yang kami pegang</h2>

    <div class="space-y-5">
        @php
            $principles = [
                [
                    'n' => '01',
                    't' => 'Barangnya masih layak, bukan sampah',
                    'd' => 'Yang kami salurkan adalah barang yang masih bisa dipakai dan bukan barang rusak. Kalau memang sudah tidak layak, sebaiknya masuk jalur daur ulang, bukan jalur donasi.',
                ],
                [
                    'n' => '02',
                    't' => 'Lingkungan kampus dulu',
                    'd' => 'Kami tidak mulai dari skala besar. Kami fokuskan dulu ke mahasiswa Universitas Pelita Bangsa dan sekitarnya, supaya alur dan kepercayaan antar pengguna lebih mudah dibangun.',
                ],
                [
                    'n' => '03',
                    't' => 'Jujur soal angka',
                    'd' => 'Kalau data di halaman dampak masih berupa contoh, kami bilang itu contoh. Kami tidak mau menampilkan angka palsu hanya supaya kelihatan ramai.',
                ],
                [
                    'n' => '04',
                    't' => 'Tidak ada perantara',
                    'd' => 'SILABUNG tidak mengambil komisi, tidak menahan barang, tidak mengatur harga. Pemberi dan penerima berhubungan langsung, dan sepakat serah terima di platform.',
                ],
            ];
        @endphp

        @foreach ($principles as $p)
            <div class="flex gap-4">
                <div class="text-[13px] font-extrabold text-[#16A34A] tracking-wider shrink-0 w-7 pt-0.5">
                    {{ $p['n'] }}
                </div>
                <div>
                    <div class="font-bold text-[15px] text-[#172117] mb-1">{{ $p['t'] }}</div>
                    <p class="text-[#647164] text-sm leading-relaxed">{{ $p['d'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ============ STATUS PROJECT ============ --}}
<section class="max-w-3xl mx-auto px-5 py-10 border-t border-[#E3EAE3]">
    <h2 class="text-xl font-extrabold tracking-tight mb-5">Status project</h2>

    <div class="bg-[#F0FDF4] border border-[#cfe6d5] rounded-2xl p-5 sm:p-6">
        <p class="text-[15px] text-[#172117] leading-relaxed mb-4">
            SILABUNG masih dalam tahap pengembangan. Beberapa hal sudah jalan penuh (pencarian barang, pengajuan,
            pengelolaan stok, peta lokasi, chat), beberapa masih sederhana dan akan terus diperbaiki.
        </p>
        <div class="grid sm:grid-cols-2 gap-4 text-sm">
            <div>
                <div class="font-bold text-[#166534] mb-2">Sudah jalan</div>
                <ul class="space-y-1 text-[#647164]">
                    <li>· Cari barang berdasarkan kategori &amp; jarak</li>
                    <li>· Ajukan &amp; kelola permintaan</li>
                    <li>· Peta lokasi supplier</li>
                    <li>· Chat antara customer &amp; supplier</li>
                    <li>· Mode terang &amp; gelap</li>
                </ul>
            </div>
            <div>
                <div class="font-bold text-[#166534] mb-2">Masih dikembangkan</div>
                <ul class="space-y-1 text-[#647164]">
                    <li>· Notifikasi real-time</li>
                    <li>· Verifikasi supplier</li>
                    <li>· Estimasi dampak karbon</li>
                    <li>· Mode gelap untuk landing page</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ============ TIM ============ --}}
<section class="max-w-3xl mx-auto px-5 py-10 border-t border-[#E3EAE3]">
    <h2 class="text-xl font-extrabold tracking-tight mb-2">Tim di balik SILABUNG</h2>
    <p class="text-[#647164] text-sm mb-6">
        Project ini dibuat untuk kompetisi web development oleh tim kecil yang terdiri dari beberapa orang.
    </p>

    <div class="grid sm:grid-cols-3 gap-4">
        @php
            $team = [
                ['nama' => 'Ari Cakra Kurniawan',      'peran' => 'Full-Stack Developer'],
                ['nama' => 'Friska Pebriana Lestari',   'peran' => 'Product & Riset'],
                ['nama' => 'Nadia Aulina Safari', 'peran' => 'Product & Riset'],
            ];
        @endphp

        @foreach ($team as $t)
            <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5">
                <div class="w-12 h-12 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center text-base font-extrabold mb-3">
                    {{ strtoupper(substr($t['nama'], 0, 2)) }}
                </div>
                <div class="font-bold text-sm text-[#172117] mb-0.5">{{ $t['nama'] }}</div>
                <div class="text-xs text-[#647164]">{{ $t['peran'] }}</div>
            </div>
        @endforeach
    </div>
</section>

<section class="max-w-3xl mx-auto px-5 py-10 border-t border-[#E3EAE3]">
    <h2 class="text-xl font-extrabold tracking-tight mb-5">Hubungi kami</h2>

    <div class="bg-white border border-[#E3EAE3] rounded-2xl divide-y divide-[#E3EAE3]">
        <a href="https://wa.me/6289665733153" target="_blank" rel="noopener"
           class="flex items-center gap-3.5 px-5 py-4 hover:bg-[#F0FDF4] transition">
            <div class="w-9 h-9 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center shrink-0">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="font-bold text-sm text-[#172117]">WhatsApp</div>
                <div class="text-xs text-[#647164]">+62 896-6573-3153</div>
            </div>
        </a>
        <a href="{{ route('home') }}#tentang"
           class="flex items-center gap-3.5 px-5 py-4 hover:bg-[#F0FDF4] transition">
            <div class="w-9 h-9 rounded-full bg-[#DCFCE7] text-[#166534] grid place-items-center shrink-0">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
                    <path d="M2 21c0-3 1.85-5.36 5.08-6"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="font-bold text-sm text-[#172117]">Pelajari lebih lanjut</div>
                <div class="text-xs text-[#647164]">Baca tentang circular economy &amp; reuse</div>
            </div>
        </a>
    </div>
</section>

{{-- ============ CTA ============ --}}
<section class="max-w-3xl mx-auto px-5 py-12">
    <div class="bg-white border border-[#E3EAE3] rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center gap-5">
        <div class="flex-1">
            <div class="font-extrabold tracking-tight text-lg mb-1">Mulai pakai SILABUNG</div>
            <p class="text-sm text-[#647164]">
                Daftar gratis, pilih peranmu, dan mulai bagikan atau cari barang.
            </p>
        </div>
        <div class="flex gap-2.5 shrink-0">
            <a href="{{ route('home') }}"
               class="inline-flex items-center h-11 px-5 rounded-xl border border-[#E3EAE3] text-sm font-bold hover:bg-[#F8FAF8] transition">
                Beranda
            </a>
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center h-11 px-5 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('register') }}"
                   class="inline-flex items-center h-11 px-5 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
                    Daftar Gratis
                </a>
            @endauth
        </div>
    </div>
</section>

{{-- ============ FOOTER ============ --}}
<footer class="border-t border-[#E3EAE3] bg-white">
    <div class="max-w-5xl mx-auto px-5 py-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#647164]">
        <div>© {{ date('Y') }} SILABUNG — Sirkula Sambung</div>
    </div>
</footer>

</body>
</html>