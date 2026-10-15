<x-layouts.app
    :title="$trail->title . ' — Urban Rally'"
    :description="'Panduan timeline transit multi-moda step-by-step: ' . $trail->title . '. Waktu tempuh ' . $trail->estimated_time . ' (' . $trail->total_distance . ').'"
>
    <div
        x-data="{
            completedSteps: [],
            showFinishedModal: false,
            totalSteps: {{ $trail->checkpoints->count() }},
            toggleStep(id) {
                if (this.completedSteps.includes(id)) {
                    this.completedSteps = this.completedSteps.filter(s => s !== id);
                } else {
                    this.completedSteps.push(id);
                    if (this.completedSteps.length === this.totalSteps) {
                        this.showFinishedModal = true;
                    }
                }
            },
            isCompleted(id) {
                return this.completedSteps.includes(id);
            },
            resetProgress() {
                this.completedSteps = [];
                this.showFinishedModal = false;
            },
            markAllCompleted() {
                this.completedSteps = {{ json_encode($trail->checkpoints->pluck('id')) }};
                this.showFinishedModal = true;
            },
            get progressPercent() {
                if (this.totalSteps === 0) return 0;
                return Math.round((this.completedSteps.length / this.totalSteps) * 100);
            }
        }"
        class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-7xl mx-auto space-y-8">
            <!-- Breadcrumbs Navigation -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                <a href="{{ route('home') }}" class="hover:text-sky-600 transition-colors">Beranda</a>
                <span>&rsaquo;</span>
                <a href="{{ route('urban-rally.index') }}" class="hover:text-sky-600 transition-colors">Urban Rally</a>
                <span>&rsaquo;</span>
                <a href="{{ route('urban-rally.index', ['city' => $trail->city->slug]) }}" class="hover:text-sky-600 transition-colors">{{ $trail->city->name }}</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-bold truncate max-w-xs">{{ $trail->title }}</span>
            </nav>

            <!-- Hero Header Section -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/90 relative overflow-hidden">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <div class="space-y-3.5 max-w-3xl">
                        <!-- Top Badges -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <a
                                href="{{ route('urban-rally.index', ['city' => $trail->city->slug]) }}"
                                class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-sky-50 text-sky-700 border border-sky-100 hover:bg-sky-100 transition-colors"
                            >
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $trail->city->name }}
                            </a>

                            @if ($trail->is_recommended)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-black bg-amber-400 text-slate-950 uppercase tracking-wide">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Rute Rekomendasi Utama
                                </span>
                            @endif

                            @if ($trail->destination)
                                <a
                                    href="{{ route('destinations.show', $trail->destination) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors"
                                >
                                    <span>Tujuan: {{ $trail->destination->name }}</span>
                                    <span>&rarr;</span>
                                </a>
                            @endif
                        </div>

                        <!-- Route Title -->
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                            {{ $trail->title }}
                        </h1>

                        <!-- Recommendation Reason Callout -->
                        @if ($trail->recommendation_reason)
                            <div class="p-4 bg-amber-50/80 rounded-2xl border border-amber-200/80 text-xs sm:text-sm text-amber-950 font-medium leading-relaxed flex items-start gap-3">
                                <span class="text-lg">💡</span>
                                <div>
                                    <span class="font-extrabold text-amber-900">Rekomendasi Cerdas:</span>
                                    {{ $trail->recommendation_reason }}
                                </div>
                            </div>
                        @endif

                        <!-- Description -->
                        @if ($trail->description)
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                {{ $trail->description }}
                            </p>
                        @endif

                        <!-- Modes Sequence -->
                        <div class="flex items-center gap-2 flex-wrap pt-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-1">
                                Moda Transit:
                            </span>
                            @foreach ($trail->modes_metadata as $index => $mMeta)
                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold border {{ $mMeta['bg_class'] }}">
                                    {{ $mMeta['label'] }}
                                </span>
                                @if ($index < count($trail->modes_metadata) - 1)
                                    <span class="text-slate-300 font-bold">&rarr;</span>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Route Stats Card -->
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-5 rounded-2xl shadow-md border border-slate-700 shrink-0 w-full lg:w-72 space-y-4">
                        <div class="text-xs font-extrabold text-sky-300 uppercase tracking-wider flex items-center justify-between">
                            <span>Ringkasan Perjalanan</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                <div class="text-[11px] text-slate-400 font-medium">Estimasi Waktu</div>
                                <div class="text-base font-black text-white mt-0.5">{{ $trail->estimated_time }}</div>
                            </div>
                            <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                <div class="text-[11px] text-slate-400 font-medium">Total Jarak</div>
                                <div class="text-base font-black text-white mt-0.5">{{ $trail->total_distance }}</div>
                            </div>
                            <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                <div class="text-[11px] text-slate-400 font-medium">Estimasi Biaya</div>
                                <div class="text-base font-black text-emerald-400 mt-0.5">{{ $trail->estimated_cost ?? 'Hemat' }}</div>
                            </div>
                            <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                <div class="text-[11px] text-slate-400 font-medium">Tahapan Transit</div>
                                <div class="text-base font-black text-sky-400 mt-0.5">{{ $trail->checkpoints->count() }} Checkpoint</div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a
                                href="{{ route('urban-rally.index') }}"
                                class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white transition-all text-center"
                            >
                                <span>&larr;</span>
                                <span>Pilih Rute Lain</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INTERACTIVE CHECKPOINT PROGRESS TRACKER (Alpine.js) -->
            <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-200/90 sticky top-20 z-20 transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-sky-100 text-sky-700 font-black text-xs">
                                <span x-text="completedSteps.length"></span>
                            </span>
                            <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                                Progres Checklist Transit Perjalanan
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500">
                            Centang setiap halte atau stasiun saat Anda melewatinya untuk melacak posisi perjalanan secara langsung.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <div class="text-xs font-bold text-slate-700">
                                <span x-text="completedSteps.length"></span> dari {{ $trail->checkpoints->count() }} selesai
                            </div>
                            <div class="text-xs font-extrabold text-sky-600" x-text="progressPercent + '% Selesai'"></div>
                        </div>
                        <button
                            type="button"
                            @click="markAllCompleted()"
                            x-show="completedSteps.length < totalSteps"
                            class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all"
                        >
                            Tandai Semua
                        </button>
                        <button
                            type="button"
                            @click="resetProgress()"
                            x-show="completedSteps.length > 0"
                            class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold transition-all"
                        >
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Animated Progress Bar -->
                <div class="w-full h-3 bg-slate-100 rounded-full mt-4 overflow-hidden relative">
                    <div
                        class="h-full bg-gradient-to-r from-sky-500 to-emerald-500 rounded-full transition-all duration-500 ease-out"
                        :style="'width: ' + progressPercent + '%'"
                    ></div>
                </div>
            </div>

            <!-- MAIN CONTENT: STEP-BY-STEP TIMELINE & SIDEBAR INFO -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- LEFT COLUMN (2 Cols): TIMELINE BERTINGKAT STEP-BY-STEP -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/90 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <h2 class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-sky-600"></span>
                                Rincian Langkah Perjalanan (Step-by-Step)
                            </h2>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                Urutan Tahapan
                            </span>
                        </div>

                        <!-- Vertical Connected Timeline -->
                        <div class="relative pl-6 sm:pl-8 space-y-8 before:absolute before:left-3 sm:before:left-4 before:top-3 before:bottom-3 before:w-0.5 before:bg-slate-200">
                            @foreach ($trail->checkpoints as $index => $step)
                                <div
                                    class="relative group"
                                    :class="isCompleted({{ $step->id }}) ? 'opacity-90' : ''"
                                >
                                    <!-- Step Circle Marker -->
                                    <div
                                        class="absolute -left-6 sm:-left-8 top-1.5 w-6 h-6 sm:w-8 sm:h-8 rounded-full flex items-center justify-center font-black text-xs transition-all ring-4 ring-white shadow-sm"
                                        :class="isCompleted({{ $step->id }}) ? 'bg-emerald-500 text-white' : 'bg-sky-600 text-white'"
                                    >
                                        <template x-if="isCompleted({{ $step->id }})">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="!isCompleted({{ $step->id }})">
                                            <span>{{ $step->step_order }}</span>
                                        </template>
                                    </div>

                                    <!-- Step Detail Card -->
                                    <div
                                        class="p-5 rounded-2xl border transition-all"
                                        :class="isCompleted({{ $step->id }}) ? 'bg-emerald-50/40 border-emerald-200 shadow-xs' : 'bg-white border-slate-200/90 shadow-sm hover:border-sky-300'"
                                    >
                                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                            <div class="space-y-2">
                                                <!-- Mode Badge & Duration -->
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold border {{ $step->mode_badge_class }}">
                                                        {{ $step->mode_label }}
                                                    </span>
                                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                            <polyline points="12,6 12,12 16,14" stroke-width="2"/>
                                                        </svg>
                                                        {{ $step->duration_minutes }} Menit
                                                    </span>
                                                    @if ($index === 0)
                                                        <span class="text-[11px] font-extrabold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">
                                                            Titik Awal
                                                        </span>
                                                    @elseif ($index === $trail->checkpoints->count() - 1)
                                                        <span class="text-[11px] font-extrabold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                                                            Destinasi Akhir
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Stop Name -->
                                                <h3 class="text-base font-extrabold text-slate-900 leading-snug">
                                                    {{ $step->stop_name }}
                                                </h3>

                                                <!-- Instruction Text -->
                                                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-normal">
                                                    {{ $step->instruction }}
                                                </p>
                                            </div>

                                            <!-- Interactive Checkbox Action Button -->
                                            <div class="shrink-0 pt-1">
                                                <button
                                                    type="button"
                                                    @click="toggleStep({{ $step->id }})"
                                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer select-none"
                                                    :class="isCompleted({{ $step->id }}) ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                                                >
                                                    <template x-if="isCompleted({{ $step->id }})">
                                                        <div class="flex items-center gap-1.5">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            <span>Sudah Lewat</span>
                                                        </div>
                                                    </template>
                                                    <template x-if="!isCompleted({{ $step->id }})">
                                                        <div class="flex items-center gap-1.5">
                                                            <div class="w-3.5 h-3.5 rounded border border-slate-400 bg-white"></div>
                                                            <span>Tandai Terlewati</span>
                                                        </div>
                                                    </template>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN (1 Col): TRANSIT TIPS & DESTINATION SPOTLIGHT -->
                <div class="space-y-6">
                    <!-- Destination Card (if connected) -->
                    @if ($trail->destination)
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/90 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Destinasi Tujuan</span>
                                <span class="text-xs font-semibold text-slate-400">{{ $trail->destination->category }}</span>
                            </div>

                            @if ($trail->destination->cover_image)
                                <img
                                    src="{{ $trail->destination->cover_image }}"
                                    alt="{{ $trail->destination->name }}"
                                    class="w-full h-36 object-cover rounded-2xl shadow-xs"
                                >
                            @endif

                            <h3 class="text-base font-bold text-slate-900 leading-snug">
                                {{ $trail->destination->name }}
                            </h3>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                {{ $trail->destination->short_description ?? $trail->destination->description }}
                            </p>

                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                <div class="text-xs text-slate-500">
                                    Tiket: <span class="font-bold text-slate-800">Rp {{ number_format($trail->destination->official_ticket_price, 0, ',', '.') }}</span>
                                </div>
                                <a
                                    href="{{ route('destinations.show', $trail->destination) }}"
                                    class="text-xs font-bold text-sky-600 hover:text-sky-700"
                                >
                                    Buka Profil Destinasi &rarr;
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Smart Transit Tips Card -->
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-6 text-white shadow-md border border-slate-700 space-y-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center border border-sky-400/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-extrabold text-white tracking-wide">
                                Etika & Tips Transit Cerdas
                            </h3>
                        </div>

                        <ul class="text-xs text-slate-300 space-y-2.5 leading-relaxed">
                            <li class="flex items-start gap-2">
                                <span class="text-sky-400 font-bold">&bull;</span>
                                <span>Pastikan saldo kartu uang elektronik (Flazz/e-Money/TapCash) minimal Rp 15.000 sebelum masuk gerbang.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-sky-400 font-bold">&bull;</span>
                                <span>Dahulukan penumpang yang turun sebelum melangkah masuk ke dalam gerbong MRT, KRL, atau bus TransJakarta.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-sky-400 font-bold">&bull;</span>
                                <span>Gunakan jembatan skybridge integrasi untuk pindah antar moda tanpa perlu membayar ganda atau keluar gate.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-sky-400 font-bold">&bull;</span>
                                <span>Selalu berdiri di sisi kiri eskalator jika sedang diam, sisi kanan diperuntukkan bagi yang bergegas.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Related Trails in Same City -->
                    @if ($relatedTrails->isNotEmpty())
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/90 space-y-4">
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">
                                Rute Lain di {{ $trail->city->name }}
                            </h3>
                            <div class="space-y-3">
                                @foreach ($relatedTrails as $rel)
                                    <a
                                        href="{{ route('urban-rally.show', $rel) }}"
                                        class="block p-3.5 rounded-2xl bg-slate-50 hover:bg-sky-50/60 border border-slate-100 hover:border-sky-200 transition-all group"
                                    >
                                        <div class="text-xs font-bold text-slate-900 group-hover:text-sky-700 line-clamp-1">
                                            {{ $rel->title }}
                                        </div>
                                        <div class="flex items-center gap-2 mt-1.5 text-[11px] text-slate-500">
                                            <span>⏱ {{ $rel->estimated_time }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $rel->total_distance }}</span>
                                            <span>&bull;</span>
                                            <span class="text-emerald-700 font-bold">{{ $rel->estimated_cost }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- POP-UP CELEBRATION MODAL (ALPINE.JS WHEN 100% CHECKLIST COMPLETED) -->
        <div
            x-show="showFinishedModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div
                @click.away="showFinishedModal = false"
                class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-100 text-center space-y-4 transform transition-all"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
            >
                <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/30 text-2xl animate-bounce">
                    🎉
                </div>

                <div class="space-y-1.5">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">
                        Rute Selesai Dilewati!
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Hebat! Anda telah berhasil menyelesaikan seluruh langkah transit di rute <span class="font-bold text-slate-800">{{ $trail->title }}</span>.
                    </p>
                </div>

                <div class="p-3 bg-emerald-50 rounded-2xl border border-emerald-200 text-xs text-emerald-800 font-semibold">
                    ✅ {{ $trail->checkpoints->count() }} dari {{ $trail->checkpoints->count() }} titik transit terkonfirmasi.
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <button
                        type="button"
                        @click="showFinishedModal = false"
                        class="flex-1 py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all"
                    >
                        Tutup
                    </button>
                    <a
                        href="{{ route('urban-rally.index') }}"
                        class="flex-1 py-2.5 px-4 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold transition-all shadow-sm"
                    >
                        Eksplor Rute Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
