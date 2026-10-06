@props(['icon' => 'package', 'title', 'description' => null, 'actionLabel' => null, 'actionHref' => null])

<div class="text-center py-14 px-6 flex flex-col items-center gap-3.5">
    <div class="w-16 h-16 rounded-2xl bg-[#DCFCE7] text-[#166534] grid place-items-center">
        <x-icon :name="$icon" :size="26" />
    </div>
    <h3 class="text-base font-extrabold tracking-tight">{{ $title }}</h3>
    @if ($description)
        <p class="text-sm text-[#647164] max-w-md leading-relaxed">{{ $description }}</p>
    @endif
    @if ($actionLabel && $actionHref)
        <a href="{{ $actionHref }}"
           class="mt-1.5 inline-flex items-center gap-2 h-10 px-5 rounded-xl bg-[#16A34A] text-white text-sm font-bold shadow-md shadow-green-600/20 hover:bg-[#166534] transition">
            <x-icon name="plus" :size="15" />
            {{ $actionLabel }}
        </a>
    @endif
</div>