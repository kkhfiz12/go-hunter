<x-layouts.app title="Go Hunter — Jelajah Tanpa Ragu, Info Nyata Tanpa Tipu" description="Platform super-app direktori panduan wisata independen, proteksi transparansi harga anti-getok, teman jalan hemat, dan forum Q&A real-time di Indonesia.">
    <!-- Hero Section (Berdasarkan DESAIN.md Bagian 4.2) -->
    <section class="relative min-h-[580px] lg:min-h-[640px] flex items-center justify-center bg-slate-950 overflow-hidden">
        <!-- Photographic Backdrop -->
        <img
            src="https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1920&q=80"
            alt="Pemandangan Alam Indonesia - Go Hunter"
            class="absolute inset-0 w-full h-full object-cover object-center opacity-45 scale-105 animate-pulse-slow"
        />
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-slate-950/30"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 text-center">
            <!-- Badge Sub-Heading -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs sm:text-sm font-semibold mb-6 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>Super-App Wisata Bebas Getok Harga</span>
            </div>

            <!-- Tagline Title -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight sm:leading-none">
                Jelajah Tanpa Ragu, <br />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-sky-300 to-amber-300">
                    Info Nyata Tanpa Tipu.
                </span>
            </h1>

            <p class="mt-4 sm:mt-6 text-sm sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Temukan panduan logistik independen, proteksi harga warung & parkir transparan, teman jalan patungan hemat, dan update situasi langsung dari warga lokal.
            </p>

            <!-- Floating Super-Search Card with Tab Selector (DESAIN.md 4.2) -->
            <div
                x-data="{
                    activeTab: 'destinasi',
                    tabs: [
                        { id: 'destinasi', label: 'Cari Destinasi', action: '{{ route('destinations.index') }}', placeholder: 'Ketik nama tempat, kota, atau provinsi (Bromo, Bandung, dsb)...' },
                        { id: 'buddy', label: 'Cari Travel Buddy', action: '{{ route('travel-buddy.index') }}', placeholder: 'Cari ajakan patungan jeep, kapal, atau road-trip...' },
                        { id: 'forum', label: 'Tanya Komunitas', action: '{{ route('forum.index') }}', placeholder: 'Tanyakan cuaca, kondisi jalur banjir/longsor, atau tips...' }
                    ]
                }"
                class="mt-8 sm:mt-10 max-w-3xl mx-auto bg-white/95 backdrop-blur-md rounded-3xl p-3 sm:p-5 shadow-2xl border border-white/40 text-left"
            >
                <!-- Tab Selector Buttons -->
                <div class="flex items-center gap-1.5 sm:gap-2 border-b border-slate-100 pb-3 mb-3 overflow-x-auto scrollbar-none">
                    <button
                        type="button"
                        @click="activeTab = 'destinasi'"
                        :class="activeTab === 'destinasi' ? 'bg-sky-600 text-white font-bold shadow-sm' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100 font-semibold'"
                        class="px-3.5 py-1.5 rounded-xl text-xs sm:text-sm transition whitespace-nowrap"
                    >
                        Direktori Wisata
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'buddy'"
                        :class="activeTab === 'buddy' ? 'bg-sky-600 text-white font-bold shadow-sm' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100 font-semibold'"
                        class="px-3.5 py-1.5 rounded-xl text-xs sm:text-sm transition whitespace-nowrap"
                    >
                        Teman Jalan (Buddy)
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'forum'"
                        :class="activeTab === 'forum' ? 'bg-sky-600 text-white font-bold shadow-sm' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-100 font-semibold'"
                        class="px-3.5 py-1.5 rounded-xl text-xs sm:text-sm transition whitespace-nowrap"
                    >
                        Tanya Warga Lokal
                    </button>
                </div>

                <!-- Form Search Dynamic -->
                <form
                    :action="tabs.find(t => t.id === activeTab).action"
                    method="GET"
                    class="flex flex-col sm:flex-row items-center gap-2.5"
                >
                    <div class="relative flex-1 w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="search"
                            :placeholder="tabs.find(t => t.id === activeTab).placeholder"
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                        />
                    </div>

                    <button
                        type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-sky-600 hover:bg-sky-700 active:scale-98 text-white text-xs sm:text-sm font-bold rounded-2xl shadow-md hover:shadow-lg transition-all duration-200"
                    >
                        <span>Cari Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Alert Banner Getok Harga Terbaru (2 Laporan Terkini) -->
    @if ($recentGetokAlerts->count() > 0)
        <section class="bg-red-50/80 border-y border-red-200/80 py-4 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-2xl bg-red-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm mt-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black uppercase tracking-wider text-red-700">Waspada Getok Harga Terkini</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-ping"></span>
                        </div>
                        <div class="text-xs sm:text-sm text-red-900 mt-0.5 space-y-1">
                            @foreach ($recentGetokAlerts as $alert)
                                <p class="line-clamp-1">
                                    <strong>{{ $alert->item_name }}</strong> di <em>{{ $alert->destination->name ?? 'Destinasi' }}</em> dilaporkan dibayar <strong>Rp {{ number_format($alert->price_paid, 0, ',', '.') }}</strong>
                                    @if ($alert->notes) &mdash; <span class="text-red-700 italic">"{{ $alert->notes }}"</span>@endif
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0 self-end md:self-center">
                    <a
                        href="{{ route('destinations.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-sm transition"
                    >
                        <span>Cek Indeks Kewajaran</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- Main Content Container -->
    <div class="bg-slate-50 py-12 sm:py-16 space-y-16 sm:space-y-20">
        <!-- 1. Destinasi Wisata Terpopuler (3 Destinasi) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-sky-600 mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        <span>Comprehensive Directory</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Destinasi Terpopuler Minggu Ini
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Informasi tiket resmi terverifikasi dan panduan logistik terlengkap.
                    </p>
                </div>

                <a
                    href="{{ route('destinations.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-sky-600 hover:text-sky-700 transition"
                >
                    <span>Lihat Semua Destinasi</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <!-- Grid 3 Destinasi Terpopuler -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($popularDestinations as $destination)
                    <x-cards.destination-card :destination="$destination" />
                @endforeach
            </div>
        </section>

        <!-- 2. Teman Jalan Hampir Penuh (2 Ajakan Travel Buddy) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-sky-50/40 to-slate-50 rounded-3xl border border-slate-200/90 p-6 sm:p-10 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-600 mb-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Slot Hampir Penuh • Segera Berangkat</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            Papan Teman Jalan (Travel Buddy)
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Ambil slot terakhir, patungan sewa kendaraan / akomodasi, dan jelajah lebih hemat.
                        </p>
                    </div>

                    <a
                        href="{{ route('travel-buddy.index') }}"
                        class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-sky-600 hover:text-sky-700 transition"
                    >
                        <span>Buka Semua Ajakan ({{ \App\Models\BuddyRequest::where('status', 'open')->count() }})</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>

                <!-- 2 Card Travel Buddy -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse ($urgentBuddyRequests as $buddy)
                        <x-cards.buddy-card :buddyRequest="$buddy" />
                    @empty
                        <div class="col-span-2 text-center py-8 text-sm text-slate-400">
                            Belum ada ajakan teman jalan yang aktif saat ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- 3. Diskusi Q&A Situasional Terhangat (3 Diskusi) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-600 mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                        <span>Situasional & Cuaca Real-Time</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Diskusi Terhangat Komunitas
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Tanya jawab kondisi jalur, cuaca, dan tips wisata yang dijawab langsung oleh warga lokal.
                    </p>
                </div>

                <a
                    href="{{ route('forum.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-sky-600 hover:text-sky-700 transition"
                >
                    <span>Buka Forum Komunitas</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="space-y-4">
                @foreach ($hotThreads as $thread)
                    <x-cards.thread-card :thread="$thread" />
                @endforeach
            </div>
        </section>

        <!-- 4. Community Trust & CTA Banner -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative bg-gradient-to-br from-slate-900 via-sky-950 to-slate-900 rounded-3xl p-8 sm:p-12 text-white shadow-xl overflow-hidden text-center md:text-left flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-xl space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-sky-400">Gotong Royong Wisata Sehat</span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Pernah Kena Getok Harga atau Punya Info Rute Bagus?
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Bagikan laporan harga riil yang kamu bayar untuk melindungi sesama traveler, atau inisiasi trip patunganmu sekarang juga!
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
                    <a
                        href="{{ route('destinations.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-sky-500 hover:bg-sky-400 text-slate-950 text-xs sm:text-sm font-bold transition shadow-md"
                    >
                        <span>Cari Destinasi & Lapor Biaya</span>
                    </a>
                    <a
                        href="{{ route('travel-buddy.create') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs sm:text-sm font-bold transition backdrop-blur-md"
                    >
                        <span>+ Buat Ajakan Patungan</span>
                    </a>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
