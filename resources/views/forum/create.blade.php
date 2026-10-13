<x-layouts.app title="Buat Pertanyaan Baru — Forum Go Hunter" description="Tanyakan situasi kondisi jalur, cuaca, dan tips wisata langsung kepada sesama traveler dan warga lokal.">
    <div class="bg-slate-50 min-h-screen py-8 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
                <a href="{{ url('/') }}" class="hover:text-sky-600 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('threads.index') }}" class="hover:text-sky-600 transition">Forum Diskusi</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold truncate">Buat Pertanyaan</span>
            </nav>

            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-10 overflow-hidden">
                <!-- Header Title -->
                <div class="border-b border-slate-100 pb-6 mb-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold px-2.5 py-0.5 rounded-full">
                            ★ +5 Poin Reputasi
                        </span>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Komunitas Q&A</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Tanyakan Situasi / Kondisi Terkini
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Dapatkan jawaban nyata dari warga lokal dan traveler yang baru saja berkunjung ke lokasi.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-sm text-red-700">
                        <div class="font-bold mb-1">Periksa kembali data Anda:</div>
                        <ul class="list-disc list-inside space-y-0.5 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('threads.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Judul Pertanyaan -->
                    <div>
                        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Judul Pertanyaan / Topik <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="{{ old('title') }}"
                            required
                            placeholder="Contoh: Apakah jalur Bromo via Tosari aman dilalui motor matic saat musim hujan?"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                        />
                    </div>

                    <!-- Kategori & Destinasi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Kategori Topik <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="category"
                                id="category"
                                required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                            >
                                <option value="weather_update" {{ old('category') === 'weather_update' ? 'selected' : '' }}>Kondisi Terkini (Cuaca / Bencana / Keramaian)</option>
                                <option value="traffic_transit" {{ old('category') === 'traffic_transit' ? 'selected' : '' }}>Transportasi & Jalur (Angkot / Jalan Rusak / Rute)</option>
                                <option value="tips" {{ old('category') === 'tips' ? 'selected' : '' }}>Tips & Trik (Hemat / Jam Terbaik / Larangan)</option>
                                <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>Diskusi Umum</option>
                            </select>
                        </div>

                        <div>
                            <label for="destination_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Terkait Destinasi <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <select
                                name="destination_id"
                                id="destination_id"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                            >
                                <option value="">-- Pilih Destinasi (Opsional) --</option>
                                @foreach ($destinations as $dest)
                                    <option value="{{ $dest->id }}" {{ old('destination_id') == $dest->id ? 'selected' : '' }}>
                                        {{ $dest->name }} ({{ $dest->city }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Isi Lengkap Pertanyaan -->
                    <div>
                        <label for="content" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Rincian Pertanyaan <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="content"
                            id="content"
                            rows="5"
                            required
                            placeholder="Jelaskan detail kendala atau informasi yang ingin kamu tanyakan. Cantumkan perkiraan tanggal rencana keberangkatan agar jawaban lebih akurat."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition leading-relaxed"
                        >{{ old('content') }}</textarea>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a
                            href="{{ route('threads.index') }}"
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
                            <span>Kirim Pertanyaan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
