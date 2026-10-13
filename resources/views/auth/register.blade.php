<x-layouts.app title="Daftar Akun Baru — Go Hunter" description="Daftar akun Go Hunter dan dapatkan 10 poin reputasi. Akses panduan wisata terverifikasi, proteksi harga anti-getok, dan teman jalan hemat.">
    <div class="min-h-[calc(100vh-160px)] flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8 bg-slate-50/70">
        <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden grid grid-cols-1 lg:grid-cols-12">
            
            <!-- Visual Left Showcase (Desktop) -->
            <div class="hidden lg:flex lg:col-span-5 relative flex-col justify-between p-10 bg-slate-900 text-white overflow-hidden">
                <!-- Background Scenic Image -->
                <img
                    src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80"
                    alt="Komunitas Penjelajah Go Hunter"
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
                    <div class="mt-4 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 border border-amber-400/30 text-amber-200 text-xs font-semibold backdrop-blur-md">
                        <span>★</span>
                        <span>Bonus +10 Poin Explorer Untukmu</span>
                    </div>
                </div>

                <!-- Features Highlight -->
                <div class="relative z-10 space-y-6 my-auto py-8">
                    <h2 class="text-2xl font-extrabold text-white leading-snug">
                        Mulai Perjalananmu <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-sky-200 to-amber-300">
                            Bersama Ribuan Hunter Lainnya
                        </span>
                    </h2>

                    <div class="space-y-4 text-sm text-slate-300">
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-300 flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-white text-xs uppercase tracking-wider">Proteksi Anti-Getok</p>
                                <p class="text-xs text-slate-300 mt-0.5">Akses info harga wajar warung, parkir, dan sewa jeep berdasarkan nota asli warga lokal.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-sky-500/20 border border-sky-400/30 flex items-center justify-center text-sky-300 flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-white text-xs uppercase tracking-wider">Cari Teman Jalan Hemat</p>
                                <p class="text-xs text-slate-300 mt-0.5">Pasang ajakan trip, patungan biaya transportasi, dan temukan teman petualangan satu frekuensi.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-purple-500/20 border border-purple-400/30 flex items-center justify-center text-purple-300 flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-white text-xs uppercase tracking-wider">Forum Situasi Lapangan</p>
                                <p class="text-xs text-slate-300 mt-0.5">Dapatkan update cuaca, ombak, dan kondisi jalan langsung dari traveler di lokasi.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Tagline -->
                <div class="relative z-10 pt-4 border-t border-white/10 text-xs text-slate-400">
                    &copy; {{ date('Y') }} Go Hunter &bull; Komunitas Wisata Indonesia
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
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-700">
                        Sudah punya akun?
                    </a>
                </div>

                <div class="mb-8">
                    <div class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-600 uppercase tracking-wider mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Registrasi Komunitas
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Gabung Menjadi Hunter 🧭
                    </h1>
                    <p class="mt-2 text-sm text-slate-500">
                        Daftar gratis dalam 1 menit dan nikmati seluruh kemudahan eksplorasi wisata nusantara.
                    </p>
                </div>

                <!-- Global Error Alert -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-sm text-red-700 flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold text-red-800">Harap periksa isian formulir:</p>
                            <p class="mt-0.5">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5" x-data="{ showPassword: false, showConfirmPassword: false }">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Contoh: Rian Anggoro"
                                class="w-full pl-11 pr-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-100' }} text-slate-900 placeholder-slate-400 text-sm transition bg-white"
                            />
                        </div>
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email & No. HP (Grid 2 Kolom di Desktop) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Email <span class="text-red-500">*</span>
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
                                    autocomplete="username"
                                    placeholder="nama@email.com"
                                    class="w-full pl-11 pr-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-100' }} text-slate-900 placeholder-slate-400 text-sm transition bg-white"
                                />
                            </div>
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. HP / WhatsApp (Opsional) -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="phone_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    WhatsApp / HP
                                </label>
                                <span class="text-[11px] text-slate-400 font-medium">Opsional</span>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <input
                                    id="phone_number"
                                    type="text"
                                    name="phone_number"
                                    value="{{ old('phone_number') }}"
                                    placeholder="081234567890"
                                    class="w-full pl-11 pr-4 py-3 rounded-xl border {{ $errors->has('phone_number') ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-100' }} text-slate-900 placeholder-slate-400 text-sm transition bg-white"
                                />
                            </div>
                            @error('phone_number')
                                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password & Password Confirmation (Grid 2 Kolom) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
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
                                    autocomplete="new-password"
                                    placeholder="Min. 8 karakter"
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

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Ulangi Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <input
                                    id="password_confirmation"
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Ulangi kata sandi"
                                    class="w-full pl-11 pr-11 py-3 rounded-xl border {{ $errors->has('password_confirmation') ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-100' }} text-slate-900 placeholder-slate-400 text-sm transition bg-white"
                                />
                                <button
                                    type="button"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                    aria-label="Toggle confirm password visibility"
                                >
                                    <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions Checkbox -->
                    <div class="pt-1">
                        <label class="flex items-start gap-3 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                name="terms"
                                value="1"
                                required
                                class="w-4 h-4 mt-0.5 rounded border-slate-300 text-sky-600 focus:ring-sky-500 transition"
                            />
                            <span class="text-xs text-slate-600 leading-relaxed">
                                Saya menyetujui <span class="font-semibold text-slate-800">Ketentuan Layanan</span> dan <span class="font-semibold text-slate-800">Kebijakan Privasi</span> Go Hunter, serta bersedia berbagi info wisata yang jujur & bertanggung jawab.
                            </span>
                        </label>
                        @error('terms')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3">
                        <button
                            type="submit"
                            class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-lg shadow-sky-600/25 hover:shadow-sky-600/35 transition-all transform active:scale-[0.99] focus:outline-none focus:ring-4 focus:ring-sky-100"
                        >
                            <span>Daftar Akun Sekarang</span>
                            <span class="px-2 py-0.5 rounded-full bg-sky-500 text-[10px] text-sky-100 font-bold uppercase tracking-wider">+10 Pts</span>
                            <svg class="w-4 h-4 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Switcher to Login -->
                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-600">
                        Sudah punya akun Go Hunter?
                        <a href="{{ route('login') }}" class="font-bold text-sky-600 hover:text-sky-700 hover:underline ml-1">
                            Masuk ke Akun &rarr;
                        </a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>
