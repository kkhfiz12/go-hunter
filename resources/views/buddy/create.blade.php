<x-layouts.app title="Buat Ajakan Teman Jalan — Go Hunter" description="Buat rencana perjalanan bersama, ajak teman jalan, dan patungan sewa armada lebih hemat.">
    <div class="bg-slate-50 min-h-screen py-8 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
                <a href="{{ url('/') }}" class="hover:text-sky-600 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('buddy-requests.index') }}" class="hover:text-sky-600 transition">Teman Jalan</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold truncate">Buat Ajakan Baru</span>
            </nav>

            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-10 overflow-hidden">
                <!-- Header Title -->
                <div class="border-b border-slate-100 pb-6 mb-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold px-2.5 py-0.5 rounded-full">
                            ★ +5 Poin Reputasi
                        </span>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Inisiator Perjalanan</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Buat Ajakan Perjalanan Bersama
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Publikasikan rencana tripmu, ajak teman jalan baru, dan patungan armada / akomodasi agar lebih hemat.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-sm text-red-700">
                        <div class="font-bold mb-1">Harap periksa form Anda:</div>
                        <ul class="list-disc list-inside space-y-0.5 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('buddy-requests.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Destinasi Tujuan -->
                    <div>
                        <label for="destination_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Destinasi Tujuan <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="destination_id"
                            id="destination_id"
                            required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                        >
                            <option value="">-- Pilih Destinasi Wisata --</option>
                            @foreach ($destinations as $dest)
                                <option value="{{ $dest->id }}" {{ old('destination_id') == $dest->id ? 'selected' : '' }}>
                                    {{ $dest->name }} — {{ $dest->city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Judul Ajakan -->
                    <div>
                        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Judul Rencana / Ajakan <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="{{ old('title') }}"
                            required
                            placeholder="Contoh: Cari 2 Orang Patungan Sewa Jeep Bromo Sunrise & Savana"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                        />
                    </div>

                    <!-- Tanggal Berangkat & Pulang -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="departure_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Tanggal Berangkat <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                name="departure_date"
                                id="departure_date"
                                value="{{ old('departure_date', date('Y-m-d')) }}"
                                required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                            />
                        </div>

                        <div>
                            <label for="return_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Tanggal Pulang <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <input
                                type="date"
                                name="return_date"
                                id="return_date"
                                value="{{ old('return_date') }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                            />
                        </div>
                    </div>

                    <!-- Slot Kursi & Estimasi Bujet -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="slots_needed" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Total Slot / Kursi Dibutuhkan <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                name="slots_needed"
                                id="slots_needed"
                                value="{{ old('slots_needed', 4) }}"
                                min="1"
                                max="50"
                                required
                                placeholder="4"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                            />
                            <span class="text-[11px] text-slate-400 mt-1 block">Termasuk slot inisiator trip.</span>
                        </div>

                        <div>
                            <label for="estimated_budget_per_person" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Estimasi Bujet per Orang (Rp) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative rounded-xl shadow-xs">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <span class="text-sm font-bold text-slate-400">Rp</span>
                                </div>
                                <input
                                    type="number"
                                    name="estimated_budget_per_person"
                                    id="estimated_budget_per_person"
                                    value="{{ old('estimated_budget_per_person') }}"
                                    required
                                    min="0"
                                    step="10000"
                                    placeholder="250000"
                                    class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                                />
                            </div>
                            <span class="text-[11px] text-slate-400 mt-1 block">Contoh: sewa jeep Rp 1.000.000 / 4 org = Rp 250.000</span>
                        </div>
                    </div>

                    <!-- Kontak WhatsApp / Link -->
                    <div>
                        <label for="contact_link" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Kontak WhatsApp / Link Komunikasi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="contact_link"
                            id="contact_link"
                            value="{{ old('contact_link') }}"
                            required
                            placeholder="Contoh: 081234567890 atau https://wa.me/6281234567890"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                        />
                        <span class="text-[11px] text-slate-400 mt-1 block">Traveler lain akan langsung terhubung ke WhatsApp Anda untuk konfirmasi ikut serta.</span>
                    </div>

                    <!-- Rencana Perjalanan / Itinerary & Kriteria -->
                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Rincian Rencana & Kriteria Teman Jalan <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            required
                            placeholder="Jelaskan titik temu (meeting point), pembagian biaya bbm/tol, armada sewa, atau kriteria teman (misal: non-smoking, bisa nyetir gantian, santai)."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                        >{{ old('description') }}</textarea>
                    </div>

                    <!-- Safety Note -->
                    <div class="p-4 rounded-2xl bg-sky-50 border border-sky-100 flex items-start gap-3">
                        <svg class="w-5 h-5 text-sky-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-sky-800 leading-relaxed">
                            <strong>Tips Keamanan:</strong> Sepakati aturan patungan biaya dan titik kumpul di tempat umum terbuka. Jangan pernah mentransfer uang muka penuh kepada orang yang belum terverifikasi reputasinya.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a
                            href="{{ route('buddy-requests.index') }}"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Publikasikan Ajakan Trip</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
