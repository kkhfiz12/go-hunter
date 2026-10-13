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
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col selection:bg-sky-500 selection:text-white">
    <!-- Navbar Component -->
    <x-navigation.navbar />

    <!-- Flash Notifications / Alerts -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="bg-emerald-500 text-white px-4 py-3 shadow-md relative z-40">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm font-medium">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div x-data="{ show: true }" x-show="show" x-transition class="bg-red-500 text-white px-4 py-3 shadow-md relative z-40">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm font-medium">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <!-- Global Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 lg:gap-12">
                <!-- Brand Info -->
                <div class="md:col-span-1 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-sky-600 flex items-center justify-center text-white font-black text-lg shadow-md shadow-sky-600/30">
                            GH
                        </div>
                        <span class="text-xl font-black text-white tracking-tight">Go<span class="text-sky-400">Hunter</span></span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Platform super-app direktori wisata, proteksi transparansi harga anti-getok, dan teman jalan terpercaya di Indonesia.
                    </p>
                    <p class="text-xs text-slate-500">
                        &copy; {{ date('Y') }} Go Hunter. All rights reserved.
                    </p>
                </div>

                <!-- Direktori Wisata -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Eksplorasi</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ url('/destinations?category=alam') }}" class="hover:text-sky-400 transition-colors">Wisata Alam & Gunung</a></li>
                        <li><a href="{{ url('/destinations?category=bahari') }}" class="hover:text-sky-400 transition-colors">Wisata Bahari & Pantai</a></li>
                        <li><a href="{{ url('/destinations?category=kuliner') }}" class="hover:text-sky-400 transition-colors">Wisata Kuliner Lokal</a></li>
                        <li><a href="{{ url('/destinations?category=budaya') }}" class="hover:text-sky-400 transition-colors">Warisan Budaya & Sejarah</a></li>
                    </ul>
                </div>

                <!-- Anti-Getok & Komunitas -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Komunitas</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ url('/price-reports') }}" class="hover:text-sky-400 transition-colors">Indeks Harga Wajar</a></li>
                        <li><a href="{{ url('/price-reports/create') }}" class="hover:text-sky-400 transition-colors">Lapor Biaya / Getok</a></li>
                        <li><a href="{{ url('/buddy-requests') }}" class="hover:text-sky-400 transition-colors">Cari Rekan Jalan (Buddy)</a></li>
                        <li><a href="{{ url('/threads') }}" class="hover:text-sky-400 transition-colors">Forum Tanya Jawab</a></li>
                    </ul>
                </div>

                <!-- Kontak & Misi -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Misi Kami</h3>
                    <p class="text-sm text-slate-400 leading-relaxed mb-4">
                        Mendukung pariwisata sehat dan ramah bagi semua traveler lewat transparansi informasi berbasis warga.
                    </p>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-800 text-sky-400 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Info Nyata Tanpa Tipu
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
