@props([
    'type' => 'info',
    'dot' => false,
])

@php
$classes = match($type) {
    'fair', 'success', 'open', 'cheap' => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-emerald-600/20',
    'warning', 'reasonable', 'tips' => 'bg-amber-50 text-amber-700 border-amber-200 ring-amber-600/20',
    'overpriced', 'getok', 'danger', 'cancelled' => 'bg-red-50 text-red-700 border-red-200 ring-red-600/20',
    'primary', 'brand' => 'bg-sky-50 text-sky-700 border-sky-200 ring-sky-600/20',
    'purple', 'full' => 'bg-purple-50 text-purple-700 border-purple-200 ring-purple-600/20',
    default => 'bg-slate-50 text-slate-700 border-slate-200 ring-slate-600/20',
};

$dotClasses = match($type) {
    'fair', 'success', 'open', 'cheap' => 'fill-emerald-500',
    'warning', 'reasonable', 'tips' => 'fill-amber-500',
    'overpriced', 'getok', 'danger', 'cancelled' => 'fill-red-500',
    'primary', 'brand' => 'fill-sky-500',
    'purple', 'full' => 'fill-purple-500',
    default => 'fill-slate-400',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-x-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset border $classes"]) }}>
    @if($dot)
        <svg class="h-1.5 w-1.5 {{ $dotClasses }}" viewBox="0 0 6 6" aria-hidden="true">
            <circle cx="3" cy="3" r="3" />
        </svg>
    @endif
    {{ $slot }}
</span>
