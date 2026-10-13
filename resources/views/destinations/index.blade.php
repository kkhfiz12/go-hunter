<x-layouts.app title="Katalog Destinasi Wisata Indonesia">
    <div class="bg-slate-50 min-h-screen py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header & Search Section -->
            <div class="mb-8 sm:mb-10 text-center sm:text-left">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-sky-600 bg-sky-50 px-3 py-1 rounded-full border border-sky-100 mb-3">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Comprehensive Directory
                        </span>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                            Katalog Destinasi Wisata
                        </h1>
                        <p class="text-sm sm:text-base text-slate-600 mt-2 max-w-2xl">
                            Temukan rekomendasi destinasi lengkap dengan panduan tiket masuk resmi dan tarif parkir riil tanpa khawatir getok harga.
                        </p>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('destinations.index') }}" class="w-full md:w-80">
                        @if(request('category') && strtolower(request('category')) !== 'semua')
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        <div class="relative">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search', request('q')) }}"
                                placeholder="Cari tempat atau kota..."
                                class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 shadow-sm transition"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Category Pills Filter & Sort Controls -->
                <div class="mt-6 pt-6 border-t border-slate-200/80 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                    <!-- Horizontal Scrolling Category Pills -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0 scrollbar-none">
                        @foreach ($categories as $cat)
                            @php
                                $isActive = strtolower($selectedCategory) === strtolower($cat) || ($cat === 'Semua' && (empty($selectedCategory) || strtolower($selectedCategory) === 'semua'));
                                $params = request()->except('category', 'page');
                                if ($cat !== 'Semua') {
                                    $params['category'] = strtolower($cat);
                                }
                                $url = route('destinations.index', $params);
                            @endphp
                            <a
                                href="{{ $url }}"
                                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all {{ $isActive ? 'bg-sky-600 text-white shadow-sm shadow-sky-600/30' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300 hover:text-slate-900' }}"
                            >
                                {{ $cat }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Sort Selector Dropdown -->
                    <div class="flex items-center justify-between sm:justify-end gap-2 text-xs sm:text-sm">
                        <span class="text-slate-500 font-medium">Urutkan:</span>
                        <div class="flex items-center gap-1.5 bg-white p-1 rounded-xl border border-slate-200 shadow-sm">
                            <a
                                href="{{ route('destinations.index', array_merge(request()->except('sort', 'page'), ['sort' => 'newest'])) }}"
                                class="px-2.5 py-1.5 rounded-lg font-semibold transition {{ $sort === 'newest' ? 'bg-sky-50 text-sky-600' : 'text-slate-600 hover:text-slate-900' }}"
                            >
                                Terbaru
                            </a>
                            <a
                                href="{{ route('destinations.index', array_merge(request()->except('sort', 'page'), ['sort' => 'cheapest'])) }}"
                                class="px-2.5 py-1.5 rounded-lg font-semibold transition {{ $sort === 'cheapest' ? 'bg-sky-50 text-sky-600' : 'text-slate-600 hover:text-slate-900' }}"
                            >
                                Termurah
                            </a>
                            <a
                                href="{{ route('destinations.index', array_merge(request()->except('sort', 'page'), ['sort' => 'rating'])) }}"
                                class="px-2.5 py-1.5 rounded-lg font-semibold transition {{ $sort === 'rating' ? 'bg-sky-50 text-sky-600' : 'text-slate-600 hover:text-slate-900' }}"
                            >
                                Rating
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Destinations Card Grid (1 col on mobile, 3 col on desktop) -->
            @if ($destinations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach ($destinations as $destination)
                        <x-cards.destination-card :destination="$destination" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $destinations->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 text-center max-w-lg mx-auto shadow-sm my-8">
                    <div class="w-16 h-16 bg-sky-50 rounded-2xl flex items-center justify-center text-sky-600 mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Destinasi Tidak Ditemukan</h3>
                    <p class="text-sm text-slate-500 mb-6">
                        @if ($search)
                            Tidak ada hasil untuk pencarian "<span class="font-semibold text-slate-700">{{ $search }}</span>". Coba gunakan kata kunci lain atau reset filter.
                        @else
                            Belum ada destinasi yang cocok dengan filter yang dipilih.
                        @endif
                    </p>
                    <a
                        href="{{ route('destinations.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-xl shadow-sm transition"
                    >
                        Lihat Semua Destinasi
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
