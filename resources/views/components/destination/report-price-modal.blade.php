@props([
    'destination',
])

<div
    x-data="{
        open: false,
        fairness: 'fair',
        fileName: '',
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.fileName = file.name;
            } else {
                this.fileName = '';
            }
        }
    }"
    @open-report-price-modal.window="open = true"
    @keydown.escape.window="open = false"
    x-cloak
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop Overlay -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"
    ></div>

    <!-- Modal Dialog Scroll Wrapper -->
    <div
        x-show="open"
        class="fixed inset-0 z-10 w-screen overflow-y-auto p-4 sm:p-6 md:p-10 flex items-center justify-center"
    >
        <div
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.away="open = false"
            class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all"
        >
            <!-- Header -->
            <div class="px-6 pt-6 pb-4 border-b border-slate-100 bg-gradient-to-r from-sky-50/50 via-white to-amber-50/30">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-bold px-2 py-0.5 rounded-full">
                                <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                +5 Poin Reputasi
                            </span>
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Crowdsource Price</span>
                        </div>
                        <h3 id="modal-title" class="text-lg sm:text-xl font-bold text-slate-900 mt-1">
                            Laporkan Biaya Riil (Anti-Getok)
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">
                            Destinasi: <span class="font-semibold text-slate-700">{{ $destination->name }}</span>
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="open = false"
                        class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-1.5 rounded-xl transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form
                action="{{ route('destinations.price-reports.store', $destination) }}"
                method="POST"
                enctype="multipart/form-data"
                class="p-6 space-y-4"
            >
                @csrf

                <!-- Nama Item / Layanan -->
                <div>
                    <label for="item_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Barang / Layanan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="item_name"
                        id="item_name"
                        required
                        placeholder="Contoh: Kelapa Muda, Sewa Tikar, Parkir Mobil Liar"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                    />
                </div>

                <!-- Biaya Dibayar -->
                <div>
                    <label for="price_paid" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Biaya yang Dibayar (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="text-sm font-bold text-slate-400">Rp</span>
                        </div>
                        <input
                            type="number"
                            name="price_paid"
                            id="price_paid"
                            required
                            min="0"
                            step="500"
                            placeholder="15000"
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                        />
                    </div>
                </div>

                <!-- Radio Button Tingkat Kewajaran -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tingkat Kewajaran Harga <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2.5">
                        <!-- Cheap -->
                        <label
                            @click="fairness = 'cheap'"
                            :class="fairness === 'cheap' ? 'border-fairness-fair bg-fairness-fair-bg text-fairness-fair ring-2 ring-fairness-fair/20' : 'border-slate-200 hover:border-slate-300 text-slate-700 bg-white'"
                            class="flex flex-col items-center justify-center p-3 rounded-2xl border cursor-pointer transition text-center select-none"
                        >
                            <input
                                type="radio"
                                name="fairness_level"
                                value="cheap"
                                x-model="fairness"
                                class="sr-only"
                            />
                            <svg class="w-5 h-5 mb-1" :class="fairness === 'cheap' ? 'text-fairness-fair' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                            <span class="text-xs font-bold block">Murah</span>
                            <span class="text-[10px] text-slate-500 mt-0.5">Di bawah rata-rata</span>
                        </label>

                        <!-- Fair -->
                        <label
                            @click="fairness = 'fair'"
                            :class="fairness === 'fair' ? 'border-fairness-fair bg-fairness-fair-bg text-fairness-fair ring-2 ring-fairness-fair/20' : 'border-slate-200 hover:border-slate-300 text-slate-700 bg-white'"
                            class="flex flex-col items-center justify-center p-3 rounded-2xl border cursor-pointer transition text-center select-none"
                        >
                            <input
                                type="radio"
                                name="fairness_level"
                                value="fair"
                                x-model="fairness"
                                class="sr-only"
                            />
                            <svg class="w-5 h-5 mb-1" :class="fairness === 'fair' ? 'text-fairness-fair' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs font-bold block">Wajar</span>
                            <span class="text-[10px] text-slate-500 mt-0.5">Sesuai standar</span>
                        </label>

                        <!-- Overpriced (Getok) -->
                        <label
                            @click="fairness = 'overpriced'"
                            :class="fairness === 'overpriced' ? 'border-fairness-getok bg-fairness-getok-bg text-fairness-getok ring-2 ring-fairness-getok/20' : 'border-slate-200 hover:border-slate-300 text-slate-700 bg-white'"
                            class="flex flex-col items-center justify-center p-3 rounded-2xl border cursor-pointer transition text-center select-none"
                        >
                            <input
                                type="radio"
                                name="fairness_level"
                                value="overpriced"
                                x-model="fairness"
                                class="sr-only"
                            />
                            <svg class="w-5 h-5 mb-1" :class="fairness === 'overpriced' ? 'text-fairness-getok' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="text-xs font-bold block">Getok!</span>
                            <span class="text-[10px] text-slate-500 mt-0.5">Kemahalan / tipu</span>
                        </label>
                    </div>
                </div>

                <!-- Upload Bukti Struk/Nota (Opsional) -->
                <div>
                    <label for="proof_image" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Bukti Nota / Struk / Menu (Opsional)
                    </label>
                    <div class="relative">
                        <input
                            type="file"
                            name="proof_image"
                            id="proof_image"
                            accept="image/jpeg,image/png,image/webp"
                            @change="handleFileChange($event)"
                            class="sr-only"
                        />
                        <label
                            for="proof_image"
                            class="flex items-center justify-between px-4 py-2.5 bg-slate-50 hover:bg-slate-100 border border-dashed border-slate-300 rounded-xl cursor-pointer transition text-xs"
                        >
                            <div class="flex items-center gap-2 truncate">
                                <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span x-text="fileName || 'Pilih foto struk / nota (JPG, PNG maks 4MB)'" class="text-slate-600 truncate"></span>
                            </div>
                            <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[11px] font-semibold text-slate-700 flex-shrink-0 shadow-2xs">
                                Browse
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Catatan Tambahan (Opsional) -->
                <div>
                    <label for="notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Catatan Lokasi / Tips (Opsional)
                    </label>
                    <textarea
                        name="notes"
                        id="notes"
                        rows="2"
                        placeholder="Contoh: Lokasi di dekat pintu masuk 2, disarankan tanya tarif parkir terlebih dahulu."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                    ></textarea>
                </div>

                <!-- Footer Buttons -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button
                        type="button"
                        @click="open = false"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs sm:text-sm font-semibold shadow-sm hover:shadow-md transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Kirim Laporan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
