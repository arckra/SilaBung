@props([
    'user' => null,
    'name' => null,
    'url'  => null,
    'size' => 40,        // px
    'rounded' => 'full', // full | 2xl | lg | md
    'class' => '',
])

@php
    $name    = $name ?? ($user->name ?? '?');
    $url     = $url ?? ($user->avatar_url ?? null);
    $initials = strtoupper(
        collect(explode(' ', trim($name)))
            ->filter()
            ->take(2)
            ->map(fn ($w) => mb_substr($w, 0, 1))
            ->implode('')
    );

    $radiusMap = [
        'full' => 'rounded-full',
        '2xl'  => 'rounded-2xl',
        'lg'   => 'rounded-lg',
        'md'   => 'rounded-md',
    ];
    $radius = $radiusMap[$rounded] ?? $radiusMap['full'];

    $fontSize = match (true) {
        $size >= 56 => 'text-lg',
        $size >= 40 => 'text-sm',
        $size >= 32 => 'text-xs',
        default     => 'text-[10px]',
    };
@endphp

@if ($url)
    <div style="width: {{ $size }}px; height: {{ $size }}px;" class="relative shrink-0 {{ $class }}">
        <img src="{{ $url }}"
             alt="{{ $name }}"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';"
             style="width: {{ $size }}px; height: {{ $size }}px; object-fit: cover; object-position: center;"
             class="{{ $radius }} aspect-square w-full h-full border border-[#E3EAE3]">
             
        {{-- Fallback jika gambar rusak --}}
        <div style="display: none; width: {{ $size }}px; height: {{ $size }}px;"
             class="absolute inset-0 {{ $radius }} bg-[#166534] text-white place-items-center font-extrabold {{ $fontSize }}">
            {{ $initials }}
        </div>
    </div>
@else
    <div style="width: {{ $size }}px; height: {{ $size }}px;"
         class="{{ $radius }} bg-[#166534] text-white grid place-items-center font-extrabold shrink-0 {{ $fontSize }} {{ $class }}">
        {{ $initials }}
    </div>
@endif