<x-layouts.app
    :title="$category->title . ' — Eksplor Pedia'"
    :description="$category->description"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
            <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('eksplor-pedia.index') }}" class="hover:text-amber-600 transition-colors">Eksplor Pedia</a>
            <span>/</span>
            <span class="text-slate-800 font-bold truncate max-w-xs">{{ $category->city->name ?? 'Kota' }}</span>
        </nav>

        <!-- Category Banner in Go-Hunter Ocean Azure theme -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-600 via-sky-700 to-slate-900 text-white p-6 sm:p-8 border border-sky-500/20 shadow-lg">
            <div class="absolute inset-0 opacity-15">
                <img src="{{ $category->thumbnail_url ?: 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $category->title }}" class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-sky-900/90 via-sky-800/80 to-transparent"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-white border border-white/20 text-xs font-bold">
                        <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $category->city->name ?? 'Wilayah Destinasi' }}</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight">
                        {{ $category->title }}
                    </h1>

                    <p class="text-xs sm:text-sm text-sky-100 leading-relaxed">
                        {{ $category->description }}
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center min-w-[120px]">
                        <div class="text-[10px] uppercase font-bold text-sky-200">Total Set Kuis</div>
                        <div class="text-2xl font-black text-amber-300 mt-0.5">{{ $quizzes->count() }} Paket</div>
                    </div>
                    <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center min-w-[120px]">
                        <div class="text-[10px] uppercase font-bold text-sky-200">Total Dimainkan</div>
                        <div class="text-2xl font-black text-white mt-0.5">{{ number_format($category->total_attempts) }}x</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz List Section (PRD 3.2: Sub-Level Quizzes with Difficulty Badges) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Pilih Tingkat Kesulitan Paket Kuis</h2>
                    <p class="text-xs text-slate-500">Makin tinggi tingkat kesulitan, makin besar perolehan poin XP yang didapat!</p>
                </div>

                <a
                    href="{{ route('eksplor-pedia.index') }}"
                    class="text-xs font-bold text-slate-600 hover:text-amber-600 transition-colors flex items-center gap-1"
                >
                    &larr; Ganti Kota
                </a>
            </div>

            <!-- Quiz Row Cards -->
            <div class="space-y-4">
                @foreach ($quizzes as $quiz)
                    @php
                        $userAttempt = $userHistories[$quiz->id] ?? null;
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-2xs hover:shadow-md transition-all flex flex-col md:flex-row md:items-center justify-between gap-6 group hover:border-amber-300">
                        <!-- Left: Info & Difficulty Badge -->
                        <div class="space-y-3 max-w-xl">
                            <div class="flex items-center gap-2 flex-wrap">
                                <!-- Difficulty Badge -->
                                @if ($quiz->difficulty === 'easy')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        Tingkat: Mudah (+{{ $quiz->points_per_question }} XP/soal)
                                    </span>
                                @elseif ($quiz->difficulty === 'medium')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        Tingkat: Sedang (+{{ $quiz->points_per_question }} XP/soal)
                                    </span>
                                @elseif ($quiz->difficulty === 'hard')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        Tingkat: Sulit (+{{ $quiz->points_per_question }} XP/soal)
                                    </span>
                                @endif

                                @if ($userAttempt)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                        <svg class="w-3.5 h-3.5 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Pernah Dikerjakan: Skor {{ $userAttempt->score }}% (+{{ $userAttempt->points_earned }} XP)
                                    </span>
                                @endif
                            </div>

                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-amber-600 transition-colors">
                                    {{ $quiz->title }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">
                                    Paket kuis bertema sejarah dengan sistem pilihan ganda dan pembahasan edukatif mendalam di akhir pengerjaan.
                                </p>
                            </div>

                            <!-- Meta Chips: Jumlah Soal, Durasi Waktu, Potensi Reward (PRD 3.2) -->
                            <div class="flex items-center gap-4 text-xs font-semibold text-slate-500 pt-1">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ $quiz->questions_count ?: $quiz->total_questions }} Soal
                                </span>

                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Durasi {{ $quiz->time_limit_minutes }} Menit
                                </span>

                                <span class="flex items-center gap-1.5 text-amber-600 font-bold">
                                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    Maksimal +{{ $quiz->max_points }} XP
                                </span>
                            </div>
                        </div>

                        <!-- Right: Action Button (PRD: "Mulai Mengerjakan") -->
                        <div class="flex items-center gap-3 shrink-0 pt-2 md:pt-0">
                            <a
                                href="{{ route('eksplor-pedia.quiz', $quiz->slug) }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs py-3 px-6 rounded-xl shadow-sm transition-all active:scale-95"
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
        </div>

    </div>
</x-layouts.app>
