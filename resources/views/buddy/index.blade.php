<x-layouts.app title="Teman Jalan & Cost-Sharing — Go Hunter" description="Temukan rekan seperjalanan untuk patungan sewa kendaraan, penginapan, dan jelajah destinasi impian di seluruh Indonesia.">
    <div class="bg-slate-50 min-h-screen py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero / Header Section -->
            <div class="relative bg-gradient-to-br from-slate-900 via-sky-950 to-slate-900 rounded-3xl p-6 sm:p-10 md:p-12 text-white shadow-xl overflow-hidden mb-8">
                <!-- Decorative background elements -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-sky-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="max-w-2xl space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-500/20 text-sky-300 text-xs font-bold border border-sky-400/30 backdrop-blur-md">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Papan Teman Jalan (Travel Buddy Board)
                        </div>
                        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                            Cari Rekan Jalan, <br class="hidden sm:block" />
                            <span class="text-sky-400">Bagi Ongkos</span> Lebih Hemat.
                        </h1>
                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-xl">
                            Mau sewa jeep Bromo, kapal Labuan Bajo, atau road-trip tapi kurang kuota? Temukan traveler satu frekuensi dan hemat bujet hingga 50%!
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row md:flex-col gap-3 flex-shrink-0">
                        <a
                            href="{{ route('buddy-requests.create') }}"
                            class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-sm shadow-lg shadow-sky-500/30 hover:shadow-sky-500/50 hover:scale-105 transition-all duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>+ Buat Ajakan Patungan</span>
                        </a>

                        <div class="flex items-center justify-center sm:justify-start md:justify-center gap-4 text-xs text-slate-300 pt-1">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Bebas Biaya Admin
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-sky-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Kontak Langsung WA
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horizontal Filter Bar -->
            <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-6 shadow-sm mb-8">
                <form action="{{ route('buddy-requests.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <!-- Search Keyword -->
                        <div>
                            <label for="search" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Cari Kata Kunci
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    name="search"
                                    id="search"
                                    value="{{ $search ?? '' }}"
                                    placeholder="Bromo, Pantai, Sewa Mobil..."
                                    class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                                />
                            </div>
                        </div>

                        <!-- Dropdown Destinasi -->
                        <div>
                            <label for="destination_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Destinasi Tujuan
                            </label>
                            <select
                                name="destination_id"
                                id="destination_id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition font-medium"
                            >
                                <option value="all">Semua Destinasi</option>
                                @foreach ($destinations as $dest)
                                    <option value="{{ $dest->id }}" {{ (string)$selectedDestination === (string)$dest->id ? 'selected' : '' }}>
                                        {{ $dest->name }} ({{ $dest->city }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rentang Bujet Patungan -->
                        <div>
                            <label for="budget_range" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Rentang Bujet Patungan
                            </label>
                            <select
                                name="budget_range"
                                id="budget_range"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition font-medium"
                            >
                                <option value="all" {{ $selectedBudgetRange === 'all' ? 'selected' : '' }}>Semua Bujet</option>
                                <option value="under_250k" {{ $selectedBudgetRange === 'under_250k' ? 'selected' : '' }}>Di bawah Rp 250.000</option>
                                <option value="250k_500k" {{ $selectedBudgetRange === '250k_500k' ? 'selected' : '' }}>Rp 250rb - Rp 500.000</option>
                                <option value="500k_1000k" {{ $selectedBudgetRange === '500k_1000k' ? 'selected' : '' }}>Rp 500rb - Rp 1.000.000</option>
                                <option value="over_1000k" {{ $selectedBudgetRange === 'over_1000k' ? 'selected' : '' }}>Di atas Rp 1.000.000</option>
                            </select>
                        </div>

                        <!-- Status & Action Buttons -->
                        <div>
                            <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Status Slot
                            </label>
                            <div class="flex items-center gap-2">
                                <select
                                    name="status"
                                    id="status"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition font-medium"
                                >
                                    <option value="open" {{ $selectedStatus === 'open' ? 'selected' : '' }}>Masih Buka (Open)</option>
                                    <option value="all" {{ $selectedStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="full" {{ $selectedStatus === 'full' ? 'selected' : '' }}>Sudah Penuh (Full)</option>
                                </select>

                                <button
                                    type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs sm:text-sm font-bold shadow-sm transition flex-shrink-0"
                                >
                                    Cari
                                </button>

                                @if ($search || $selectedDestination !== 'all' || $selectedBudgetRange !== 'all' || $selectedStatus !== 'open')
                                    <a
                                        href="{{ route('buddy-requests.index') }}"
                                        class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition flex-shrink-0"
                                        title="Reset Filter"
                                    >
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Feed Header & Counter -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900">
                        Ajakan Perjalanan Terkini
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Menampilkan {{ $buddyRequests->total() }} rencana trip aktif dari komunitas
                    </p>
                </div>
            </div>

            <!-- Grid Feed of Travel Buddy Cards -->
            @if ($buddyRequests->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($buddyRequests as $buddyRequest)
                        <x-cards.buddy-card :buddyRequest="$buddyRequest" />
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="mt-8">
                    {{ $buddyRequests->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-3xl border border-slate-200/90 p-8 sm:p-12 text-center max-w-xl mx-auto shadow-sm">
                    <div class="w-16 h-16 rounded-3xl bg-sky-50 text-sky-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Ajakan yang Sesuai</h3>
                    <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                        Tidak ada rencana perjalanan yang cocok dengan filter yang kamu pilih. Coba sesuaikan kata kunci atau buat postingan ajakan trip pertamamu!
                    </p>
                    <div class="flex items-center justify-center gap-3">
                        <a
                            href="{{ route('buddy-requests.index') }}"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition"
                        >
                            Reset Filter
                        </a>
                        <a
                            href="{{ route('buddy-requests.create') }}"
                            class="px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs sm:text-sm font-bold shadow-sm transition"
                        >
                            + Buat Ajakan Sekarang
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Floating Action Button (FAB) for Quick Post -->
    <div class="fixed bottom-6 right-6 z-40">
        <a
            href="{{ route('buddy-requests.create') }}"
            class="group inline-flex items-center gap-2 px-5 py-3.5 rounded-full bg-sky-600 hover:bg-sky-500 text-white font-bold text-sm shadow-xl shadow-sky-600/30 hover:shadow-sky-500/50 hover:scale-105 active:scale-95 transition-all duration-200"
            title="Buat Ajakan Patungan Baru"
        >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span class="hidden sm:inline">Buat Ajakan Patungan</span>
            <span class="sm:hidden">Ajakan Baru</span>
        </a>
    </div>
</x-layouts.app>
