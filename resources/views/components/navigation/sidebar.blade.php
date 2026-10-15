@props(['contextNav' => null])

<aside
    class="w-72 bg-white border-r border-slate-200/90 flex flex-col shrink-0 h-screen sticky top-0 shadow-sm z-30 transition-all select-none overflow-hidden"
>
    <!-- Brand Logo & Header (Interactive: klik mengarahkan langsung ke Dashboard) -->
    <div class="h-20 px-6 flex items-center justify-between border-b border-slate-100 bg-gradient-to-b from-slate-50/50 to-white shrink-0">
        <a
            href="{{ route('dashboard') }}"
            @click="activeModule = 'dashboard'"
            class="flex items-center gap-3 group focus:outline-none cursor-pointer select-none"
            title="Kembali ke Dashboard GoHunter"
        >
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-sky-600 via-sky-500 to-cyan-400 flex items-center justify-center text-white shadow-md shadow-sky-500/25 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                    <polygon points="12,6 15.5,12 12,18 8.5,12" fill="white" stroke="none"/>
                </svg>
            </div>
            <div>
                <div class="text-xl font-black tracking-tight text-slate-900 group-hover:text-sky-600 transition-colors">
                    Go<span class="text-sky-600">Hunter</span>
                </div>
                <div class="text-[10px] font-semibold tracking-wider uppercase text-slate-400 -mt-0.5">
                    Wisata Bebas Getok
                </div>
            </div>
        </a>
    </div>

    <!-- Scrollable Content: Dynamic Sub-Menu Berdasarkan Top Navbar (Area Merah) -->
    <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6 custom-scrollbar">
        <div>
            <!-- Section Header Indicator -->
            <div class="px-2 mb-3 flex items-center justify-between text-[11px] font-bold tracking-wider text-slate-400 uppercase">
                <span>Navigasi Fitur</span>
                <span
                    class="text-[10px] font-extrabold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-full border border-sky-100 uppercase"
                    x-text="activeModule === 'dashboard' ? 'Dashboard' : (activeModule === 'tripy' ? 'TRIPY' : (activeModule === 'eksplor_pedia' ? 'Eksplor Pedia' : 'Urban Rally'))"
                ></span>
            </div>

            <!-- Sub-Menu 1: Modul Active = Dashboard -->
            <template x-if="activeModule === 'dashboard'">
                <nav class="space-y-1.5">
                    <!-- Destinasi -->
                    <a
                        href="{{ route('destinations.index') }}"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('destinations.index') || (request()->routeIs('destinations.*') && !request()->routeIs('destinations.price-reports.*')) ? 'bg-sky-50 text-sky-700 border border-sky-200/80 font-bold' : 'text-slate-700 hover:bg-slate-100 hover:text-sky-600' }}"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('destinations.index') || (request()->routeIs('destinations.*') && !request()->routeIs('destinations.price-reports.*')) ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span>Destinasi</span>
                    </a>

                    <!-- Cek Harga Wajar -->
                    <a
                        href="{{ route('destinations.index', ['sort' => 'cheapest']) }}"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->get('sort') === 'cheapest' || request()->routeIs('destinations.price-reports.*') || request()->routeIs('price-reports.*') ? 'bg-sky-50 text-sky-700 border border-sky-200/80 font-bold' : 'text-slate-700 hover:bg-slate-100 hover:text-sky-600' }}"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->get('sort') === 'cheapest' || request()->routeIs('destinations.price-reports.*') || request()->routeIs('price-reports.*') ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span>Cek Harga Wajar</span>
                    </a>

                    <!-- Teman Jalan -->
                    <a
                        href="{{ route('travel-buddy.index') }}"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('travel-buddy.*') || request()->routeIs('buddy-requests.*') || request()->routeIs('buddy.*') ? 'bg-sky-50 text-sky-700 border border-sky-200/80 font-bold' : 'text-slate-700 hover:bg-slate-100 hover:text-sky-600' }}"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('travel-buddy.*') || request()->routeIs('buddy-requests.*') || request()->routeIs('buddy.*') ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span>Teman Jalan</span>
                    </a>

                    <!-- Diskusi -->
                    <a
                        href="{{ route('forum.index') }}"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('forum.*') || request()->routeIs('threads.*') ? 'bg-sky-50 text-sky-700 border border-sky-200/80 font-bold' : 'text-slate-700 hover:bg-slate-100 hover:text-sky-600' }}"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('forum.*') || request()->routeIs('threads.*') ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <span>Diskusi</span>
                    </a>
                </nav>
            </template>

            <!-- Sub-Menu 2: Modul Active = TRIPY -->
            <template x-if="activeModule === 'tripy'">
                <nav class="space-y-1.5">
                    <!-- Peta Interaktif -->
                    <a
                        href="{{ route('tripy.index') }}"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('tripy.*') ? 'bg-sky-50 text-sky-700 border border-sky-200/80 font-bold' : 'text-slate-700 hover:bg-slate-100 hover:text-sky-600' }}"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('tripy.*') ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <span>Peta Interaktif</span>
                    </a>

                    <!-- Filter Kota -->
                    <a
                        href="{{ route('tripy.index') }}#cities"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100 hover:text-sky-600 transition-all"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span>Filter Kota</span>
                    </a>

                    <!-- Spot Rekomendasi -->
                    <a
                        href="{{ route('tripy.index') }}#spots"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100 hover:text-sky-600 transition-all"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        <span>Spot Rekomendasi</span>
                    </a>

                    <!-- Riwayat Check-in -->
                    <a
                        href="{{ route('tripy.index') }}#checkin"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100 hover:text-sky-600 transition-all"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span>Riwayat Check-in</span>
                    </a>
                </nav>
            </template>

            <!-- Sub-Menu 3: Modul Active = Eksplor Pedia -->
            <template x-if="activeModule === 'eksplor_pedia'">
                <nav class="space-y-1.5">
                    <!-- Katalog Kuis Kota -->
                    <a
                        href="{{ route('eksplor-pedia.index') }}"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('eksplor-pedia.index') || request()->routeIs('eksplor-pedia.category') || request()->routeIs('eksplor-pedia.quiz') ? 'bg-sky-50 text-sky-700 border border-sky-200/80 font-bold' : 'text-slate-700 hover:bg-slate-100 hover:text-sky-600' }}"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('eksplor-pedia.index') || request()->routeIs('eksplor-pedia.category') || request()->routeIs('eksplor-pedia.quiz') ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span>Katalog Kuis Kota</span>
                    </a>

                    <!-- Leaderboard -->
                    <a
                        href="{{ route('eksplor-pedia.leaderboard') }}"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('eksplor-pedia.leaderboard') ? 'bg-sky-50 text-sky-700 border border-sky-200/80 font-bold' : 'text-slate-700 hover:bg-slate-100 hover:text-sky-600' }}"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('eksplor-pedia.leaderboard') ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <span>Leaderboard</span>
                    </a>

                    <!-- Tukar Reward -->
                    <a
                        href="{{ route('eksplor-pedia.rewards') }}"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('eksplor-pedia.rewards') ? 'bg-sky-50 text-sky-700 border border-sky-200/80 font-bold' : 'text-slate-700 hover:bg-slate-100 hover:text-sky-600' }}"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('eksplor-pedia.rewards') ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                        </div>
                        <span>Tukar Reward</span>
                    </a>

                    <!-- Riwayat Kuis Saya -->
                    <a
                        href="{{ route('eksplor-pedia.history') }}"
                        class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('eksplor-pedia.history') ? 'bg-sky-50 text-sky-700 border border-sky-200/80 font-bold' : 'text-slate-700 hover:bg-slate-100 hover:text-sky-600' }}"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('eksplor-pedia.history') ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span>Riwayat Kuis Saya</span>
                        </div>
                        @guest
                            <span class="text-[10px] text-slate-400 font-medium">Login</span>
                        @endguest
                    </a>
                </nav>
            </template>

            <!-- Sub-Menu 4: Modul Active = Urban Rally -->
            <template x-if="activeModule === 'urban_rally'">
                <nav class="space-y-1.5">
                    <!-- Pilih Transportasi -->
                    <a
                        href="{{ route('urban-rally.index') }}#filter-transportasi"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('urban-rally.*') ? 'bg-sky-50 text-sky-700 border border-sky-200/80 font-bold' : 'text-slate-700 hover:bg-slate-100 hover:text-sky-600' }}"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('urban-rally.*') ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <span>Pilih Transportasi</span>
                    </a>

                    <!-- Rekomendasi Rute -->
                    <a
                        href="{{ route('urban-rally.index') }}#rekomendasi"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100 hover:text-sky-600 transition-all"
                    >
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-100 text-slate-500 group-hover:bg-sky-50 group-hover:text-sky-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        <span>Rekomendasi Rute</span>
                    </a>
                </nav>
            </template>
        </div>

        <!-- Optional Contextual Slot (misal Filter Kategori Destinasi atau Filter Khusus) -->
        @if ($contextNav)
            <div class="pt-4 border-t border-slate-100">
                {{ $contextNav }}
            </div>
        @endif

        <!-- Reputasi / Gamification Badge Card (Bersih tanpa titik kedap-kedip) -->
        <div class="pt-2">
            <div class="p-3.5 bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl text-white shadow-sm border border-slate-700/60">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold text-sky-300">Penjelajah GoHunter</span>
                    <span class="text-[10px] bg-amber-400/20 text-amber-300 px-2 py-0.5 rounded-full font-bold">
                        {{ auth()->user()->badge_level ?? 'Explorer' }}
                    </span>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-300 mt-1">
                    <span>Poin Terkumpul:</span>
                    <span class="font-bold text-amber-400">★ {{ max(auth()->user()->total_points ?? 0, auth()->user()->reputation_points ?? 0) }} XP</span>
                </div>
            </div>
        </div>
    </div>
</aside>
