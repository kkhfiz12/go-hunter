@props(['title' => null, 'description' => null, 'sidebarContext' => null, 'hideFooter' => false])
@php
    $currentModule = 'dashboard';
    if (request()->routeIs('tripy.*')) {
        $currentModule = 'tripy';
    } elseif (request()->routeIs('eksplor-pedia.*')) {
        $currentModule = 'eksplor_pedia';
    } elseif (request()->routeIs('urban-rally.*')) {
        $currentModule = 'urban_rally';
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — Go Hunter' : 'Go Hunter — Jelajah Tanpa Ragu, Info Nyata Tanpa Tipu' }}</title>
    <meta name="description" content="{{ $description ?? 'Platform terpadu panduan logistik independen, proteksi transparansi harga anti-getok, forum Q&A situasional, serta pencarian travel buddy di Indonesia.' }}">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%230284C7'><circle cx='12' cy='12' r='10'/><polygon points='12,6 15.5,12 12,18 8.5,12' fill='white'/></svg>">

    <!-- Google Fonts: Plus Jakarta Sans & Caveat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body
    x-data="{ activeModule: '{{ $currentModule }}', mobileSidebarOpen: false }"
    class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col selection:bg-sky-500 selection:text-white"
>
    <!-- App Container with Left Sidebar on Desktop -->
    <div class="flex-1 flex flex-col lg:flex-row min-h-screen w-full">
        <!-- 1. DYNAMIC SIDEBAR (AREA MERAH) - DESKTOP -->
        <div class="hidden lg:block shrink-0">
            <x-navigation.sidebar :contextNav="$sidebarContext" />
        </div>

        <!-- Mobile Drawer Backdrop & Slide-over Sidebar -->
        <div
            x-show="mobileSidebarOpen"
            x-cloak
            class="fixed inset-0 z-50 lg:hidden flex"
        >
            <div
                x-show="mobileSidebarOpen"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="mobileSidebarOpen = false"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"
            ></div>

            <div
                x-show="mobileSidebarOpen"
                x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="relative flex-1 flex flex-col max-w-xs w-full bg-white shadow-xl z-10"
            >
                <div class="absolute top-3 right-3 z-20">
                    <button
                        @click="mobileSidebarOpen = false"
                        type="button"
                        class="p-2 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100"
                        aria-label="Tutup Menu"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="h-full overflow-hidden">
                    <x-navigation.sidebar :contextNav="$sidebarContext" />
                </div>
            </div>
        </div>

        <!-- MAIN WRAPPER (TOP NAVBAR + KONTEN UTAMA) -->
        <div class="flex-1 flex flex-col min-w-0 bg-slate-50 min-h-screen">
            <!-- 2. TOP NAVBAR (AREA HIJAU) -->
            <header class="bg-white border-b border-slate-200/90 px-4 sm:px-6 py-2.5 flex items-center justify-between shrink-0 sticky top-0 z-40 shadow-xs">
                <!-- Left: Module Category Navigation Tabs -->
                <nav class="flex items-center space-x-1 sm:space-x-4 overflow-x-auto scrollbar-none py-1">
                    <!-- Dashboard Tab -->
                    <a
                        href="{{ route('dashboard') }}"
                        @click="activeModule = 'dashboard'"
                        :class="activeModule === 'dashboard' ? 'border-sky-600 text-sky-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 font-semibold'"
                        class="pb-2 border-b-2 text-sm sm:text-base px-2.5 sm:px-3 transition-colors whitespace-nowrap cursor-pointer select-none inline-block {{ $currentModule === 'dashboard' ? 'border-sky-600 text-sky-600 font-bold' : '' }}"
                    >
                        Dashboard
                    </a>

                    <!-- TRIPY Tab -->
                    <a
                        href="{{ route('tripy.index') }}"
                        @click="activeModule = 'tripy'"
                        :class="activeModule === 'tripy' ? 'border-sky-600 text-sky-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 font-semibold'"
                        class="pb-2 border-b-2 text-sm sm:text-base px-2.5 sm:px-3 transition-colors whitespace-nowrap cursor-pointer select-none inline-block {{ $currentModule === 'tripy' ? 'border-sky-600 text-sky-600 font-bold' : '' }}"
                    >
                        TRIPY
                    </a>

                    <!-- Eksplor Pedia Tab -->
                    <a
                        href="{{ route('eksplor-pedia.index') }}"
                        @click="activeModule = 'eksplor_pedia'"
                        :class="activeModule === 'eksplor_pedia' ? 'border-sky-600 text-sky-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 font-semibold'"
                        class="pb-2 border-b-2 text-sm sm:text-base px-2.5 sm:px-3 transition-colors whitespace-nowrap cursor-pointer select-none inline-block {{ $currentModule === 'eksplor_pedia' ? 'border-sky-600 text-sky-600 font-bold' : '' }}"
                    >
                        Eksplor Pedia
                    </a>

                    <!-- Urban Rally Tab -->
                    <a
                        href="{{ route('urban-rally.index') }}"
                        @click="activeModule = 'urban_rally'"
                        :class="activeModule === 'urban_rally' ? 'border-sky-600 text-sky-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 font-semibold'"
                        class="pb-2 border-b-2 text-sm sm:text-base px-2.5 sm:px-3 transition-colors whitespace-nowrap cursor-pointer select-none inline-block {{ $currentModule === 'urban_rally' ? 'border-sky-600 text-sky-600 font-bold' : '' }}"
                    >
                        Urban Rally
                    </a>
                </nav>

                <!-- Right: Quick Actions & Auth Controls -->
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <!-- Quick Action Button -->
                    <a
                        href="{{ route('destinations.index', ['sort' => 'cheapest']) }}"
                        class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-sky-700 bg-sky-50 hover:bg-sky-100 border border-sky-100 transition-all shadow-xs"
                    >
                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Cek Harga</span>
                    </a>

                    @auth
                        <div class="flex items-center gap-2 pl-1 border-l border-slate-200">
                            <div class="text-right hidden sm:block">
                                <div class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</div>
                                <div class="text-[10px] text-amber-600 font-semibold">★ {{ max(auth()->user()->total_points ?? 0, auth()->user()->reputation_points ?? 0) }} XP</div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all"
                                    title="Keluar"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <a
                                href="{{ route('login') }}"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 transition-all"
                            >
                                Masuk
                            </a>
                            <a
                                href="{{ route('register') }}"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold text-white bg-sky-600 hover:bg-sky-700 transition-all shadow-xs"
                            >
                                Daftar
                            </a>
                        </div>
                    @endauth

                    <!-- Mobile Sidebar Trigger Button -->
                    <button
                        @click="mobileSidebarOpen = true"
                        type="button"
                        class="lg:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors focus:outline-none"
                        aria-label="Buka Menu Samping"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Flash Notifications / Alerts -->
            @if (session()->has('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="bg-emerald-500 text-white px-4 py-3 shadow-md relative z-30">
                    <div class="max-w-7xl mx-auto flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-100 hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="bg-red-500 text-white px-4 py-3 shadow-md relative z-30">
                    <div class="max-w-7xl mx-auto flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-medium">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-100 hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Main Content Slot -->
            <main class="flex-1 w-full">
                {{ $slot }}
            </main>

            <!-- Global Footer (Hidden on interactive map screens if requested) -->
            @unless($hideFooter)
                <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-auto">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                            <!-- Brand Info -->
                            <div class="md:col-span-1 space-y-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-sky-600 flex items-center justify-center text-white font-black text-sm shadow-md shadow-sky-600/30">
                                        GH
                                    </div>
                                    <span class="text-lg font-black text-white tracking-tight">Go<span class="text-sky-400">Hunter</span></span>
                                </div>
                                <p class="text-xs text-slate-400 leading-relaxed">
                                    Platform super-app direktori wisata, proteksi transparansi harga anti-getok, dan petualangan cerdas di Indonesia.
                                </p>
                                <p class="text-[11px] text-slate-500">
                                    &copy; {{ date('Y') }} Go Hunter. All rights reserved.
                                </p>
                            </div>

                            <!-- Direktori Wisata -->
                            <div>
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-3">Eksplorasi</h3>
                                <ul class="space-y-2 text-xs">
                                    <li><a href="{{ route('tripy.index') }}" class="text-sky-400 font-semibold hover:text-sky-300 transition-colors">Peta Interaktif TRIPY &rarr;</a></li>
                                    <li><a href="{{ url('/destinations?category=alam') }}" class="hover:text-sky-400 transition-colors">Wisata Alam & Gunung</a></li>
                                    <li><a href="{{ url('/destinations?category=bahari') }}" class="hover:text-sky-400 transition-colors">Wisata Bahari & Pantai</a></li>
                                    <li><a href="{{ url('/destinations?category=budaya') }}" class="hover:text-sky-400 transition-colors">Warisan Budaya & Sejarah</a></li>
                                </ul>
                            </div>

                            <!-- Anti-Getok & Komunitas -->
                            <div>
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-3">Komunitas</h3>
                                <ul class="space-y-2 text-xs">
                                    <li><a href="{{ url('/destinations') }}" class="hover:text-sky-400 transition-colors">Cek Harga Wajar</a></li>
                                    <li><a href="{{ url('/price-reports/create') }}" class="hover:text-sky-400 transition-colors">Lapor Biaya / Getok</a></li>
                                    <li><a href="{{ route('travel-buddy.index') }}" class="hover:text-sky-400 transition-colors">Teman Jalan (Buddy)</a></li>
                                    <li><a href="{{ route('forum.index') }}" class="hover:text-sky-400 transition-colors">Forum Diskusi</a></li>
                                </ul>
                            </div>

                            <!-- Misi -->
                            <div>
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-3">Misi Transparansi</h3>
                                <p class="text-xs text-slate-400 leading-relaxed mb-3">
                                    Mendukung pariwisata sehat dan ramah bagi semua traveler lewat transparansi informasi berbasis warga.
                                </p>
                                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-slate-800 text-sky-400 text-[11px] font-semibold">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                    Info Nyata Tanpa Tipu
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            @endunless
        </div>
    </div>

    @stack('scripts')
</body>
</html>
