@props([
    'report',
])

@php
    $fairness = $report->fairness_level ?? 'fair';

    $statusConfig = match ($fairness) {
        'cheap' => [
            'label' => 'Harga Murah / Hemat',
            'badgeClass' => 'bg-fairness-fair-bg text-fairness-fair border-fairness-fair/30',
            'dotClass' => 'bg-fairness-fair',
            'priceClass' => 'text-fairness-fair',
            'icon' => 'check',
        ],
        'overpriced' => [
            'label' => 'Waspada Getok',
            'badgeClass' => 'bg-fairness-getok-bg text-fairness-getok border-fairness-getok/30',
            'dotClass' => 'bg-fairness-getok',
            'priceClass' => 'text-fairness-getok',
            'icon' => 'alert',
        ],
        default => [
            'label' => 'Harga Normal / Wajar',
            'badgeClass' => 'bg-fairness-fair-bg text-fairness-fair border-fairness-fair/30',
            'dotClass' => 'bg-fairness-fair',
            'priceClass' => 'text-sky-700',
            'icon' => 'check',
        ],
    };

    $reporterName = $report->user->name ?? 'Traveler Hunter';
    $timeAgo = $report->created_at ? $report->created_at->diffForHumans() : 'Baru saja';
    $formattedPrice = 'Rp ' . number_format($report->price_paid, 0, ',', '.');
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 transition-all duration-200 hover:shadow-md hover:border-slate-300 relative group']) }}>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <!-- Item Info -->
        <div class="space-y-1.5 flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2">
                <h4 class="text-base font-bold text-slate-900 group-hover:text-sky-600 transition truncate">
                    {{ $report->item_name }}
                </h4>

                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusConfig['badgeClass'] }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dotClass'] }}"></span>
                    {{ $statusConfig['label'] }}
                </span>
            </div>

            <!-- Reporter and Date Meta -->
            <div class="flex items-center gap-3 text-xs text-slate-500">
                <span class="flex items-center gap-1.5 font-medium text-slate-700">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ $reporterName }}</span>
                </span>
                <span>•</span>
                <span class="flex items-center gap-1 text-slate-500">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $timeAgo }}</span>
                </span>
            </div>
        </div>

        <!-- Price Display -->
        <div class="flex items-baseline sm:flex-col sm:items-end justify-between border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100">
            <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block sm:mb-0.5">Biaya Dibayar</span>
            <span class="text-lg sm:text-xl font-black {{ $statusConfig['priceClass'] }}">
                {{ $formattedPrice }}
            </span>
        </div>
    </div>

    <!-- Notes & Proof Image (if any) -->
    @if ($report->notes || $report->proof_image)
        <div class="mt-3 pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
            @if ($report->notes)
                <p class="text-slate-600 italic bg-slate-50 rounded-xl px-3 py-1.5 flex-1">
                    "{{ $report->notes }}"
                </p>
            @endif

            @if ($report->proof_image)
                <a
                    href="{{ str_starts_with($report->proof_image, 'http') ? $report->proof_image : asset('storage/' . $report->proof_image) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-sky-50 text-sky-700 hover:bg-sky-100 font-semibold transition flex-shrink-0"
                >
                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Bukti Nota / Struk</span>
                </a>
            @endif
        </div>
    @endif
</div>
