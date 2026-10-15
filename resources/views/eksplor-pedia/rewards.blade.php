<x-layouts.app
    title="Katalog Tukar Hadiah & Voucher — Eksplor Pedia"
    description="Tukarkan akumulasi poin XP kuis Go-Hunter dengan tiket masuk cagar budaya, voucher kuliner legendaris, dan merchandise resmi."
>
    <div
        x-data="{
            selectedReward: null,
            confirmModalOpen: false,
            openRedeemModal(reward) {
                this.selectedReward = reward;
                this.confirmModalOpen = true;
            }
        }"
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10"
    >

        <!-- Header & Points Balance Banner -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-200 pb-6">
            <div>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold uppercase">
                    🎁 Reward Hub
                </span>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight mt-1">
                    Katalog Penukaran Hadiah & Voucher
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Gunakan poin XP yang Anda kumpulkan dari kuis sejarah untuk ditukarkan dengan tiket wisata & voucher merchant.
                </p>
            </div>

            <!-- User Point Balance Card -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-5 border border-slate-700 shadow-md min-w-[240px]">
                @auth
                    @php
                        $userPoints = max(auth()->user()->total_points ?? 0, auth()->user()->reputation_points ?? 0);
                    @endphp
                    <div class="text-[11px] uppercase font-bold text-slate-400">Saldo Poin Anda</div>
                    <div class="text-2xl font-black text-amber-400 mt-0.5 flex items-center gap-1.5">
                        <span>★</span>
                        <span>{{ number_format($userPoints) }} XP</span>
                    </div>
                    <div class="text-[11px] text-slate-300 mt-1">
                        Level: <strong class="text-white">{{ auth()->user()->badge_level ?? 'Penjelajah Pemula' }}</strong>
                    </div>
                @else
                    <div class="text-xs text-slate-300">
                        Masuk untuk melihat saldo XP Anda & mulai menukar hadiah!
                    </div>
                    <div class="mt-3 flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold rounded-lg transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-lg transition-colors">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Success Flash Alert with Voucher Code -->
        @if (session('success_redeem'))
            <div class="p-6 rounded-3xl bg-emerald-500 text-white shadow-lg space-y-3 animate-fadeIn">
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-black">Penukaran Berhasil!</h3>
                </div>
                <p class="text-sm text-emerald-100">
                    {{ session('success_redeem')['message'] }}
                </p>
                <div class="p-4 rounded-2xl bg-black/20 border border-white/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="text-[10px] uppercase font-bold tracking-wider text-emerald-200">KODE VOUCHER ANDA</div>
                        <div class="text-xl sm:text-2xl font-black font-mono tracking-widest text-white select-all">
                            {{ session('success_redeem')['code'] }}
                        </div>
                    </div>
                    <div class="text-xs text-emerald-100">
                        Tunjukkan kode ini kepada petugas loket atau kasir merchant rekanan.
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Rewards Grid Catalog -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Koleksi Hadiah Tersedia</h2>
                <span class="text-xs text-slate-500">{{ $rewards->count() }} Pilihan Hadiah</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($rewards as $reward)
                    @php
                        $userPoints = auth()->check() ? max(auth()->user()->total_points ?? 0, auth()->user()->reputation_points ?? 0) : 0;
                        $hasEnough = $userPoints >= $reward->points_required;
                        $isOutOfStock = $reward->stock <= 0;
                    @endphp

                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group">
                        <!-- Thumbnail -->
                        <div class="relative h-48 overflow-hidden bg-slate-100">
                            <img
                                src="{{ $reward->image_url ?: 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&w=600&q=80' }}"
                                alt="{{ $reward->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 to-transparent"></div>

                            <!-- Category Badge -->
                            <div class="absolute top-3.5 left-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white/90 backdrop-blur-md text-slate-900">
                                    {{ $reward->category }}
                                </span>
                            </div>

                            <!-- Stock Badge -->
                            <div class="absolute top-3.5 right-3.5">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold {{ $isOutOfStock ? 'bg-rose-500 text-white' : 'bg-black/60 backdrop-blur-md text-white border border-white/10' }}">
                                    {{ $isOutOfStock ? 'Habis' : 'Sisa ' . $reward->stock . ' Kuota' }}
                                </span>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 group-hover:text-amber-600 transition-colors line-clamp-1">
                                    {{ $reward->title }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $reward->description }}
                                </p>
                            </div>

                            <div class="space-y-3 pt-3 border-t border-slate-100">
                                <div class="flex items-center justify-between">
                                    <div class="text-xs text-slate-400 font-semibold">Biaya Penukaran:</div>
                                    <div class="text-lg font-black text-amber-600 flex items-center gap-1">
                                        <span>★</span>
                                        <span>{{ number_format($reward->points_required) }} XP</span>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                @auth
                                    @if ($isOutOfStock)
                                        <button disabled class="w-full py-2.5 px-4 rounded-xl bg-slate-100 text-slate-400 text-xs font-bold cursor-not-allowed">
                                            Stok Habis
                                        </button>
                                    @elseif (! $hasEnough)
                                        <button disabled class="w-full py-2.5 px-4 rounded-xl bg-slate-100 text-slate-400 text-xs font-bold cursor-not-allowed" title="Poin Anda belum cukup">
                                            Kurang {{ number_format($reward->points_required - $userPoints) }} XP
                                        </button>
                                    @else
                                        <button
                                            type="button"
                                            @click="openRedeemModal({
                                                id: {{ $reward->id }},
                                                title: '{{ addslashes($reward->title) }}',
                                                points: {{ $reward->points_required }},
                                                redeemUrl: '{{ route('eksplor-pedia.redeem', $reward->id) }}'
                                            })"
                                            class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black text-xs shadow-xs transition-all active:scale-[0.98]"
                                        >
                                            Tukar Hadiah Sekarang &rarr;
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="block text-center w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                                        Masuk untuk Menukar
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section: User's Redeemed Vouchers (History) -->
        @auth
            <div class="pt-8 border-t border-slate-200 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Voucher Saya Yang Sudah Ditukarkan</h2>
                        <p class="text-xs text-slate-500">Gunakan voucher ini saat berkunjung ke destinasi atau merchant</p>
                    </div>
                </div>

                @if ($userRedemptions->isEmpty())
                    <div class="p-8 bg-white rounded-3xl border border-slate-200/80 text-center space-y-2">
                        <div class="text-slate-400 text-3xl">🎟️</div>
                        <h4 class="text-sm font-bold text-slate-800">Belum Ada Voucher yang Ditukar</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            Kumpulkan poin XP dengan menjawab kuis edukasi sejarah, lalu tukarkan dengan voucher di atas!
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($userRedemptions as $ur)
                            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <span class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ $ur->reward->category ?? 'Voucher' }}
                                        </span>
                                        <h4 class="text-sm font-bold text-slate-900 mt-1">
                                            {{ $ur->reward->title ?? 'Reward' }}
                                        </h4>
                                    </div>
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                                        Tersedia
                                    </span>
                                </div>

                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center justify-between">
                                    <div>
                                        <div class="text-[10px] text-slate-400 uppercase font-semibold">Kode Voucher:</div>
                                        <div class="text-base font-black font-mono text-slate-900 tracking-wider select-all">
                                            {{ $ur->voucher_code }}
                                        </div>
                                    </div>
                                    <div class="text-[10px] text-slate-400 text-right">
                                        Ditukar:<br>
                                        {{ $ur->redeemed_at ? $ur->redeemed_at->format('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endauth

        <!-- Modal Konfirmasi Penukaran (Alpine.js) -->
        <div
            x-show="confirmModalOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
        >
            <div
                @click.outside="confirmModalOpen = false"
                class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 space-y-5 animate-scaleUp"
            >
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold mx-auto">
                    🎁
                </div>

                <div class="text-center space-y-2">
                    <h3 class="text-lg font-black text-slate-900">Konfirmasi Penukaran Hadiah</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Apakah Anda yakin ingin menukarkan <strong class="text-amber-600" x-text="selectedReward?.points"></strong> XP untuk hadiah:
                    </p>
                    <div class="p-3 rounded-xl bg-slate-50 font-bold text-sm text-slate-800" x-text="selectedReward?.title"></div>
                </div>

                <form :action="selectedReward?.redeemUrl" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <button
                            type="button"
                            @click="confirmModalOpen = false"
                            class="w-full py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="w-full py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs transition-colors shadow-xs"
                        >
                            Ya, Tukar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.app>
