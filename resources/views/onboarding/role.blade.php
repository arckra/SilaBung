<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Peran — SILABUNG</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .role-card.selected {
            border-color: #16A34A;
            background: #F0FDF4;
            box-shadow: 0 0 0 4px rgba(22, 163, 74, .12);
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: none; }
        }
        .fade-up { animation: fadeUp .6s cubic-bezier(.2,.9,.3,1) both; }
    </style>
</head>
<body class="min-h-screen bg-[#F8FAF8] text-[#172117] antialiased">

<div class="min-h-screen flex flex-col items-center justify-center px-5 py-12">
    <div class="w-full max-w-3xl fade-up">

        {{-- ================= LOGO ================= --}}
        <div class="text-center mb-10">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#16A34A] to-[#166534] grid place-items-center text-white shadow-md shadow-green-500/30">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 2l4 4-4 4"/>
                        <path d="M3 11v-1a4 4 0 0 1 4-4h14"/>
                        <path d="M7 22l-4-4 4-4"/>
                        <path d="M21 13v1a4 4 0 0 1-4 4H3"/>
                    </svg>
                </div>
                <div class="text-left">
                    <div class="font-extrabold tracking-tight text-[17px] leading-tight">SILABUNG</div>
                    <div class="text-[10px] text-[#647164] font-semibold tracking-wide">Sirkula Sambung</div>
                </div>
            </a>

            {{-- Step indicator --}}
            <div class="flex justify-center gap-1.5 mb-6">
                <span class="w-7 h-2 rounded-full bg-[#16A34A]"></span>
                <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
            </div>

            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                Kamu ingin menggunakan<br>SILABUNG sebagai apa?
            </h1>
            <p class="text-[#647164] mt-4 max-w-md mx-auto text-sm sm:text-base">
                Pilih dengan hati-hati — peran ini
                <span class="font-semibold text-[#166534]">tidak dapat diubah</span>
                setelah dipilih.
            </p>
        </div>

        {{-- ================= ERROR ================= --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex gap-2 items-start">
                <svg class="shrink-0 mt-0.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4M12 16h.01"/>
                </svg>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        {{-- ================= FORM ================= --}}
        <form method="POST" action="{{ route('onboarding.role.store') }}" id="roleForm">
            @csrf
            <input type="hidden" name="role" id="roleInput" value="">

            <div class="grid sm:grid-cols-2 gap-4">

                {{-- ============ CARD: CUSTOMER ============ --}}
                <button type="button"
                        class="role-card group text-left bg-white border-2 border-[#E3EAE3] rounded-2xl p-7 transition-all hover:border-[#16A34A] hover:shadow-lg hover:-translate-y-1"
                        data-role="customer">
                    <div class="w-14 h-14 rounded-2xl bg-[#DCFCE7] text-[#166534] grid place-items-center mb-5">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="M21 21l-4.3-4.3"/>
                        </svg>
                    </div>

                    <div class="text-[11px] font-extrabold tracking-widest text-[#16A34A] mb-1.5">
                        SAYA MEMBUTUHKAN BARANG
                    </div>
                    <h3 class="text-lg font-extrabold mb-2 tracking-tight">CUSTOMER</h3>
                    <p class="text-sm text-[#647164] leading-relaxed">
                        Temukan barang bekas yang masih berguna untuk kebutuhanmu.
                    </p>

                    <ul class="mt-5 space-y-2 text-sm text-[#647164]">
                        <li class="flex gap-2 items-start">
                            <svg class="text-[#16A34A] shrink-0 mt-0.5" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                            Cari barang berdasarkan kategori &amp; jarak
                        </li>
                        <li class="flex gap-2 items-start">
                            <svg class="text-[#16A34A] shrink-0 mt-0.5" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                            Lihat lokasi &amp; jarak supplier
                        </li>
                        <li class="flex gap-2 items-start">
                            <svg class="text-[#16A34A] shrink-0 mt-0.5" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                            Ajukan permintaan pengambilan
                        </li>
                    </ul>
                </button>

                {{-- ============ CARD: SUPPLIER ============ --}}
                <button type="button"
                        class="role-card group text-left bg-white border-2 border-[#E3EAE3] rounded-2xl p-7 transition-all hover:border-[#16A34A] hover:shadow-lg hover:-translate-y-1"
                        data-role="supplier">
                    <div class="w-14 h-14 rounded-2xl bg-[#DCFCE7] text-[#166534] grid place-items-center mb-5">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <path d="M3.3 7L12 12l8.7-5M12 22V12"/>
                        </svg>
                    </div>

                    <div class="text-[11px] font-extrabold tracking-widest text-[#16A34A] mb-1.5">
                        SAYA MEMILIKI BARANG
                    </div>
                    <h3 class="text-lg font-extrabold mb-2 tracking-tight">SUPPLIER</h3>
                    <p class="text-sm text-[#647164] leading-relaxed">
                        Bagikan barang yang sudah tidak kamu gunakan agar dapat dimanfaatkan kembali.
                    </p>

                    <ul class="mt-5 space-y-2 text-sm text-[#647164]">
                        <li class="flex gap-2 items-start">
                            <svg class="text-[#16A34A] shrink-0 mt-0.5" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                            Daftarkan barang + jumlah stok
                        </li>
                        <li class="flex gap-2 items-start">
                            <svg class="text-[#16A34A] shrink-0 mt-0.5" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                            Kelola stok &amp; lokasi
                        </li>
                        <li class="flex gap-2 items-start">
                            <svg class="text-[#16A34A] shrink-0 mt-0.5" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                            Terima atau tolak permintaan
                        </li>
                    </ul>
                </button>
            </div>

            {{-- ============ LOKASI (muncul setelah pilih role) ============ --}}
            <div id="locationFields" class="hidden mt-6 bg-white border border-[#E3EAE3] rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                    <div>
                        <h3 class="font-extrabold text-base">
                            Lokasi kamu
                            <span class="text-[#647164] font-medium text-sm">(opsional)</span>
                        </h3>
                        <p class="text-xs text-[#647164] mt-0.5">
                            Digunakan untuk menghitung jarak ke supplier.
                        </p>
                    </div>
                    <button type="button" id="useGeoBtn"
                            class="inline-flex items-center gap-1.5 px-3.5 h-9 rounded-lg bg-[#DCFCE7] text-[#166534] text-xs font-bold hover:bg-[#bbf7d0] transition">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Deteksi otomatis
                    </button>
                </div>

                <div class="grid sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold mb-1.5">Kota / Kecamatan</label>
                        <input type="text" name="city" id="cityInput"
                               placeholder="Cikarang Selatan"
                               class="w-full h-11 px-3.5 rounded-lg border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-[#16A34A]/15 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1.5">Latitude</label>
                        <input type="text" name="latitude" id="latInput" readonly
                               class="w-full h-11 px-3.5 rounded-lg border border-[#E3EAE3] bg-[#F8FAF8] text-sm text-[#647164]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1.5">Longitude</label>
                        <input type="text" name="longitude" id="lngInput" readonly
                               class="w-full h-11 px-3.5 rounded-lg border border-[#E3EAE3] bg-[#F8FAF8] text-sm text-[#647164]">
                    </div>
                    <div class="flex items-end">
                        <p class="text-xs text-[#647164]">Bisa diisi nanti di profil.</p>
                    </div>
                </div>
            </div>

            {{-- ============ SUBMIT ============ --}}
            <div class="mt-7 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <p class="text-xs text-[#647164] order-2 sm:order-1">
                    Peran tidak dapat diubah setelah dikonfirmasi.
                </p>
                <button type="submit" id="submitBtn" disabled
                        class="order-1 sm:order-2 h-12 px-8 rounded-xl bg-[#16A34A] text-white font-bold text-sm shadow-md shadow-green-600/20 hover:bg-[#166534] transition disabled:opacity-40 disabled:cursor-not-allowed">
                    Konfirmasi Peran
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards     = document.querySelectorAll('.role-card');
        const roleInput = document.getElementById('roleInput');
        const submitBtn = document.getElementById('submitBtn');
        const locFields = document.getElementById('locationFields');
        const geoBtn    = document.getElementById('useGeoBtn');
        const latInput  = document.getElementById('latInput');
        const lngInput  = document.getElementById('lngInput');

        /* ---------- Pilih role ---------- */
        cards.forEach(card => {
            card.addEventListener('click', () => {
                cards.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                roleInput.value    = card.dataset.role;
                submitBtn.disabled = false;
                locFields.classList.remove('hidden');
            });
        });

        /* ---------- Deteksi lokasi ---------- */
        geoBtn.addEventListener('click', () => {
            if (!navigator.geolocation) {
                alert('Browser kamu tidak mendukung deteksi lokasi.');
                return;
            }

            geoBtn.disabled = true;
            geoBtn.textContent = 'Mendeteksi...';

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    latInput.value = pos.coords.latitude.toFixed(7);
                    lngInput.value = pos.coords.longitude.toFixed(7);
                    geoBtn.disabled = false;
                    geoBtn.innerHTML = '✓ Terdeteksi';
                },
                () => {
                    geoBtn.disabled = false;
                    geoBtn.innerHTML = 'Gagal — isi manual nanti';
                    setTimeout(() => {
                        geoBtn.innerHTML = 'Deteksi otomatis';
                    }, 2500);
                },
                { timeout: 8000 }
            );
        });
    });
</script>

</body>
</html>