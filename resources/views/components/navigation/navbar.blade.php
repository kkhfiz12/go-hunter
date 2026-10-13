<header
    x-data="{ mobileOpen: false, userMenuOpen: false }"
    class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-sm transition-all"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">
            <!-- Brand Logo -->
            <div class="flex items-center gap-8">
                <a href="{{ Route::has('home') ? route('home') : url('/') }}" class="flex items-center gap-3 group focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 rounded-xl">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-sky-700 flex items-center justify-center text-white shadow-md shadow-sky-500/20 group-hover:scale-105 transition-transform">
                        <!-- Compass / Target Explorer Icon -->
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 110-16 8 8 0 010 16z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6l3.5 6-3.5 6-3.5-6L12 6z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 group-hover:text-sky-600 transition-colors">
                            Go<span class="text-sky-600">Hunter</span>
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400 tracking-wider uppercase -mt-1 hidden sm:block">
                            Wisata Bebas Getok
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1">
                    <a
                        href="{{ Route::has('destinations.index') ? route('destinations.index') : url('/destinations') }}"
                        class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('destinations.index') || (request()->routeIs('destinations.*') && !request()->routeIs('destinations.price-reports.*')) ? 'bg-sky-50 text-sky-600' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-50' }}"
                    >
                        Destinasi
                    </a>
                    <a
                        href="{{ Route::has('destinations.index') ? route('destinations.index') : url('/destinations') }}"
                        class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('destinations.price-reports.*') || request()->routeIs('price-reports.*') ? 'bg-sky-50 text-sky-600' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-50' }}"
                    >
                        Cek Harga Wajar
                    </a>
                    <a
                        href="{{ Route::has('travel-buddy.index') ? route('travel-buddy.index') : (Route::has('buddy-requests.index') ? route('buddy-requests.index') : url('/travel-buddy')) }}"
                        class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('travel-buddy.*') || request()->routeIs('buddy-requests.*') || request()->routeIs('buddy.*') ? 'bg-sky-50 text-sky-600' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-50' }}"
                    >
                        Teman Jalan
                    </a>
                    <a
                        href="{{ Route::has('forum.index') ? route('forum.index') : (Route::has('threads.index') ? route('threads.index') : url('/forum')) }}"
                        class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('forum.*') || request()->routeIs('threads.*') ? 'bg-sky-50 text-sky-600' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-50' }}"
                    >
                        Diskusi
                    </a>
                </nav>
            </div>

            <!-- Right Actions (CTA, Search & User Profile / Auth) -->
            <div class="hidden sm:flex items-center gap-3">
                <!-- Search Button / Trigger -->
                <button
                    type="button"
                    class="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-500"
                    title="Cari Destinasi atau Harga"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Action Button "+ Lapor / Buat Post" -->
                <a
                    href="{{ Route::has('price-reports.create') ? route('price-reports.create') : url('/price-reports/create') }}"
                    class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm hover:shadow-md transition-all active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Lapor Harga</span>
                </a>

                <!-- Auth & Profile -->
                @auth
                    <div class="relative ml-2" @click.outside="userMenuOpen = false">
                        <button
                            @click="userMenuOpen = !userMenuOpen"
                            type="button"
                            class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-500"
                        >
                            @if(auth()->user()->avatar)
                                <img class="w-9 h-9 rounded-lg object-cover ring-2 ring-sky-500/30" src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}">
                            @else
                                <div class="w-9 h-9 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-sm ring-2 ring-sky-500/20">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="hidden lg:flex flex-col text-left">
                                <span class="text-xs font-bold text-slate-800 line-clamp-1 max-w-[100px]">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] text-amber-600 font-semibold flex items-center gap-0.5">
                                    ★ {{ auth()->user()->reputation_points ?? 0 }} pts
                                </span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            x-show="userMenuOpen"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 divide-y divide-slate-100"
                            style="display: none;"
                        >
                            <div class="px-4 py-2.5">
                                <p class="text-xs text-slate-400 font-medium">Masuk sebagai</p>
                                <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ url('/profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-sky-600 font-medium">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profil Saya
                                </a>
                                <a href="{{ url('/my-reports') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-sky-600 font-medium">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Laporan Saya
                                </a>
                            </div>
                            <div class="py-1">
                                <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2 ml-1">
                        <a
                            href="{{ Route::has('login') ? route('login') : url('/login') }}"
                            class="px-3.5 py-2 text-sm font-semibold text-slate-700 hover:text-sky-600 hover:bg-slate-50 rounded-xl transition-all"
                        >
                            Masuk
                        </a>
                        <a
                            href="{{ Route::has('register') ? route('register') : url('/register') }}"
                            class="px-4 py-2 text-sm font-semibold text-sky-700 bg-sky-50 hover:bg-sky-100 border border-sky-200 rounded-xl transition-all"
                        >
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex items-center gap-2 md:hidden">
                <a
                    href="{{ Route::has('price-reports.create') ? route('price-reports.create') : url('/price-reports/create') }}"
                    class="p-2 text-sky-600 bg-sky-50 rounded-xl hover:bg-sky-100 transition-colors"
                    title="Lapor Harga"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                </a>
                <button
                    @click="mobileOpen = !mobileOpen"
                    type="button"
                    class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-500"
                    aria-label="Toggle Menu"
                >
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden border-b border-slate-100 bg-white px-4 pt-2 pb-6 space-y-3"
        style="display: none;"
    >
        <div class="grid grid-cols-1 gap-1">
            <a
                href="{{ Route::has('destinations.index') ? route('destinations.index') : url('/destinations') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('destinations.index') || (request()->routeIs('destinations.*') && !request()->routeIs('destinations.price-reports.*')) ? 'bg-sky-50 text-sky-600' : 'text-slate-700 hover:bg-slate-50' }}"
            >
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Destinasi
            </a>
            <a
                href="{{ Route::has('destinations.index') ? route('destinations.index') : url('/destinations') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('destinations.price-reports.*') || request()->routeIs('price-reports.*') ? 'bg-sky-50 text-sky-600' : 'text-slate-700 hover:bg-slate-50' }}"
            >
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Cek Harga Wajar
            </a>
            <a
                href="{{ Route::has('travel-buddy.index') ? route('travel-buddy.index') : (Route::has('buddy-requests.index') ? route('buddy-requests.index') : url('/travel-buddy')) }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('travel-buddy.*') || request()->routeIs('buddy-requests.*') || request()->routeIs('buddy.*') ? 'bg-sky-50 text-sky-600' : 'text-slate-700 hover:bg-slate-50' }}"
            >
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Teman Jalan
            </a>
            <a
                href="{{ Route::has('forum.index') ? route('forum.index') : (Route::has('threads.index') ? route('threads.index') : url('/forum')) }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('forum.*') || request()->routeIs('threads.*') ? 'bg-sky-50 text-sky-600' : 'text-slate-700 hover:bg-slate-50' }}"
            >
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                Diskusi
            </a>
        </div>

        <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
            @auth
                <div class="flex items-center gap-3 px-3 py-2">
                    @if(auth()->user()->avatar)
                        <img class="w-10 h-10 rounded-xl object-cover" src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-base">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <div class="font-bold text-slate-900 text-sm">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-amber-600 font-semibold">★ {{ auth()->user()->reputation_points ?? 0 }} Reputation Points</div>
                    </div>
                </div>
                <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2.5 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50">
                        Keluar
                    </button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a
                        href="{{ Route::has('login') ? route('login') : url('/login') }}"
                        class="text-center py-2.5 px-4 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all"
                    >
                        Masuk
                    </a>
                    <a
                        href="{{ Route::has('register') ? route('register') : url('/register') }}"
                        class="text-center py-2.5 px-4 text-sm font-semibold text-white bg-sky-600 hover:bg-sky-700 rounded-xl transition-all shadow-sm"
                    >
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>
