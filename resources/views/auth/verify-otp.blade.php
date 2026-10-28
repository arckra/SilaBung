@extends('layouts.auth')

@section('title', 'Verifikasi Email')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 rounded-2xl bg-[#DCFCE7] text-[#166534] grid place-items-center mx-auto mb-4">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="4" width="20" height="16" rx="2"/>
            <path d="M22 6l-10 7L2 6"/>
        </svg>
    </div>
    <h1 class="text-2xl font-extrabold tracking-tight">Cek Email Kamu</h1>
    <p class="text-[#647164] text-sm mt-2">
        Kami telah mengirim 6 digit kode verifikasi ke <br>
        <b class="text-[#172117]">{{ session('pending_user.email') }}</b>
    </p>
</div>

@if (session('success'))
    <div class="mb-5 px-4 py-3 rounded-xl bg-[#DCFCE7] border border-green-200 text-[#166534] text-sm font-semibold text-center">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm text-center">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('verification.otp.verify') }}" id="otpForm">
    @csrf
    {{-- Input Tersembunyi: Ini yang akan dikirim ke Controller --}}
    <input type="hidden" name="otp" id="otpValue">

    {{-- 6 Kotak Input Visual --}}
    <div class="flex justify-center gap-2 sm:gap-3 mb-6" id="otpContainer">
        @for ($i = 0; $i < 6; $i++)
            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" autocomplete="off"
                   class="otp-input w-11 h-14 sm:w-14 sm:h-16 text-center text-xl sm:text-2xl font-extrabold rounded-xl border-2 border-[#E3EAE3] focus:border-[#16A34A] focus:ring-0 outline-none transition bg-white text-[#172117]">
        @endfor
    </div>

    <button type="submit" id="submitBtn" disabled
            class="w-full h-12 rounded-xl bg-[#16A34A] text-white font-bold text-sm shadow-lg shadow-green-600/20 hover:bg-[#166534] disabled:opacity-50 disabled:cursor-not-allowed transition">
        Verifikasi Sekarang
    </button>
</form>

<div class="mt-6 text-center text-sm text-[#647164]">
    Tidak menerima kode?
    <form method="POST" action="{{ route('verification.otp.resend') }}" class="inline">
        @csrf
        <button type="submit" class="font-bold text-[#166534] hover:underline">Kirim Ulang</button>
    </form>
</div>

<div class="mt-8 text-center">
    <a href="{{ route('register') }}" class="text-xs text-[#647164] hover:text-[#166534] transition underline">Batal & Kembali ke Daftar</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('otpValue');
    const submitBtn = document.getElementById('submitBtn');

    if (inputs.length > 0) inputs[0].focus();

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            checkComplete();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
                inputs[index - 1].value = '';
                checkComplete();
            }
        });

        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedData = (e.clipboardData || window.clipboardData).getData('text');
            const numbers = pastedData.replace(/[^0-9]/g, '').split('');

            if (numbers.length > 0) {
                inputs.forEach((inp, i) => {
                    if (numbers[i]) inp.value = numbers[i];
                });
                const lastFilled = Math.min(numbers.length - 1, inputs.length - 1);
                inputs[lastFilled].focus();
                checkComplete();
            }
        });
    });

    function checkComplete() {
        let val = '';
        inputs.forEach(inp => val += inp.value);
        
        // KUNCI UTAMA: Masukkan nilai gabungan ke input hidden
        hiddenInput.value = val;

        // Hanya aktifkan tombol jika panjangnya pas 6
        if (val.length === 6) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
});
</script>
@endsection