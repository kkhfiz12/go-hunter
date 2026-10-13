<x-layouts.app :title="$buddyRequest->title . ' — Travel Buddy Go Hunter'" :description="$buddyRequest->description">
    @php
        $user = $buddyRequest->user;
        $destination = $buddyRequest->destination;
        $totalSlots = max($buddyRequest->slots_needed, $buddyRequest->slots_filled);
        $filledSlots = min($buddyRequest->slots_filled, $totalSlots);
        $remainingSlots = max(0, $totalSlots - $filledSlots);
        $isOpen = $buddyRequest->status === 'open' && $remainingSlots > 0;
        $formattedBudget = 'Rp ' . number_format($buddyRequest->estimated_budget_per_person, 0, ',', '.');
        $whatsappUrl = $buddyRequest->whatsapp_url;
    @endphp

    <div class="bg-slate-50 min-h-screen py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
                <a href="{{ url('/') }}" class="hover:text-sky-600 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('buddy-requests.index') }}" class="hover:text-sky-600 transition">Teman Jalan</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold truncate">{{ $buddyRequest->title }}</span>
            </nav>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Hero Card -->
                    <div class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-sm">
                        <!-- Destination Backdrop Image -->
                        <div class="relative h-56 sm:h-72 w-full bg-slate-900 overflow-hidden">
                            <img
                                src="{{ $destination->cover_image ? (str_starts_with($destination->cover_image, 'http') ? $destination->cover_image : asset($destination->cover_image)) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80' }}"
                                alt="{{ $destination->name }}"
                                class="w-full h-full object-cover opacity-80"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                            <div class="absolute bottom-5 left-5 right-5 text-white">
                                <div class="flex items-center gap-2 mb-2">
                                    @if ($isOpen)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-sm">
                                            <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                            Open Trip
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-500 text-white shadow-sm">
                                            Slot Penuh
                                        </span>
                                    @endif

                                    <a href="{{ route('destinations.show', $destination->slug) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-white/20 hover:bg-white/30 text-white backdrop-blur-md transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $destination->name }}
                                    </a>
                                </div>

                                <h1 class="text-xl sm:text-3xl font-extrabold tracking-tight">
                                    {{ $buddyRequest->title }}
                                </h1>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 sm:p-8 space-y-6">
                            <!-- Host Information -->
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-600 text-white font-bold flex items-center justify-center text-base shadow-sm">
                                        {{ strtoupper(substr($user->name ?? 'H', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900 text-sm sm:text-base">
                                                {{ $user->name ?? 'Host Hunter' }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                ★ {{ $user->reputation_points ?? 0 }} Reputasi
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            Inisiator trip • Diposting {{ $buddyRequest->created_at ? $buddyRequest->created_at->diffForHumans() : 'baru saja' }}
                                        </p>
                                    </div>
                                </div>

                                <a
                                    href="{{ $whatsappUrl }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition"
                                >
                                    <span>Hubungi Host</span>
                                </a>
                            </div>

                            <!-- Trip Description & Itinerary -->
                            <div>
                                <h3 class="text-base font-bold text-slate-900 mb-2">
                                    Rencana Perjalanan & Deskripsi
                                </h3>
                                <p class="text-sm sm:text-base text-slate-600 leading-relaxed whitespace-pre-line">
                                    {{ $buddyRequest->description }}
                                </p>
                            </div>

                            <!-- Boarding Pass / Ticket Slot Status Box -->
                            <div class="bg-gradient-to-br from-sky-50 via-white to-slate-50 p-5 sm:p-6 rounded-3xl border border-sky-100 space-y-4">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-sky-800">
                                    Ketersediaan Kursi / Slot
                                </h3>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div>
                                        <span class="text-2xl font-black text-slate-900">
                                            Sisa {{ $remainingSlots }} Kursi
                                        </span>
                                        <span class="text-xs text-slate-500 block">
                                            Total kapasitas: {{ $totalSlots }} kursi ({{ $filledSlots }} terisi)
                                        </span>
                                    </div>

                                    <!-- Visual Dots -->
                                    <div class="flex items-center gap-2">
                                        @for ($i = 1; $i <= min(10, $totalSlots); $i++)
                                            @if ($i <= $filledSlots)
                                                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold shadow-xs ring-2 ring-emerald-100" title="Kursi Terisi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 rounded-full border-2 border-dashed border-slate-300 bg-white text-slate-400 flex items-center justify-center text-xs font-bold" title="Kursi Kosong">
                                                    <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                                </div>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <!-- Safety & Disclaimer Tips -->
                            <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/60 text-xs text-amber-900 space-y-1">
                                <span class="font-bold flex items-center gap-1.5 text-amber-800">
                                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Panduan Bertransaksi Aman
                                </span>
                                <p class="leading-relaxed text-amber-800">
                                    Go Hunter adalah jembatan penghubung komunitas traveler. Selalu diskusikan rincian pengeluaran (BBM, sewa mobil, tiket) secara transparan dan buat grup WhatsApp bersama seluruh anggota tim jalan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sticky Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm sticky top-24 space-y-5">
                        <div class="border-b border-slate-100 pb-4">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">
                                Estimasi Biaya Patungan
                            </span>
                            <div class="text-2xl sm:text-3xl font-black text-sky-600 mt-1">
                                {{ $formattedBudget }}
                                <span class="text-xs font-normal text-slate-500">/ orang</span>
                            </div>
                        </div>

                        <!-- Date & Location Meta -->
                        <div class="space-y-3 text-xs sm:text-sm">
                            <div class="flex items-center justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-500">Destinasi:</span>
                                <a href="{{ route('destinations.show', $destination->slug) }}" class="font-bold text-sky-600 hover:underline">
                                    {{ $destination->name }}
                                </a>
                            </div>

                            <div class="flex items-center justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-500">Jadwal:</span>
                                <span class="font-bold text-slate-800">
                                    {{ $buddyRequest->formatted_date_range }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-500">Status Slot:</span>
                                <span class="font-bold {{ $isOpen ? 'text-emerald-600' : 'text-purple-600' }}">
                                    {{ $isOpen ? 'Tersedia (' . $remainingSlots . ' Kursi)' : 'Penuh' }}
                                </span>
                            </div>
                        </div>

                        <!-- Action CTA -->
                        <div class="space-y-2 pt-2">
                            <a
                                href="{{ $whatsappUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white text-sm font-bold shadow-md hover:shadow-lg transition"
                            >
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.152.569 4.17 1.564 5.917l-1.637 5.986 6.136-1.609c1.701.928 3.649 1.456 5.72 1.456 6.627 0 12-5.373 12-12s-5.373-12-12-12zm.001 22.083c-1.896 0-3.666-.543-5.161-1.482l-.37-.232-3.834 1.006 1.024-3.738-.255-.399c-1.077-1.688-1.648-3.659-1.648-5.698 0-5.753 4.678-10.431 10.43-10.431 5.753 0 10.432 4.678 10.432 10.431 0 5.753-4.679 10.431-10.432 10.431z"/>
                                </svg>
                                <span>Hubungi Host via WA</span>
                            </a>

                            <a
                                href="{{ route('buddy-requests.index') }}"
                                class="w-full inline-flex justify-center items-center px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
                            >
                                &larr; Kembali ke Feed Teman Jalan
                            </a>
                        </div>
                    </div>

                    <!-- Other Trips Recommendation -->
                    @if (isset($otherTrips) && $otherTrips->count() > 0)
                        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm space-y-4">
                            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">
                                Ajakan Trip Lainnya
                            </h3>

                            <div class="space-y-3">
                                @foreach ($otherTrips as $otherTrip)
                                    <a href="{{ route('buddy-requests.show', $otherTrip) }}" class="block p-3 rounded-2xl bg-slate-50 hover:bg-sky-50 border border-slate-100 transition group">
                                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-sky-600 line-clamp-1">
                                            {{ $otherTrip->title }}
                                        </h4>
                                        <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1">
                                            <span>{{ $otherTrip->destination->name ?? 'Destinasi' }}</span>
                                            <span class="font-bold text-sky-600">
                                                Rp {{ number_format($otherTrip->estimated_budget_per_person, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
