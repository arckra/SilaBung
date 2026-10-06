@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')

<div class="mb-8">
    <h1 class="text-[26px] sm:text-[28px] font-extrabold tracking-tight leading-tight">
        Selamat datang kembali 👋
    </h1>
    <p class="text-[#647164] text-sm mt-2">
        Masuk untuk lanjut menjelajah barang bekas di sekitarmu.
    </p>
</div>

{{-- Session status --}}
@if (session('status'))
    <div class="mb-5 px-4 py-3 rounded-xl bg-[#DCFCE7] border border-green-200 text-[#166534] text-sm font-semibold">
        {{ session('status') }}
    </div>
@endif

{{-- Global error --}}
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

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

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
            <input id="email" name="email" type="email" required autofocus
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
        <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="text-xs font-bold">Password</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#166534] hover:underline">
                    Lupa password?
                </a>
            @endif
        </div>
        <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#647164] pointer-events-none">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </span>
            <input id="password" name="password" type="password" required
                   placeholder="••••••••"
                   class="w-full h-12 pl-11 pr-11 rounded-xl border bg-white text-sm transition
                          {{ $errors->has('password') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/15' : 'border-[#E3EAE3] focus:border-[#16A34A] focus:ring-[#16A34A]/15' }}
                          focus:ring-2 outline-none">
            <button type="button" onclick="togglePw('password', this)"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg grid place-items-center text-[#647164] hover:bg-[#F0FDF4] hover:text-[#166534] transition"
                    aria-label="Tampilkan password">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </button>
        </div>
        @error('password')
            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    {{-- Remember --}}
    <label class="flex items-center gap-2.5 cursor-pointer select-none pt-1">
        <input type="checkbox" name="remember"
               class="w-4 h-4 rounded border-[#E3EAE3] text-[#16A34A] focus:ring-[#16A34A]/30">
        <span class="text-sm text-[#647164]">Ingat saya di perangkat ini</span>
    </label>

    {{-- Submit --}}
    <button type="submit"
            class="w-full h-12 rounded-xl bg-[#16A34A] text-white font-bold text-sm shadow-lg shadow-green-600/20 hover:bg-[#166534] active:scale-[.99] transition flex items-center justify-center gap-2">
        Masuk
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
    </button>
</form>

{{-- Divider --}}
<div class="flex items-center gap-3 my-6">
    <div class="flex-1 h-px bg-[#E3EAE3]"></div>
    <span class="text-[11px] font-bold tracking-wider text-[#647164] uppercase">atau</span>
    <div class="flex-1 h-px bg-[#E3EAE3]"></div>
</div>

<p class="text-center text-sm text-[#647164]">
    Belum punya akun?
    <a href="{{ route('register') }}" class="font-bold text-[#166534] hover:underline ml-1">
        Daftar gratis
    </a>
</p>

{{-- Demo box --}}
<div class="mt-7 p-4 rounded-xl bg-[#F0FDF4] border border-dashed border-green-300">
    <div class="text-[10px] font-extrabold tracking-widest text-[#166534] uppercase mb-2.5">
        Akun Demo
    </div>
    <div class="space-y-2 text-xs">
        <div class="flex items-center justify-between gap-3">
            <span class="text-[#647164]">Supplier</span>
            <code class="bg-white px-2 py-1 rounded-md border border-[#E3EAE3] text-[11px] font-mono cursor-pointer hover:border-[#16A34A] transition"
                  onclick="fillDemo('budi@silabung.test','password', this)">
                budi@silabung.test / password
            </code>
        </div>
        <div class="flex items-center justify-between gap-3">
            <span class="text-[#647164]">Customer</span>
            <code class="bg-white px-2 py-1 rounded-md border border-[#E3EAE3] text-[11px] font-mono cursor-pointer hover:border-[#16A34A] transition"
                  onclick="fillDemo('ari@silabung.test','password', this)">
                ari@silabung.test / password
            </code>
        </div>
    </div>
    <p class="text-[10px] text-[#647164] mt-2.5 italic">Klik salah satu untuk mengisi otomatis.</p>
</div>

<script>
    function togglePw(id, btn) {
        const inp = document.getElementById(id);
        inp.type = inp.type === 'password' ? 'text' : 'password';
        btn.style.color = inp.type === 'text' ? '#166534' : '';
    }
    function fillDemo(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>

@endsection