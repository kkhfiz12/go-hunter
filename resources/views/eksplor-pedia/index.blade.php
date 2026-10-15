<x-layouts.app
    title="Eksplor Pedia — Gamifikasi & Kuis Sejarah Edukatif"
    description="Asah wawasan sejarah cagar budaya Indonesia, raih poin XP bertingkat, puncaki Leaderboard, dan tukarkan dengan voucher tiket wisata resmi."
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

        <!-- Hero Section with Go-Hunter Brand Theme -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-600 via-sky-700 to-slate-900 text-white p-6 sm:p-10 shadow-xl border border-sky-500/20">
            <!-- Subtle Decorative Circles -->
            <div class="absolute -right-12 -bottom-12 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -top-12 w-64 h-64 bg-amber-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
                <div class="max-w-2xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-white text-xs font-bold tracking-wide uppercase">
                        <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                        <span>Eksplor Pedia Super-App</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white leading-tight">
                        Jelajah Sejarah, <br class="hidden sm:inline">
                        <span class="text-amber-300">
                            Taklukkan Kuis & Klaim Reward
                        </span>
                    </h1>

                    <p class="text-sm sm:text-base text-sky-100 leading-relaxed max-w-xl">
                        Uji pengetahuanmu seputar warisan budaya dan fakta sejarah tiap kota. Jawab soal bertingkat dari Mudah (+10 XP) hingga Sulit (+35 XP), puncaki papan peringkat global, dan tukarkan poinmu dengan tiket masuk wisata gratis!
                    </p>

                    <!-- Quick Action Buttons -->
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a
                            href="{{ route('eksplor-pedia.leaderboard') }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs transition-all shadow-md active:scale-95"
                        >
                            <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Papan Peringkat (Top 50)</span>
                        </a>

                        <a
                            href="{{ route('eksplor-pedia.rewards') }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-sky-50 text-sky-800 font-bold text-xs transition-all shadow-sm active:scale-95"
                        >
                            <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                            <span>Katalog Tukar Hadiah</span>
                        </a>

                        @auth
                            <a
                                href="{{ route('eksplor-pedia.history') }}"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-800/60 hover:bg-sky-800 text-sky-100 hover:text-white font-medium text-xs transition-all border border-sky-400/30"
                            >
                                <span>Riwayat Kuis Saya &rarr;</span>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- User Stats / Top Leaderboard Mini Card -->
                <div class="w-full lg:w-80 shrink-0 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-5 space-y-4 shadow-lg text-white">
                    @auth
                        <div class="flex items-center gap-3">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-white/50">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-white text-sky-700 font-black text-lg flex items-center justify-center shadow-md">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <div class="text-xs text-sky-200 font-medium">Selamat Datang,</div>
                                <div class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</div>
                                <span class="inline-block mt-0.5 text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-white/20 text-white">
                                    {{ auth()->user()->badge_level ?? 'Penjelajah Pemula' }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-white/15">
                            <div class="bg-black/20 rounded-xl p-2.5">
                                <div class="text-[10px] text-sky-200 uppercase font-semibold">Total Poin</div>
                                <div class="text-base font-extrabold text-amber-300 flex items-center gap-1">
                                    <span>★</span>
                                    <span>{{ max(auth()->user()->total_points ?? 0, auth()->user()->reputation_points ?? 0) }} XP</span>
                                </div>
                            </div>
                            <div class="bg-black/20 rounded-xl p-2.5">
                                <div class="text-[10px] text-sky-200 uppercase font-semibold">Kuis Selesai</div>
                                <div class="text-base font-extrabold text-white">
                                    {{ auth()->user()->quizHistories()->count() }} Kali
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-2 space-y-3">
                            <div class="w-12 h-12 mx-auto rounded-2xl bg-amber-400 text-slate-950 flex items-center justify-center text-xl font-black shadow-md">
                                ★
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white">Kumpulkan Poin & Naikkan Level</div>
                                <p class="text-xs text-sky-100 mt-1">Masuk untuk menyimpan skor kuis, mengklaim XP, dan menukar voucher!</p>
                            </div>
                            <div class="flex items-center justify-center gap-2 pt-1">
                                <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-white text-slate-900 font-bold text-xs hover:bg-slate-100 transition-colors">
                                    Masuk
                                </a>
                                <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-amber-400 text-slate-950 font-bold text-xs hover:bg-amber-300 transition-colors">
                                    Daftar Akun
                                </a>
                            </div>
                        </div>
                    @endauth

                    <!-- Global Quick Stats -->
                    <div class="flex items-center justify-between text-[11px] text-sky-200 pt-2 border-t border-white/15">
                        <span>{{ $totalQuizzesCount }} Paket Kuis Tersedia</span>
                        <span>•</span>
                        <span>{{ number_format($totalAttemptsCount) }} Percobaan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- City Navigation Tabs & Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Katalog Paket Kuis Per Kota</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pilih kota destinasi untuk membuka daftar paket kuis bertingkat</p>
            </div>

            <!-- City Filter Pills in Go-Hunter Brand Style -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 scrollbar-none">
                <a
                    href="{{ route('eksplor-pedia.index') }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ !request('city') ? 'bg-sky-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}"
                >
                    Semua Kota
                </a>
                @foreach ($cities as $city)
                    <a
                        href="{{ route('eksplor-pedia.index', ['city' => $city->slug]) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ request('city') === $city->slug ? 'bg-sky-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}"
                    >
                        {{ $city->name }}
                        <span class="ml-1 opacity-80 text-[10px]">({{ $city->quiz_categories_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- City Quiz Cards Grid -->
        @if ($categories->isEmpty())
            <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Kuis untuk Kota Ini</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Silakan pilih kota lain atau kembali ke seluruh katalog kuis.</p>
                <a href="{{ route('eksplor-pedia.index') }}" class="mt-4 inline-block px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl transition-colors">
                    Lihat Semua Kuis
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($categories as $cat)
                    <div class="group bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden hover:-translate-y-1">
                        <!-- Card Thumbnail with City Badge -->
                        <div class="relative h-52 overflow-hidden bg-slate-100">
                            <img
                                src="{{ $cat->thumbnail_url ?: 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&w=800&q=80' }}"
                                alt="{{ $cat->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>

                            <!-- City Badge (Top Left) -->
                            <div class="absolute top-3.5 left-3.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-white/95 backdrop-blur-md text-slate-900 shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $cat->city->name ?? 'Indonesia' }}</span>
                                </span>
                            </div>

                            <!-- Attempts Counter (Top Right) -->
                            <div class="absolute top-3.5 right-3.5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-black/60 backdrop-blur-md text-amber-300 border border-white/10">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ number_format($cat->total_attempts) }} dicoba</span>
                                </span>
                            </div>

                            <!-- Title on Image Bottom -->
                            <div class="absolute bottom-3.5 left-4 right-4">
                                <h3 class="text-lg font-bold text-white group-hover:text-amber-300 transition-colors line-clamp-1">
                                    {{ $cat->title }}
                                </h3>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed line-clamp-2">
                                {{ $cat->description }}
                            </p>

                            <!-- Difficulty & Quiz Sets Summary -->
                            <div class="space-y-3 pt-2 border-t border-slate-100">
                                <div class="flex items-center justify-between text-xs text-slate-500 font-semibold">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        {{ $cat->quizzes_count }} Paket Kuis Bertingkat
                                    </span>

                                    @php
                                        $maxCatPoints = $cat->quizzes->sum(function($q) {
                                            return $q->max_points;
                                        });
                                    @endphp
                                    <span class="text-amber-600 font-extrabold flex items-center gap-0.5">
                                        ★ s.d +{{ $maxCatPoints }} XP
                                    </span>
                                </div>

                                <!-- Difficulty Pills -->
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @foreach ($cat->quizzes as $quiz)
                                        @if ($quiz->difficulty === 'easy')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Mudah (+10)
                                            </span>
                                        @elseif ($quiz->difficulty === 'medium')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                Sedang (+20)
                                            </span>
                                        @elseif ($quiz->difficulty === 'hard')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                Sulit (+35)
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <!-- CTA Button (Go-Hunter Signature Sky Button) -->
                            <a
                                href="{{ route('eksplor-pedia.category', $cat->slug) }}"
                                class="w-full flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs py-3 px-4 rounded-xl shadow-sm transition-all active:scale-[0.98]"
                            >
                                <span>Mulai Mengerjakan</span>
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Educational Gamification Explainer Banner -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
            <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-black text-sm">
                    1
                </div>
                <h4 class="text-base font-bold text-slate-900">Pilih Kota & Tingkat Kesulitan</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Setiap paket menawarkan tingkat kesulitan: Mudah (+10 XP), Sedang (+20 XP), dan Sulit (+35 XP per jawaban benar).
                </p>
            </div>

            <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-black text-sm">
                    2
                </div>
                <h4 class="text-base font-bold text-slate-900">Jawab Soal & Raih Poin XP</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Pengerjaan interaktif dengan batas waktu. Dapatkan pembahasan sejarah lengkap setelah kuis selesai.
                </p>
            </div>

            <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-sm">
                    3
                </div>
                <h4 class="text-base font-bold text-slate-900">Puncaki Ranking & Tukar Voucher</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Akumulasi poinmu menentukan peringkat Leaderboard dan dapat langsung ditukarkan dengan tiket wisata asli!
                </p>
            </div>
        </div>

    </div>
</x-layouts.app>
