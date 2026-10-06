@props(['label', 'value', 'icon' => 'package', 'tone' => 'green'])

@php
    $tones = [
        'green'  => 'bg-[#DCFCE7] text-[#166534]',
        'amber'  => 'bg-amber-100 text-amber-700',
        'blue'   => 'bg-sky-100 text-sky-700',
        'purple' => 'bg-violet-100 text-violet-700',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white border border-[#E3EAE3] rounded-2xl p-5']) }}>
    <div class="w-10 h-10 rounded-xl grid place-items-center mb-3.5 {{ $tones[$tone] ?? $tones['green'] }}">
        <x-icon :name="$icon" :size="18" />
    </div>
    <div class="text-2xl font-extrabold tracking-tight leading-tight">{{ $value }}</div>
    <div class="text-xs text-[#647164] font-medium mt-0.5">{{ $label }}</div>
</div>