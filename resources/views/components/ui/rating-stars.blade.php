@props([
    'rating' => 0,
    'reviewsCount' => null,
    'showCount' => true,
    'size' => 'sm',
])

@php
$numericRating = round((float) $rating, 1);
$iconSize = match($size) {
    'xs' => 'w-3 h-3',
    'md' => 'w-5 h-5',
    'lg' => 'w-6 h-6',
    default => 'w-4 h-4',
};
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5']) }}>
    <div class="flex items-center text-amber-400">
        @for ($i = 1; $i <= 5; $i++)
            @if ($numericRating >= $i)
                {{-- Full Star --}}
                <svg class="{{ $iconSize }} fill-current" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @elseif ($numericRating >= $i - 0.5)
                {{-- Half Star --}}
                <svg class="{{ $iconSize }}" viewBox="0 0 20 20" aria-hidden="true">
                    <defs>
                        <linearGradient id="halfStar-{{ $i }}-{{ (int)($numericRating * 10) }}">
                            <stop offset="50%" stop-color="#FBBF24" />
                            <stop offset="50%" stop-color="#E2E8F0" />
                        </linearGradient>
                    </defs>
                    <path fill="url(#halfStar-{{ $i }}-{{ (int)($numericRating * 10) }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @else
                {{-- Empty Star --}}
                <svg class="{{ $iconSize }} text-slate-200 fill-current" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endif
        @endfor
    </div>

    @if ($numericRating > 0)
        <span class="text-xs font-bold text-slate-800">{{ number_format($numericRating, 1) }}</span>
    @else
        <span class="text-xs font-medium text-slate-400">Baru</span>
    @endif

    @if ($showCount && $reviewsCount !== null)
        <span class="text-xs text-slate-400">({{ $reviewsCount }})</span>
    @endif
</div>
