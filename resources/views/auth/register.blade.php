@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')

<div class="mb-8">
    <h1 class="text-[26px] sm:text-[28px] font-extrabold tracking-tight leading-tight">
        Mulai sambungkan manfaatnya
    </h1>
    <p class="text-[#647164] text-sm mt-2">
        Buat akun gratis. Cuma butuh beberapa detik.
    </p>
</div>

@if ($errors->any())
    <div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
        <div class="flex gap-2 items-start">
            <svg class="shrink-0 mt-0.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
            </svg>
            <div>{{ $errors->first() }}</div>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    {{-- Nama --}}
    <div>
        <label for="name" class="block text-xs font-bold mb-1.5">Nama lengkap</label>
        <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#647164] pointer-events-none">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </span>
            <input id="name" name="name" type="text" required autofocus
                   value="{{ old('name') }}"
                   placeholder="Nama kamu"
                   class="w-full h-12 pl-11 pr-3.5 rounded-xl border bg-white text-sm transition
                          {{ $errors->has('name') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/15' : 'border-[#E3EAE3] focus:border-[#16A34A] focus:ring-[#16A34A]/15' }}
                          focus:ring-2 outline-none">
        </div>
        @error('name')
            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-xs font-bold mb-1.5">Email</label>
        <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#647164] pointer-events-none">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                    <path d="M22 6l-10 7L2 6"/>
                </svg>
            </span>
            <input id="email" name="email" type="email" required
                   value="{{ old('email') }}"
                   placeholder="nama@email.com"
                   class="w-full h-12 pl-11 pr-3.5 rounded-xl border bg-white text-sm transition
                          {{ $errors->has('email') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/15' : 'border-[#E3EAE3] focus:border-[#16A34A] focus:ring-[#16A34A]/15' }}
                          focus:ring-2 outline-none">
        </div>
        @error('email')
            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password --}}
    <div>
        <label for="password" class="block text-xs font-bold mb-1.5">Password</label>
        <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#647164] pointer-events-none">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </span>
            <input id="password" name="password" type="password" required
                   placeholder="Minimal 8 karakter"
                   class="w-full h-12 pl-11 pr-11 rounded-xl border bg-white text-sm transition
                          {{ $errors->has('password') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/15' : 'border-[#E3EAE3] focus:border-[#16A34A] focus:ring-[#16A34A]/15' }}
                          focus:ring-2 outline-none">
            <button type="button" onclick="togglePw('password', this)"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg grid place-items-center text-[#647164] hover:bg-[#F0FDF4] hover:text-[#166534] transition">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </button>
        </div>
        @error('password')
            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
        @enderror

        {{-- Strength meter (visual only) --}}
        <div class="mt-2 flex gap-1" id="pwStrength">
            <span class="h-1 flex-1 rounded-full bg-[#E3EAE3]"></span>
            <span class="h-1 flex-1 rounded-full bg-[#E3EAE3]"></span>
            <span class="h-1 flex-1 rounded-full bg-[#E3EAE3]"></span>
            <span class="h-1 flex-1 rounded-full bg-[#E3EAE3]"></span>
        </div>
    </div>

    {{-- Confirm --}}
    <div>
        <label for="password_confirmation" class="block text-xs font-bold mb-1.5">Ulangi password</label>
        <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#647164] pointer-events-none">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </span>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   placeholder="Ketik ulang password"
                   class="w-full h-12 pl-11 pr-3.5 rounded-xl border border-[#E3EAE3] bg-white text-sm
                          focus:border-[#16A34A] focus:ring-2 focus:ring-[#16A34A]/15 outline-none transition">
        </div>
    </div>

    {{-- Terms --}}
    <label class="flex items-start gap-2.5 cursor-pointer select-none pt-1">
        <input type="checkbox" required
               class="w-4 h-4 mt-0.5 rounded border-[#E3EAE3] text-[#16A34A] focus:ring-[#16A34A]/30">
        <span class="text-[13px] text-[#647164] leading-relaxed">
            Saya setuju untuk menggunakan SILABUNG secara bertanggung jawab
            dan hanya membagikan barang yang layak digunakan.
        </span>
    </label>

    {{-- Submit --}}
    <button type="submit"
            class="w-full h-12 rounded-xl bg-[#16A34A] text-white font-bold text-sm shadow-lg shadow-green-600/20 hover:bg-[#166534] active:scale-[.99] transition flex items-center justify-center gap-2">
        Buat Akun
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
    </button>

    <p class="text-[11px] text-center text-[#647164]">
        Setelah mendaftar, kamu akan memilih peran <b class="text-[#166534]">Customer</b> atau
        <b class="text-[#166534]">Supplier</b> — dan peran itu tidak bisa diubah.
    </p>
</form>

{{-- Divider --}}
<div class="flex items-center gap-3 my-6">
    <div class="flex-1 h-px bg-[#E3EAE3]"></div>
    <span class="text-[11px] font-bold tracking-wider text-[#647164] uppercase">atau</span>
    <div class="flex-1 h-px bg-[#E3EAE3]"></div>
</div>

<p class="text-center text-sm text-[#647164]">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="font-bold text-[#166534] hover:underline ml-1">
        Masuk di sini
    </a>
</p>

<script>
    function togglePw(id, btn) {
        const inp = document.getElementById(id);
        inp.type = inp.type === 'password' ? 'text' : 'password';
        btn.style.color = inp.type === 'text' ? '#166534' : '';
    }

    // Password strength meter (visual only)
    const pwInput = document.getElementById('password');
    const bars = document.querySelectorAll('#pwStrength span');
    pwInput.addEventListener('input', () => {
        const v = pwInput.value;
        let score = 0;
        if (v.length >= 8) score++;
        if (/[A-Z]/.test(v)) score++;
        if (/[0-9]/.test(v)) score++;
        if (/[^A-Za-z0-9]/.test(v)) score++;

        bars.forEach((b, i) => {
            b.className = 'h-1 flex-1 rounded-full transition-colors';
            if (i < score) {
                b.classList.add(
                    score <= 1 ? 'bg-red-400'    :
                    score === 2 ? 'bg-amber-400' :
                    score === 3 ? 'bg-yellow-400':
                                  'bg-[#16A34A]'
                );
            } else {
                b.classList.add('bg-[#E3EAE3]');
            }
        });
    });
</script>

@endsection