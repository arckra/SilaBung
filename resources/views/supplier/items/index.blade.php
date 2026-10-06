@extends('layouts.app')

@section('title', 'Barang Saya')
@section('page-title', 'Barang Saya')
@section('page-subtitle', 'Kelola stok barang yang kamu bagikan')

@section('page-actions')
    <a href="{{ route('supplier.items.create') }}"
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
        <x-icon name="plus" :size="15" />
        Tambah Barang
    </a>
@endsection

@section('content')

@if ($items->isEmpty())
    <div class="bg-white border border-[#E3EAE3] rounded-2xl">
        <x-empty-state
            icon="package"
            title="Belum ada barang yang kamu bagikan"
            description="Yuk bagikan barang yang sudah tidak kamu gunakan."
            actionLabel="Tambah Barang Pertamamu"
            :actionHref="route('supplier.items.create')" />
    </div>
@else
    <div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#FBFDFB] border-b border-[#E3EAE3]">
                    <tr>
                        <th class="text-left px-5 py-3 text-[10px] font-extrabold uppercase tracking-wider text-[#647164]">Barang</th>
                        <th class="text-left px-5 py-3 text-[10px] font-extrabold uppercase tracking-wider text-[#647164]">Kategori</th>
                        <th class="text-left px-5 py-3 text-[10px] font-extrabold uppercase tracking-wider text-[#647164]">Jumlah</th>
                        <th class="text-left px-5 py-3 text-[10px] font-extrabold uppercase tracking-wider text-[#647164]">Kondisi</th>
                        <th class="text-left px-5 py-3 text-[10px] font-extrabold uppercase tracking-wider text-[#647164]">Status</th>
                        <th class="text-right px-5 py-3 text-[10px] font-extrabold uppercase tracking-wider text-[#647164]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E3EAE3]">
                    @foreach ($items as $item)
                        <tr class="hover:bg-[#FBFDFB] transition">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#DCFCE7] grid place-items-center text-lg">
                                        {{ $item->category->emoji ?? '📦' }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold truncate max-w-[220px]">{{ $item->name }}</div>
                                        <div class="text-[11px] text-[#647164] truncate max-w-[220px]">
                                            {{ \Illuminate\Support\Str::limit($item->description, 40) ?: '—' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-[#647164]">{{ $item->category->name }}</td>
                            <td class="px-5 py-3.5 font-extrabold">{{ $item->quantity }} {{ $item->unit }}</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold
                                    {{ $item->condition === 'Baik' ? 'bg-[#DCFCE7] text-[#166534]' : ($item->condition === 'Cukup' ? 'bg-amber-100 text-amber-700' : 'bg-red-50 text-red-700') }}">
                                    {{ $item->condition }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold
                                    {{ $item->status === 'available' ? 'bg-[#DCFCE7] text-[#166534]' : 'bg-[#F1F5F1] text-[#647164]' }}">
                                    {{ $item->status === 'available' ? 'Tersedia' : 'Habis' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('supplier.items.edit', $item) }}"
                                       class="w-8 h-8 rounded-lg grid place-items-center text-[#647164] hover:bg-[#DCFCE7] hover:text-[#166534] transition"
                                       title="Edit">
                                        <x-icon name="edit" :size="14" />
                                    </a>
                                    <form method="POST" action="{{ route('supplier.items.destroy', $item) }}"
                                          onsubmit="return confirm('Yakin hapus barang ini?')">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 rounded-lg grid place-items-center text-[#647164] hover:bg-red-50 hover:text-red-600 transition" title="Hapus">
                                            <x-icon name="trash" :size="14" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="px-5 py-4 border-t border-[#E3EAE3]">{{ $items->links() }}</div>
        @endif
    </div>
@endif

@endsection