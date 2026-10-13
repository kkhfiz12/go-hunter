<x-layouts.app title="Forum Diskusi & Q&A Situasional — Go Hunter" description="Tanya jawab kondisi terkini, rute transportasi alternatif, dan tips wisata langsung dari warga lokal dan sesama traveler.">
    <div class="bg-slate-50 min-h-screen py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero / Header Section -->
            <div class="relative bg-gradient-to-br from-slate-900 via-sky-950 to-slate-900 rounded-3xl p-6 sm:p-10 md:p-12 text-white shadow-xl overflow-hidden mb-8">
                <!-- Decorative background elements -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-sky-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="max-w-2xl space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-500/20 text-sky-300 text-xs font-bold border border-sky-400/30 backdrop-blur-md">
                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>Community Q&A Feed</span>
                        </div>
                        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                            Info Situasional Nyata, <br class="hidden sm:block" />
                            <span class="text-sky-400">Langsung dari</span> Warga Lokal.
                        </h1>
                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-xl">
                            Cek kondisi cuaca terkini, jalur banjir/longsor, calo tiket, atau rute angkutan umum sebelum berangkat ke destinasi tujuanmu.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row md:flex-col gap-3 flex-shrink-0">
                        <a
                            href="{{ route('threads.create') }}"
                            class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-sm shadow-lg shadow-sky-500/30 hover:shadow-sky-500/50 hover:scale-105 transition-all duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>+ Buat Pertanyaan Baru</span>
                        </a>

                        <div class="flex items-center justify-center sm:justify-start md:justify-center gap-4 text-xs text-slate-300 pt-1">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                Jawaban Terverifikasi
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                +5 Poin Tanya
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Layout: 2 Columns (Content + Right Sticky Sidebar) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Feed Area -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Category Tabs (Horizontal Scrollable) -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                        @foreach ($categories as $catKey => $catLabel)
                            <a
                                href="{{ route('threads.index', array_merge(request()->query(), ['category' => $catKey, 'page' => 1])) }}"
                                class="px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200 border {{ $selectedCategory === $catKey ? 'bg-sky-600 text-white border-sky-600 shadow-sm' : 'bg-white text-slate-600 hover:text-sky-600 hover:bg-slate-50 border-slate-200/80' }}"
                            >
                                {{ $catLabel }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Search & Filter Options Bar -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-3 sm:p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <!-- Search Keyword -->
                        <form action="{{ route('threads.index') }}" method="GET" class="flex-1 flex items-center gap-2">
                            <input type="hidden" name="category" value="{{ $selectedCategory }}">
                            <input type="hidden" name="sort" value="{{ $selectedSort }}">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ $search ?? '' }}"
                                    placeholder="Cari topik situasi, rute, cuaca..."
                                    class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                                />
                            </div>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl transition"
                            >
                                Cari
                            </button>
                        </form>

                        <!-- Sorting Dropdown -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider hidden sm:inline">Urutkan:</span>
                            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-bold">
                                <a
                                    href="{{ route('threads.index', array_merge(request()->query(), ['sort' => 'newest'])) }}"
                                    class="px-2.5 py-1 rounded-lg transition {{ $selectedSort === 'newest' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}"
                                >
                                    Terbaru
                                </a>
                                <a
                                    href="{{ route('threads.index', array_merge(request()->query(), ['sort' => 'popular'])) }}"
                                    class="px-2.5 py-1 rounded-lg transition {{ $selectedSort === 'popular' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}"
                                >
                                    Populer
                                </a>
                                <a
                                    href="{{ route('threads.index', array_merge(request()->query(), ['sort' => 'solved'])) }}"
                                    class="px-2.5 py-1 rounded-lg transition {{ $selectedSort === 'solved' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}"
                                >
                                    Solusi
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Thread List -->
                    @if ($threads->count() > 0)
                        <div class="space-y-4">
                            @foreach ($threads as $thread)
                                <x-cards.thread-card :thread="$thread" />
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $threads->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="bg-white rounded-3xl border border-slate-200/90 p-8 sm:p-12 text-center shadow-sm">
                            <div class="w-16 h-16 rounded-3xl bg-sky-50 text-sky-600 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Diskusi di Kategori Ini</h3>
                            <p class="text-sm text-slate-500 mb-6 max-w-md mx-auto leading-relaxed">
                                Jadilah yang pertama menanyakan kondisi jalur, situasi cuaca, atau tips seputar destinasi impianmu!
                            </p>
                            <a
                                href="{{ route('threads.create') }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs sm:text-sm font-bold shadow-sm transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>+ Tanyakan Sekarang</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Right Sticky Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Community Guidelines Card -->
                    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm sticky top-24 space-y-5">
                        <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                            <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Etika Forum Go Hunter</h3>
                                <p class="text-[11px] text-slate-500">Transparansi & gotong royong</p>
                            </div>
                        </div>

                        <!-- Points Rewards Rules -->
                        <div class="space-y-2.5 text-xs text-slate-600">
                            <div class="flex items-start gap-2.5">
                                <span class="px-1.5 py-0.5 rounded-md bg-amber-50 text-amber-700 font-bold text-[10px] border border-amber-200 flex-shrink-0 mt-0.5">
                                    +5 Pts
                                </span>
                                <span>Buat pertanyaan baru mengenai kondisi destinasi.</span>
                            </div>

                            <div class="flex items-start gap-2.5">
                                <span class="px-1.5 py-0.5 rounded-md bg-sky-50 text-sky-700 font-bold text-[10px] border border-sky-200 flex-shrink-0 mt-0.5">
                                    +2 Pts
                                </span>
                                <span>Menjawab pertanyaan dan memberikan info valid.</span>
                            </div>

                            <div class="flex items-start gap-2.5">
                                <span class="px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200 flex-shrink-0 mt-0.5">
                                    +10 Pts
                                </span>
                                <span>Jawaban ditandai sebagai <strong>Solusi Terkonfirmasi Warga Lokal</strong>.</span>
                            </div>
                        </div>

                        <!-- CTA Ask Question -->
                        <div class="pt-3 border-t border-slate-100">
                            <a
                                href="{{ route('threads.create') }}"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs sm:text-sm font-bold shadow-sm transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Buat Pertanyaan Baru</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
