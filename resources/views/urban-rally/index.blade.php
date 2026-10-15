<x-layouts.app
    title="Urban Rally — Multi-Modal Transit Trails & Rekomendasi Rute"
    description="Panduan rute transit jelajah kota terpadu memadukan MRT, TransJakarta, KRL Commuter Line, LRT, dan jalan kaki untuk mobilitas hemat dan bebas macet."
>
    <div
        x-data="{
            selectedModes: ['mrt', 'transjakarta', 'krl', 'lrt', 'bus', 'walking'],
            searchQuery: '',
            filterType: 'any',
            toggleMode(mode) {
                if (this.selectedModes.includes(mode)) {
                    if (this.selectedModes.length > 1) {
                        this.selectedModes = this.selectedModes.filter(m => m !== mode);
                    } else {
                        // Keep at least one or allow empty
                        this.selectedModes = this.selectedModes.filter(m => m !== mode);
                    }
                } else {
                    this.selectedModes.push(mode);
                }
            },
            selectAll() {
                this.selectedModes = ['mrt', 'transjakarta', 'krl', 'lrt', 'bus', 'walking'];
            },
            resetFilter() {
                this.selectedModes = ['mrt', 'transjakarta', 'krl', 'lrt', 'bus', 'walking'];
                this.searchQuery = '';
            },
            matches(modes, title, dest) {
                // Search query match
                if (this.searchQuery.trim() !== '') {
                    const q = this.searchQuery.toLowerCase();
                    const titleMatch = title.toLowerCase().includes(q);
                    const destMatch = dest ? dest.toLowerCase().includes(q) : false;
                    if (!titleMatch && !destMatch) return false;
                }
                if (this.selectedModes.length === 0) return false;
                
                // Mode match
                if (this.filterType === 'strict') {
                    return modes.every(m => this.selectedModes.includes(m.toLowerCase()));
                } else {
                    return modes.some(m => this.selectedModes.includes(m.toLowerCase()));
                }
            }
        }"
        class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-7xl mx-auto space-y-8">
            <!-- Hero Header Banner -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-sky-950 to-slate-900 text-white p-8 sm:p-10 shadow-xl border border-slate-800">
                <!-- Background Glow -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-sky-500/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="max-w-2xl space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-sky-500/20 text-sky-300 border border-sky-400/30 text-xs font-bold tracking-wide uppercase">
                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            <span>Modul Urban Rally &bull; Smart Multi-Transit</span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white leading-tight">
                            Jelajah Kota Bebas Macet <br class="hidden sm:inline" />
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-cyan-300 to-blue-200">
                                Rute Multi-Moda Terpadu
                            </span>
                        </h1>
                        <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                            Panduan rute cerdas memadukan MRT, TransJakarta, KRL Commuter Line, LRT, dan jalur pedestrian ramah kaki. Hemat waktu, minim polusi, dan bebas tarif getok kendaraan.
                        </p>
                    </div>

                    <!-- Quick Highlights Counter Card -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 shrink-0">
                        <div class="text-center px-3 py-2">
                            <div class="text-2xl font-black text-sky-400">{{ $trails->count() }}</div>
                            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Rute</div>
                        </div>
                        <div class="text-center px-3 py-2 border-l border-white/10">
                            <div class="text-2xl font-black text-emerald-400">{{ $recommendedTrails->count() }}</div>
                            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Rekomendasi</div>
                        </div>
                        <div class="text-center px-3 py-2 border-l border-white/10 col-span-2 sm:col-span-1">
                            <div class="text-2xl font-black text-amber-400">{{ $cities->count() }}</div>
                            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Kota Aktif</div>
                        </div>
                    </div>
                </div>

                <!-- City Navigation Pills Inside Hero -->
                <div class="relative z-10 mt-8 pt-6 border-t border-white/10 flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-slate-400 mr-2 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Pilih Kota:
                    </span>
                    <a
                        href="{{ route('urban-rally.index', ['city' => 'all']) }}"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ !$activeCity ? 'bg-sky-500 text-white shadow-md shadow-sky-500/30' : 'bg-white/10 text-slate-300 hover:bg-white/20' }}"
                    >
                        Semua Kota ({{ $trails->count() }})
                    </a>
                    @foreach ($cities as $city)
                        <a
                            href="{{ route('urban-rally.index', ['city' => $city->slug]) }}"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeCity && $activeCity->id === $city->id ? 'bg-sky-500 text-white shadow-md shadow-sky-500/30' : 'bg-white/10 text-slate-300 hover:bg-white/20' }}"
                        >
                            {{ $city->name }} ({{ $city->trails_count }})
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Multi-Select Transit Mode Filter Bar -->
            <div id="filter-transportasi" class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 space-y-4 scroll-mt-24">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-sky-600"></span>
                            Filter Kombinasi Moda Transportasi
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Pilih satu atau lebih moda kendaraan yang ingin Anda gunakan. Kartu rute akan menyesuaikan secara real-time.
                        </p>
                    </div>

                    <!-- Search Input Box -->
                    <div class="relative w-full md:w-72">
                        <input
                            type="text"
                            x-model="searchQuery"
                            placeholder="Cari rute atau destinasi..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all text-slate-800 placeholder-slate-400"
                        >
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Badge Multi-Select Buttons -->
                <div class="flex flex-wrap items-center gap-2.5 pt-2 border-t border-slate-100">
                    <!-- MRT -->
                    <button
                        type="button"
                        @click="toggleMode('mrt')"
                        :class="selectedModes.includes('mrt') ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30 ring-2 ring-blue-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200/80'"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer select-none"
                    >
                        <span class="w-2 h-2 rounded-full" :class="selectedModes.includes('mrt') ? 'bg-white' : 'bg-blue-600'"></span>
                        <span>MRT Jakarta</span>
                        <template x-if="selectedModes.includes('mrt')">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </template>
                    </button>

                    <!-- TransJakarta -->
                    <button
                        type="button"
                        @click="toggleMode('transjakarta')"
                        :class="selectedModes.includes('transjakarta') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30 ring-2 ring-indigo-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200/80'"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer select-none"
                    >
                        <span class="w-2 h-2 rounded-full" :class="selectedModes.includes('transjakarta') ? 'bg-white' : 'bg-indigo-600'"></span>
                        <span>TransJakarta (BRT)</span>
                        <template x-if="selectedModes.includes('transjakarta')">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </template>
                    </button>

                    <!-- KRL Commuter Line -->
                    <button
                        type="button"
                        @click="toggleMode('krl')"
                        :class="selectedModes.includes('krl') ? 'bg-red-600 text-white shadow-sm shadow-red-600/30 ring-2 ring-red-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200/80'"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer select-none"
                    >
                        <span class="w-2 h-2 rounded-full" :class="selectedModes.includes('krl') ? 'bg-white' : 'bg-red-600'"></span>
                        <span>KRL Commuter Line</span>
                        <template x-if="selectedModes.includes('krl')">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </template>
                    </button>

                    <!-- LRT -->
                    <button
                        type="button"
                        @click="toggleMode('lrt')"
                        :class="selectedModes.includes('lrt') ? 'bg-amber-600 text-white shadow-sm shadow-amber-600/30 ring-2 ring-amber-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200/80'"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer select-none"
                    >
                        <span class="w-2 h-2 rounded-full" :class="selectedModes.includes('lrt') ? 'bg-white' : 'bg-amber-600'"></span>
                        <span>LRT</span>
                        <template x-if="selectedModes.includes('lrt')">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </template>
                    </button>

                    <!-- Bus Kota -->
                    <button
                        type="button"
                        @click="toggleMode('bus')"
                        :class="selectedModes.includes('bus') ? 'bg-cyan-600 text-white shadow-sm shadow-cyan-600/30 ring-2 ring-cyan-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200/80'"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer select-none"
                    >
                        <span class="w-2 h-2 rounded-full" :class="selectedModes.includes('bus') ? 'bg-white' : 'bg-cyan-600'"></span>
                        <span>Bus Kota / Feeder</span>
                        <template x-if="selectedModes.includes('bus')">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </template>
                    </button>

                    <!-- Jalan Kaki -->
                    <button
                        type="button"
                        @click="toggleMode('walking')"
                        :class="selectedModes.includes('walking') ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30 ring-2 ring-emerald-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200/80'"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer select-none"
                    >
                        <span class="w-2 h-2 rounded-full" :class="selectedModes.includes('walking') ? 'bg-white' : 'bg-emerald-600'"></span>
                        <span>Jalan Kaki</span>
                        <template x-if="selectedModes.includes('walking')">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </template>
                    </button>

                    <!-- Filter Options & Controls -->
                    <div class="ml-auto flex items-center gap-2">
                        <button
                            type="button"
                            @click="selectAll()"
                            class="text-xs font-semibold text-sky-600 hover:text-sky-700 underline px-2 py-1"
                        >
                            Pilih Semua
                        </button>
                        <span class="text-slate-300">|</span>
                        <button
                            type="button"
                            @click="resetFilter()"
                            class="text-xs font-semibold text-slate-500 hover:text-slate-700 px-2 py-1"
                        >
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 1: KARTU REKOMENDASI RUTE POPULER (CERDAS) -->
            <div id="rekomendasi" class="scroll-mt-24 space-y-4">
                @if ($recommendedTrails->isNotEmpty())
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-amber-500 to-amber-400 flex items-center justify-center text-white shadow-sm shadow-amber-500/30">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-black text-slate-900 tracking-tight">
                                    Rekomendasi Rute Cerdas & Populer
                                </h2>
                                <p class="text-xs text-slate-500">
                                    Rute transit teruji paling efektif, hemat waktu, dan bebas kemacetan menuju destinasi tujuan.
                                </p>
                            </div>
                        </div>
                        <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            Paling Efektif
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach ($recommendedTrails as $rTrail)
                            <div
                                x-show="matches({{ json_encode($rTrail->transport_modes) }}, '{{ addslashes($rTrail->title) }}', '{{ addslashes($rTrail->destination?->name ?? '') }}')"
                                x-transition
                                class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md border border-amber-200/90 relative flex flex-col justify-between transition-all group hover:-translate-y-0.5"
                            >
                                <div class="space-y-3">
                                    <!-- Card Header: Target Destination & Badge -->
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black bg-amber-400 text-slate-950 uppercase tracking-wide">
                                                ★ Pilihan Cerdas
                                            </span>
                                            <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                                {{ $rTrail->city->name }}
                                            </span>
                                        </div>
                                        @if ($rTrail->estimated_cost)
                                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                                {{ $rTrail->estimated_cost }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Destination Focus Tag -->
                                    @if ($rTrail->destination)
                                        <div class="text-xs font-extrabold text-sky-700 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>Menuju: {{ $rTrail->destination->name }}</span>
                                        </div>
                                    @endif

                                    <!-- Title -->
                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-sky-600 transition-colors leading-snug">
                                        <a href="{{ route('urban-rally.show', $rTrail) }}">
                                            {{ $rTrail->title }}
                                        </a>
                                    </h3>

                                    <!-- Recommendation Reason Callout -->
                                    @if ($rTrail->recommendation_reason)
                                        <div class="p-3 bg-amber-50/70 rounded-xl border border-amber-200/60 text-xs text-amber-900 leading-relaxed font-medium">
                                            <span class="font-bold text-amber-950">💡 Alasan Efisiensi:</span>
                                            {{ $rTrail->recommendation_reason }}
                                        </div>
                                    @endif

                                    <!-- Transport Modes Sequence Flow -->
                                    <div class="flex items-center gap-1.5 flex-wrap pt-1">
                                        @foreach ($rTrail->modes_metadata as $index => $mMeta)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold border {{ $mMeta['bg_class'] }}">
                                                {{ $mMeta['label'] }}
                                            </span>
                                            @if ($index < count($rTrail->modes_metadata) - 1)
                                                <span class="text-slate-300 text-xs font-bold">&rarr;</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Card Footer: Stats & CTA -->
                                <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3 text-xs text-slate-500 font-semibold">
                                        <span class="flex items-center gap-1" title="Perkiraan Waktu Tempuh">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                <polyline points="12,6 12,12 16,14" stroke-width="2"/>
                                            </svg>
                                            {{ $rTrail->estimated_time }}
                                        </span>
                                        <span class="flex items-center gap-1" title="Total Jarak">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                            {{ $rTrail->total_distance }}
                                        </span>
                                    </div>

                                    <a
                                        href="{{ route('urban-rally.show', $rTrail) }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold text-sky-600 hover:text-sky-700 group-hover:translate-x-0.5 transition-all"
                                    >
                                        <span>Detail Rute</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- SECTION 2: SEMUA RUTE JELAJAH TRANSIT (CATALOG GRID) -->
            <div class="space-y-4 pt-2">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">
                            Katalog Lengkap Rute Urban Rally
                        </h2>
                        <p class="text-xs text-slate-500">
                            Pilih rute untuk melihat rincian langkah transit per halte/stasiun serta peta checkpoint.
                        </p>
                    </div>

                    <div class="text-xs font-semibold text-slate-500">
                        Menampilkan rute transit kota aktif
                    </div>
                </div>

                <!-- Trails Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($trails as $trail)
                        <div
                            x-show="matches({{ json_encode($trail->transport_modes) }}, '{{ addslashes($trail->title) }}', '{{ addslashes($trail->destination?->name ?? '') }}')"
                            x-transition
                            class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md border border-slate-200/90 relative flex flex-col justify-between transition-all group hover:-translate-y-0.5"
                        >
                            <div class="space-y-3">
                                <!-- Top Metadata Badges -->
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="text-[11px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">
                                            {{ $trail->city->name }}
                                        </span>
                                        @if ($trail->is_recommended)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800">
                                                ★ Favorit
                                            </span>
                                        @endif
                                    </div>

                                    @if ($trail->estimated_cost)
                                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                            {{ $trail->estimated_cost }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Destination Reference -->
                                @if ($trail->destination)
                                    <div class="text-xs font-extrabold text-sky-700 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Tujuan: {{ $trail->destination->name }}</span>
                                    </div>
                                @endif

                                <!-- Title -->
                                <h3 class="text-base font-bold text-slate-900 group-hover:text-sky-600 transition-colors leading-snug">
                                    <a href="{{ route('urban-rally.show', $trail) }}">
                                        {{ $trail->title }}
                                    </a>
                                </h3>

                                <!-- Description Snippet -->
                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                    {{ $trail->description ?? $trail->recommendation_reason }}
                                </p>

                                <!-- Transport Modes Badges -->
                                <div class="flex items-center gap-1.5 flex-wrap pt-1">
                                    @foreach ($trail->modes_metadata as $mMeta)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $mMeta['bg_class'] }}">
                                            {{ $mMeta['label'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Footer Section -->
                            <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3 text-xs text-slate-500 font-semibold">
                                    <span class="flex items-center gap-1" title="Waktu Tempuh">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                            <polyline points="12,6 12,12 16,14" stroke-width="2"/>
                                        </svg>
                                        {{ $trail->estimated_time }}
                                    </span>
                                    <span class="flex items-center gap-1" title="Checkpoints">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $trail->checkpoints_count }} transit
                                    </span>
                                </div>

                                <a
                                    href="{{ route('urban-rally.show', $trail) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-sky-50 text-sky-600 group-hover:bg-sky-600 group-hover:text-white text-xs font-bold transition-all shadow-xs"
                                >
                                    <span>Eksplorasi</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Empty State Helper (When Alpine Filter Matches Nothing) -->
            <div
                class="bg-white rounded-2xl p-12 text-center border border-slate-200/80 shadow-sm"
                x-show="false" {{-- Only triggered dynamically or fallback --}}
            >
                <div class="w-16 h-16 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Tidak ada rute yang cocok</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-4">
                    Silakan sesuaikan kembali pilihan moda transportasi atau kata kunci pencarian Anda.
                </p>
                <button
                    type="button"
                    @click="resetFilter()"
                    class="px-4 py-2 rounded-xl bg-sky-600 text-white text-xs font-bold hover:bg-sky-700 shadow-sm transition-all"
                >
                    Reset Filter Moda
                </button>
            </div>
        </div>
    </div>
</x-layouts.app>
