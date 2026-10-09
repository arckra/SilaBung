@extends('layouts.app')

@section('title', 'Buat Wishlist')
@section('page-title', 'Buat Wishlist Kebutuhan')
@section('page-subtitle', 'Daftar barang yang kamu butuhkan, sistem akan mencarikan supplier')

@section('content')

<form method="POST" action="{{ route('customer.wishlist.store') }}" class="max-w-3xl">
    @csrf

    @if ($errors->any())
        <div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <div class="font-bold mb-1">Periksa kembali:</div>
            <ul class="list-disc list-inside space-y-0.5 text-[13px]">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- Info --}}
    <div class="mb-5 px-4 py-3.5 rounded-2xl bg-[#F0FDF4] border border-[#cfe6d5] text-sm text-[#166534] flex gap-2.5">
        <svg class="shrink-0 mt-0.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
        </svg>
        <div>
            Isi barang yang kamu butuhkan — bisa beberapa sekaligus. Setelah submit,
            sistem otomatis mencarikan supplier terdekat yang stoknya cukup.
        </div>
    </div>

    {{-- Judul --}}
    <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5 mb-4">
        <h2 class="font-extrabold tracking-tight mb-4">Info Wishlist</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold mb-1.5">Judul</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       placeholder="Contoh: Kebutuhan acara bakti sosial"
                       class="w-full h-11 px-3.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold mb-1.5">Catatan (opsional)</label>
                <textarea name="note" rows="2"
                          placeholder="Contoh: Dibutuhkan sebelum akhir bulan."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[#E3EAE3] focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none text-sm resize-y">{{ old('note') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Daftar barang --}}
    <div class="bg-white border border-[#E3EAE3] rounded-2xl p-5 mb-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-extrabold tracking-tight">Barang yang Dibutuhkan</h2>
            <button type="button" onclick="addLine()"
                    class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-[#DCFCE7] text-[#166534] text-xs font-bold hover:bg-[#bbf7d0] transition">
                <x-icon name="plus" :size="13" />
                Tambah Baris
            </button>
        </div>

        <div id="lines" class="space-y-3"></div>
    </div>

    {{-- Submit --}}
    <div class="flex items-center justify-end gap-2.5">
        <a href="{{ route('customer.wishlist.index') }}"
           class="h-11 px-5 rounded-xl border border-[#E3EAE3] bg-white text-sm font-bold hover:bg-[#F8FAF8] transition grid place-items-center">
            Batal
        </a>
        <button type="submit"
                class="h-11 px-6 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition flex items-center gap-2">
            <x-icon name="search" :size="15" />
            Cari Supplier Otomatis
        </button>
    </div>
</form>

{{-- Template baris --}}
<template id="lineTemplate">
    <div class="line-row grid sm:grid-cols-[1fr_1fr_100px_100px_40px] gap-2.5 p-3 rounded-xl border border-[#E3EAE3] bg-[#FBFDFB]">
        <div>
            <label class="block text-[10px] font-bold text-[#647164] mb-1 uppercase tracking-wider">Kategori</label>
            <select name="lines[__IDX__][category_id]" required
                    class="w-full h-10 px-3 rounded-lg border border-[#E3EAE3] focus:border-[#16A34A] outline-none text-sm bg-white">
                <option value="">Pilih</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->emoji }} {{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-[#647164] mb-1 uppercase tracking-wider">Kata kunci (opsional)</label>
            <input type="text" name="lines[__IDX__][search_term]" placeholder="misal: ukuran besar"
                   class="w-full h-10 px-3 rounded-lg border border-[#E3EAE3] focus:border-[#16A34A] outline-none text-sm">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-[#647164] mb-1 uppercase tracking-wider">Jumlah</label>
            <input type="number" name="lines[__IDX__][quantity]" min="1" value="1" required
                   class="w-full h-10 px-3 rounded-lg border border-[#E3EAE3] focus:border-[#16A34A] outline-none text-sm">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-[#647164] mb-1 uppercase tracking-wider">Satuan</label>
            <select name="lines[__IDX__][unit]"
                    class="w-full h-10 px-3 rounded-lg border border-[#E3EAE3] focus:border-[#16A34A] outline-none text-sm bg-white">
                @foreach (['pcs','kg','liter','ikat','set','karung'] as $u)
                    <option value="{{ $u }}">{{ $u }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="button" onclick="this.closest('.line-row').remove()"
                    class="w-10 h-10 rounded-lg grid place-items-center text-red-600 hover:bg-red-50 transition">
                <x-icon name="trash" :size="15" />
            </button>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script>
    let lineIndex = 0;

    function addLine() {
        const tmpl = document.getElementById('lineTemplate').innerHTML.replace(/__IDX__/g, lineIndex++);
        const wrap = document.createElement('div');
        wrap.innerHTML = tmpl;
        document.getElementById('lines').appendChild(wrap.firstElementChild);
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Minimal 1 baris
        addLine();
    });
</script>
@endpush