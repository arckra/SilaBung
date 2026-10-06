@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')

<div class="mb-8">
    <h1 class="text-[26px] sm:text-[28px] font-extrabold tracking-tight leading-tight">
        Lupa password?
    </h1>
    <p class="text-[#647164] text-sm mt-2">
        Masukkan emailmu, kami akan mengirim tautan untuk mengatur ulang password.
    </p>
</div>

@if (session('status'))
    <div class="mb-5 px-4 py-3 rounded-xl bg-[#DCFCE7] border border-green-200 text-[#166534] text-sm font-semibold">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
    @csrf
    <div>
        <label for="email" class="block text-xs font-bold mb-1.5">Email</label>
        <input id="email" name="email" type="email" required autofocus
               value="{{ old('email') }}"
               placeholder="nama@email.com"
               class="w-full h-12 px-3.5 rounded-xl border border-[#E3EAE3] bg-white text-sm
                      focus:border-[#16A34A] focus:ring-2 focus:ring-[#16A34A]/15 outline-none transition">
        @error('email')
            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
            class="w-full h-12 rounded-xl bg-[#16A34A] text-white font-bold text-sm shadow-lg shadow-green-600/20 hover:bg-[#166534] transition">
        Kirim Tautan Reset
    </button>
</form>

<p class="text-center text-sm text-[#647164] mt-6">
    Ingat passwordnya?
    <a href="{{ route('login') }}" class="font-bold text-[#166534] hover:underline ml-1">
        Kembali ke login
    </a>
</p>

@endsection