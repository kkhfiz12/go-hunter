@props([
    'destination',
])

@php
$avgRating = $destination->reviews_avg_rating ?? ($destination->relationLoaded('reviews') ? $destination->reviews->avg('rating') : 0);
$reviewsCount = $destination->reviews_count ?? ($destination->relationLoaded('reviews') ? $destination->reviews->count() : 0);
$coverImage = $destination->cover_image ? (str_starts_with($destination->cover_image, 'http') ? $destination->cover_image : asset($destination->cover_image)) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80';
$categoryType = match(strtolower($destination->category ?? 'alam')) {
    'bahari', 'pantai' => 'primary',
    'alam', 'gunung' => 'fair',
    'budaya', 'sejarah' => 'warning',
    'kuliner' => 'tips',
    default => 'info',
};
$detailUrl = route('destinations.show', $destination);
@endphp

<article {{ $attributes->merge(['class' => 'group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden']) }}>
    <!-- Photo Wrapper with 16:9 / 4:3 Ratio & Zoom Effect -->
    <div class="relative aspect-[16/10] w-full overflow-hidden bg-slate-100">
        <img
            src="{{ $coverImage }}"
            alt="{{ $destination->name }}"
            loading="lazy"
            class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
            onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80';"
        />

        <!-- Gradient Overlay for Contrast -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-slate-950/10 to-transparent opacity-80 group-hover:opacity-60 transition-opacity"></div>

        <!-- Badges on top of photo -->
        <div class="absolute top-3 inset-x-3 flex items-center justify-between pointer-events-none">
            <!-- Category Badge -->
            <x-ui.badge :type="$categoryType" class="backdrop-blur-md bg-white/90 shadow-sm font-bold uppercase tracking-wider text-[11px]">
                {{ $destination->category ?? 'Alam' }}
            </x-ui.badge>

            <!-- Verified Ticket Status Badge -->
            <span class="inline-flex items-center gap-1 bg-emerald-500/90 text-white backdrop-blur-md px-2.5 py-1 rounded-full text-[11px] font-semibold shadow-sm">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>Tiket Resmi</span>
            </span>
        </div>

        <!-- Location overlay on bottom edge of image -->
        <div class="absolute bottom-3 left-3 right-3 text-white">
            <p class="flex items-center gap-1 text-xs font-medium text-slate-100 drop-shadow-sm line-clamp-1">
                <svg class="w-3.5 h-3.5 flex-shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ $destination->city }}, {{ $destination->province }}</span>
            </p>
        </div>
    </div>

    <!-- Meta Info Body -->
    <div class="p-5 flex-1 flex flex-col justify-between">
        <div>
            <!-- Destination Name -->
            <h3 class="text-lg font-bold text-slate-900 group-hover:text-sky-600 transition-colors line-clamp-1">
                <a href="{{ $detailUrl }}" class="focus:outline-none focus:underline">
                    {{ $destination->name }}
                </a>
            </h3>

            <!-- Price Breakdown Grid: Official Ticket & Parking Fees -->
            <div class="mt-4 grid grid-cols-2 gap-2.5 bg-slate-50 p-3 rounded-xl border border-slate-100">
                <!-- Official Ticket Price -->
                <div class="col-span-2 sm:col-span-1">
                    <span class="block text-[11px] font-medium uppercase tracking-wider text-slate-500">Tiket Masuk Resmi</span>
                    <p class="text-sm sm:text-base font-extrabold text-sky-700 mt-0.5">
                        @if ((float) $destination->official_ticket_price > 0)
                            Rp {{ number_format($destination->official_ticket_price, 0, ',', '.') }}
                        @else
                            <span class="text-emerald-600">Gratis</span>
                        @endif
                    </p>
                </div>

                <!-- Parking Fees (Motor & Mobil) -->
                <div class="col-span-2 sm:col-span-1 flex flex-col justify-center">
                    <span class="block text-[11px] font-medium uppercase tracking-wider text-slate-500">Tarif Parkir Resmi</span>
                    <div class="flex items-center gap-2 mt-1 text-xs text-slate-700 font-semibold">
                        <!-- Motor -->
                        <span class="inline-flex items-center gap-1" title="Parkir Motor">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="5" cy="17" r="3" stroke-width="2"/>
                                <circle cx="19" cy="17" r="3" stroke-width="2"/>
                                <path stroke-width="2" stroke-linecap="round" d="M5 17h5l3-7h4m-7 0h4m-3 0l-2-4H7"/>
                            </svg>
                            <span>{{ (float) $destination->parking_fee_motor > 0 ? 'Rp ' . number_format($destination->parking_fee_motor, 0, ',', '.') : 'Gratis' }}</span>
                        </span>

                        <span class="text-slate-300">•</span>

                        <!-- Mobil -->
                        <span class="inline-flex items-center gap-1" title="Parkir Mobil">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11M3 13h18v4a1 1 0 01-1 1h-1a2 2 0 01-2-2H7a2 2 0 01-2 2H4a1 1 0 01-1-1v-4z" />
                            </svg>
                            <span>{{ (float) $destination->parking_fee_car > 0 ? 'Rp ' . number_format($destination->parking_fee_car, 0, ',', '.') : 'Gratis' }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer: Rating & CTA Button -->
        <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between gap-2">
            <!-- Rating Component -->
            <x-ui.rating-stars :rating="$avgRating" :reviewsCount="$reviewsCount" />

            <!-- Detail Arrow CTA -->
            <a
                href="{{ $detailUrl }}"
                class="inline-flex items-center gap-1 text-xs font-bold text-sky-600 group-hover:text-sky-700 group-hover:translate-x-0.5 transition-all"
                aria-label="Lihat detail {{ $destination->name }}"
            >
                <span>Lihat Detail</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</article>
