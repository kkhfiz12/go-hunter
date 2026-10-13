<x-layouts.app title="Masuk ke Akun — Go Hunter" description="Masuk ke akun Go Hunter Anda untuk memantau harga anti-getok, mencari travel buddy, dan berdiskusi di komunitas wisata Indonesia.">
    <div class="min-h-[calc(100vh-160px)] flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8 bg-slate-50/70">
        <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden grid grid-cols-1 lg:grid-cols-12">
            
            <!-- Visual Left Showcase (Desktop) -->
            <div class="hidden lg:flex lg:col-span-5 relative flex-col justify-between p-10 bg-slate-900 text-white overflow-hidden">
                <!-- Background Scenic Image -->
                <img
                    src="https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=1200&q=80"
                    alt="Petualangan Alam Go Hunter"
                    class="absolute inset-0 w-full h-full object-cover opacity-35"
                />
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/80 to-sky-950/60"></div>

                <!-- Brand Header -->
                <div class="relative z-10">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-sky-600 flex items-center justify-center text-white font-black text-lg shadow-lg shadow-sky-600/30 group-hover:scale-105 transition-transform">
                            GH
                        </div>
                        <span class="text-2xl font-black tracking-tight text-white">Go<span class="text-sky-400">Hunter</span></span>
                    </a>
                    <div class="mt-4 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-500/20 border border-sky-400/30 text-sky-200 text-xs font-semibold backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Super-App Wisata Transparan
                    </div>
                </div>

                <!-- Highlights & Testimonial Feature -->
                <div class="relative z-10 space-y-6 my-auto py-8">
                    <h2 class="text-2xl font-extrabold text-white leading-snug">
                        Jelajah Indonesia <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-sky-200 to-amber-300">
                            Tanpa Was-Was Getok Harga
                        </span>
                    </h2>
                    
                    <ul class="space-y-3.5 text-sm text-slate-300">
                        <li class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-300 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>Index harga wajar warung, tiket & parkir resmi</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-sky-500/20 border border-sky-400/30 flex items-center justify-center text-sky-300 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <span>Cari travel buddy terpercaya buat patungan</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-amber-500/20 border border-amber-400/30 flex items-center justify-center text-amber-300 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </div>
                            <span>Kumpulkan poin reputasi penjelajah Indonesia</span>
                        </li>
                    </ul>
                </div>

                <!-- Footer Tagline -->
                <div class="relative z-10 pt-4 border-t border-white/10 text-xs text-slate-400">
                    &copy; {{ date('Y') }} Go Hunter &bull; Komunitas Traveler Cerdas
                </div>
            </div>

            <!-- Form Right Side -->
            <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center">
                <!-- Mobile Brand Header -->
                <div class="lg:hidden mb-6 flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-xl bg-sky-600 flex items-center justify-center text-white font-black text-base shadow-md shadow-sky-600/30">
                            GH
                        </div>
                        <span class="text-xl font-black text-slate-900 tracking-tight">Go<span class="text-sky-600">Hunter</span></span>
                    </a>
                    <a href="{{ route('register') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-700">
                        Belum punya akun?
                    </a>
                </div>

                <div class="mb-8">
                    <div class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-600 uppercase tracking-wider mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Portal Masuk
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Selamat Datang Kembali! 👋
                    </h1>
                    <p class="mt-2 text-sm text-slate-500">
                        Masuk untuk akses fitur penuh: lapor harga, cari teman jalan, dan bergabung dalam diskusi.
                    </p>
                </div>

                <!-- Global Error Alert -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-sm text-red-700 flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold text-red-800">Gagal masuk:</p>
                            <p class="mt-0.5">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false }">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                                </svg>
                            </div>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="nama@email.com"
                                class="w-full pl-11 pr-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-100' }} text-slate-900 placeholder-slate-400 text-sm transition bg-white"
                            />
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Kata Sandi
                            </label>
                            <span class="text-xs text-slate-400">
                                Min. 8 karakter
                            </span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi akun"
                                class="w-full pl-11 pr-11 py-3 rounded-xl border {{ $errors->has('password') ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-100' }} text-slate-900 placeholder-slate-400 text-sm transition bg-white"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                aria-label="Toggle password visibility"
                            >
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                class="w-4 h-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500 transition"
                            />
                            <span class="text-xs sm:text-sm font-medium text-slate-600">Ingat Saya</span>
                        </label>
                        <span class="text-xs text-slate-400">
                            Aman & Terenkripsi
                        </span>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-lg shadow-sky-600/25 hover:shadow-sky-600/35 transition-all transform active:scale-[0.99] focus:outline-none focus:ring-4 focus:ring-sky-100"
                        >
                            <span>Masuk Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Switcher to Register -->
                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-600">
                        Belum punya akun Go Hunter?
                        <a href="{{ route('register') }}" class="font-bold text-sky-600 hover:text-sky-700 hover:underline ml-1">
                            Daftar Gratis di Sini &rarr;
                        </a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>
