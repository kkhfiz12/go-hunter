@props([
    'destination' => null,
    'percentage' => null,
    'totalReports' => null,
])

@php
    $finalPercentage = $percentage ?? ($destination ? $destination->fairness_percentage : 0);
    $total = $totalReports ?? ($destination ? ($destination->relationLoaded('priceReports') ? $destination->priceReports->count() : $destination->priceReports()->count()) : 0);
    
    $breakdown = $destination && method_exists($destination, 'priceReportsBreakdown') 
        ? $destination->priceReportsBreakdown() 
        : [
            'cheap' => 0,
            'fair' => 0,
            'overpriced' => 0,
            'total' => $total,
        ];

    if ($total === 0) {
        $statusKey = 'empty';
        $statusLabel = 'Belum Ada Laporan';
        $barColor = 'bg-slate-300';
        $textColor = 'text-slate-600';
        $badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
        $iconClass = 'bg-slate-100 text-slate-500';
        $headline = 'Belum ada data laporan harga riil';
        $description = 'Jadilah pengunjung pertama yang melaporkan biaya jajan, parkir, atau sewa di sini untuk melindungi sesama traveler!';
    } elseif ($finalPercentage >= 80) {
        $statusKey = 'fair';
        $statusLabel = 'Aman & Wajar';
        $barColor = 'bg-fairness-fair';
        $textColor = 'text-fairness-fair';
        $badgeClass = 'bg-fairness-fair-bg text-fairness-fair border-fairness-fair/30';
        $iconClass = 'bg-fairness-fair-bg text-fairness-fair';
        $headline = "{$finalPercentage}% Pengunjung Melaporkan Harga Wajar";
        $description = 'Mayoritas pengunjung menikmati tarif normal & terjangkau tanpa khawatir praktik pemerasan harga.';
    } elseif ($finalPercentage >= 50) {
        $statusKey = 'warning';
        $statusLabel = 'Cukup Wajar / Perlu Pantau';
        $barColor = 'bg-fairness-warning';
        $textColor = 'text-fairness-warning';
        $badgeClass = 'bg-fairness-warning-bg text-fairness-warning border-fairness-warning/30';
        $iconClass = 'bg-fairness-warning-bg text-fairness-warning';
        $headline = "{$finalPercentage}% Pengunjung Melaporkan Harga Wajar";
        $description = 'Sebagian pedagang/jasa memasang harga sedikit di atas rata-rata. Tanyakan harga sebelum bertransaksi.';
    } else {
        $statusKey = 'getok';
        $statusLabel = 'Waspada Getok Harga!';
        $barColor = 'bg-fairness-getok';
        $textColor = 'text-fairness-getok';
        $badgeClass = 'bg-fairness-getok-bg text-fairness-getok border-fairness-getok/30';
        $iconClass = 'bg-fairness-getok-bg text-fairness-getok';
        $headline = "{$finalPercentage}% Pengunjung Melaporkan Harga Wajar";
        $description = 'Tingkat getok harga tinggi dilaporkan di lokasi ini. Selalu sepakati tarif di muka atau minta nota resmi.';
    }
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-7 shadow-sm transition hover:shadow-md relative overflow-hidden']) }}>
    <!-- Decorative background glow based on status -->
    @if ($statusKey === 'fair')
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-fairness-fair/10 rounded-full blur-2xl pointer-events-none"></div>
    @elseif ($statusKey === 'warning')
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-fairness-warning/10 rounded-full blur-2xl pointer-events-none"></div>
    @elseif ($statusKey === 'getok')
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-fairness-getok/10 rounded-full blur-2xl pointer-events-none"></div>
    @endif

    <div class="relative z-10">
        <!-- Header widget -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $iconClass }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-sky-600">Anti-Getok Meter</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900 mt-0.5">Barometer Transparansi Harga</h2>
                </div>
            </div>

            <button
                type="button"
                @click="$dispatch('open-report-price-modal')"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs sm:text-sm font-semibold shadow-sm transition hover:shadow-md hover:-translate-y-0.5 active:translate-y-0"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Lapor Harga Riil</span>
            </button>
        </div>

        <!-- Metric & Main Headline -->
        <div class="mt-6">
            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2 mb-3">
                <h3 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900">
                    {{ $headline }}
                </h3>
                <span class="text-xs font-semibold text-slate-500">
                    {{ $total }} Laporan Masuk
                </span>
            </div>

            <!-- Progress Bar Meter -->
            <div class="w-full bg-slate-100 rounded-full h-4 p-0.5 overflow-hidden ring-1 ring-inset ring-slate-200/80">
                <div
                    class="h-full rounded-full transition-all duration-700 ease-out {{ $barColor }}"
                    style="width: {{ $total === 0 ? 0 : max(5, $finalPercentage) }}%"
                ></div>
            </div>

            <p class="text-xs sm:text-sm text-slate-600 mt-2.5 leading-relaxed">
                {{ $description }}
            </p>
        </div>

        <!-- Breakdown Stats Cards -->
        @if ($total > 0)
            <div class="grid grid-cols-3 gap-3 mt-6 pt-5 border-t border-slate-100">
                <div class="bg-fairness-fair-bg/60 rounded-2xl p-3 border border-fairness-fair/20 text-center">
                    <span class="block text-[11px] font-semibold text-emerald-800 uppercase tracking-wider">Murah / Hemat</span>
                    <span class="text-lg sm:text-xl font-black text-fairness-fair mt-0.5 block">
                        {{ $breakdown['cheap'] }}
                    </span>
                    <span class="text-[10px] text-emerald-700 font-medium">
                        {{ (int) round(($breakdown['cheap'] / $total) * 100) }}% total
                    </span>
                </div>

                <div class="bg-emerald-50/50 rounded-2xl p-3 border border-emerald-200/50 text-center">
                    <span class="block text-[11px] font-semibold text-emerald-800 uppercase tracking-wider">Harga Wajar</span>
                    <span class="text-lg sm:text-xl font-black text-fairness-fair mt-0.5 block">
                        {{ $breakdown['fair'] }}
                    </span>
                    <span class="text-[10px] text-emerald-700 font-medium">
                        {{ (int) round(($breakdown['fair'] / $total) * 100) }}% total
                    </span>
                </div>

                <div class="bg-fairness-getok-bg/60 rounded-2xl p-3 border border-fairness-getok/20 text-center">
                    <span class="block text-[11px] font-semibold text-red-800 uppercase tracking-wider">Getok / Mahal</span>
                    <span class="text-lg sm:text-xl font-black text-fairness-getok mt-0.5 block">
                        {{ $breakdown['overpriced'] }}
                    </span>
                    <span class="text-[10px] text-red-700 font-medium">
                        {{ (int) round(($breakdown['overpriced'] / $total) * 100) }}% total
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>