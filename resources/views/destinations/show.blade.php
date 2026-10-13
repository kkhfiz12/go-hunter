<x-layouts.app :title="$destination->name">
    <div class="bg-slate-50 min-h-screen py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
                <a href="{{ url('/') }}" class="hover:text-sky-600 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('destinations.index') }}" class="hover:text-sky-600 transition">Destinasi</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold truncate">{{ $destination->name }}</span>
            </nav>

            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-8">
                <!-- Cover Image & Hero -->
                <div class="relative h-64 sm:h-96 w-full bg-slate-900 overflow-hidden">
                    <img
                        src="{{ $destination->cover_image ? (str_starts_with($destination->cover_image, 'http') ? $destination->cover_image : asset($destination->cover_image)) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80' }}"
                        alt="{{ $destination->name }}"
                        class="w-full h-full object-cover opacity-80"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                    
                    <div class="absolute bottom-6 left-6 right-6 text-white">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <x-ui.badge type="primary" class="bg-white/90 text-sky-700 font-bold backdrop-blur-md">
                                {{ $destination->category ?? 'Alam' }}
                            </x-ui.badge>
                            <span class="inline-flex items-center gap-1 bg-emerald-500 text-white px-2.5 py-1 rounded-full text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Tiket Terverifikasi
                            </span>
                        </div>
                        <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                            {{ $destination->name }}
                        </h1>
                        <p class="text-sm sm:text-base text-slate-200 mt-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $destination->address }}, {{ $destination->city }}, {{ $destination->province }}</span>
                        </p>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 mb-2">Tentang Destinasi</h2>
                            <p class="text-slate-600 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                                {{ $destination->description }}
                            </p>
                        </div>

                        @if ($destination->nearest_transit_info)
                            <div class="p-4 rounded-2xl bg-sky-50 border border-sky-100 flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl bg-sky-600 text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-sky-900">Akses Transportasi Umum Terdekat</h4>
                                    <p class="text-sm text-sky-800 mt-0.5">{{ $destination->nearest_transit_info }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Anti-Getok / Crowdsourced Price Checker Section -->
                        <div class="pt-6 border-t border-slate-100 space-y-5">
                            <x-destination.price-fairness-meter :destination="$destination" />

                            <!-- List Riwayat Harga Riil -->
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900">Katalog Harga Riil Pengunjung</h3>
                                        <p class="text-xs text-slate-500">Daftar biaya makan, parkir, dan retribusi yang dilaporkan traveler.</p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="$dispatch('open-report-price-modal')"
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-600 hover:text-sky-700 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-xl transition"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Lapor Biaya</span>
                                    </button>
                                </div>

                                @if ($destination->priceReports->count() > 0)
                                    <div class="space-y-3">
                                        @foreach ($destination->priceReports as $report)
                                            <x-cards.price-report-card :report="$report" />
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-6 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center">
                                        <div class="w-10 h-10 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-700">Belum ada laporan harga riil</p>
                                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Bantu sesama traveler dengan melaporkan harga makanan, toilet, atau parkir yang kamu bayar.</p>
                                        <button
                                            type="button"
                                            @click="$dispatch('open-report-price-modal')"
                                            class="mt-3 inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-semibold transition"
                                        >
                                            + Laporkan Biaya Pertama
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Reviews Section -->
                        <div class="pt-6 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-bold text-slate-900">Ulasan Pengunjung</h2>
                                <x-ui.rating-stars :rating="$destination->reviews_avg_rating ?? 0" :reviewsCount="$destination->reviews_count ?? 0" />
                            </div>

                            @if ($destination->reviews->count() > 0)
                                <div class="space-y-4">
                                    @foreach ($destination->reviews as $review)
                                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-bold text-sm text-slate-800">{{ $review->user->name ?? 'Pengguna' }}</span>
                                                <x-ui.rating-stars :rating="$review->rating" :showCount="false" />
                                            </div>
                                            <p class="text-sm text-slate-600">{{ $review->review_text }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-400">Belum ada ulasan untuk destinasi ini.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Right Sticky Sidebar with Official Pricing -->
                    <div class="md:col-span-1">
                        <div class="bg-slate-50 rounded-2xl border border-slate-200/80 p-5 space-y-4 sticky top-24">
                            <h3 class="text-base font-bold text-slate-900 border-b border-slate-200 pb-3">Tarif & Biaya Resmi</h3>

                            <!-- Tiket Masuk -->
                            <div>
                                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Tiket Masuk Resmi</span>
                                <p class="text-2xl font-black text-sky-600 mt-1">
                                    @if ((float) $destination->official_ticket_price > 0)
                                        Rp {{ number_format($destination->official_ticket_price, 0, ',', '.') }}
                                    @else
                                        <span class="text-emerald-600">Gratis</span>
                                    @endif
                                </p>
                            </div>

                            <!-- Parkir Motor -->
                            <div class="flex items-center justify-between text-sm py-2 border-t border-slate-200">
                                <span class="text-slate-600">Parkir Motor:</span>
                                <span class="font-bold text-slate-800">
                                    {{ (float) $destination->parking_fee_motor > 0 ? 'Rp ' . number_format($destination->parking_fee_motor, 0, ',', '.') : 'Gratis' }}
                                </span>
                            </div>

                            <!-- Parkir Mobil -->
                            <div class="flex items-center justify-between text-sm py-2 border-t border-slate-200">
                                <span class="text-slate-600">Parkir Mobil:</span>
                                <span class="font-bold text-slate-800">
                                    {{ (float) $destination->parking_fee_car > 0 ? 'Rp ' . number_format($destination->parking_fee_car, 0, ',', '.') : 'Gratis' }}
                                </span>
                            </div>

                            <div class="pt-2">
                                <a
                                    href="{{ route('destinations.index') }}"
                                    class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-xl shadow-sm transition"
                                >
                                    &larr; Kembali ke Katalog
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Report Price Modal -->
    <x-destination.report-price-modal :destination="$destination" />
</x-layouts.app>
