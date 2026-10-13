@props([
    'thread',
])

@php
    $user = $thread->user;
    $destination = $thread->destination;
    $commentsCount = $thread->comments_count ?? ($thread->relationLoaded('comments') ? $thread->comments->count() : $thread->comments()->count());
    $hasSolution = $thread->has_solution;
    $initialUpvotes = $thread->estimated_upvotes ?? max(1, (int) round(($thread->views_count / 15) + ($commentsCount * 2)));
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-6 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 flex gap-4 sm:gap-5 relative group overflow-hidden']) }}>
    <!-- Sisi Kiri: Tombol Upvote Counter dengan interaksi Alpine.js (Model Reddit/StackOverflow) -->
    <div
        x-data="{
            upvoted: false,
            count: {{ $initialUpvotes }},
            toggle() {
                this.upvoted = !this.upvoted;
                this.count += (this.upvoted ? 1 : -1);
            }
        }"
        class="flex flex-col items-center justify-start flex-shrink-0"
    >
        <div class="flex flex-col items-center p-2 rounded-2xl bg-slate-50 border border-slate-100 group-hover:border-slate-200 transition-colors w-11 sm:w-12 text-center">
            <!-- Upvote Button -->
            <button
                type="button"
                @click="toggle()"
                :class="upvoted ? 'text-sky-600 bg-sky-100 scale-110 shadow-xs' : 'text-slate-400 hover:text-sky-600 hover:bg-slate-200/60'"
                class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl flex items-center justify-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-sky-500/40"
                title="Bantu upvote diskusi ini"
                aria-label="Upvote thread"
            >
                <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform" :class="upvoted ? '-translate-y-0.5' : ''" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
            </button>

            <!-- Counter -->
            <span
                x-text="count"
                :class="upvoted ? 'text-sky-600 font-black' : 'text-slate-700 font-bold'"
                class="text-xs sm:text-sm mt-1 transition-colors"
            >
                {{ $initialUpvotes }}
            </span>

            <span class="text-[9px] uppercase tracking-wider text-slate-400 font-medium">
                Vote
            </span>
        </div>
    </div>

    <!-- Sisi Kanan: Konten Thread -->
    <div class="flex-1 min-w-0 flex flex-col justify-between">
        <div>
            <!-- Header Badges & Meta -->
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <!-- Kategori Pill -->
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border ring-1 ring-inset {{ $thread->category_badge_class }}">
                    {{ $thread->category_label }}
                </span>

                <!-- Badge Solusi Terverifikasi / Warga Lokal -->
                @if ($hasSolution)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 ring-1 ring-emerald-600/20 shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Terkonfirmasi Warga Lokal
                    </span>
                @endif

                <!-- Destinasi Terkait (jika ada) -->
                @if ($destination)
                    <a
                        href="{{ route('destinations.show', $destination->slug) }}"
                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-500 hover:text-sky-600 transition"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        <span>{{ $destination->name }}</span>
                    </a>
                @endif
            </div>

            <!-- Judul Diskusi Tebal -->
            <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-sky-600 transition leading-snug">
                <a href="{{ route('threads.show', $thread->slug) }}" class="focus:outline-none">
                    {{ $thread->title }}
                </a>
            </h3>

            <!-- Preview Teks 2 Baris -->
            <p class="text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed mt-1.5">
                {{ $thread->content }}
            </p>
        </div>

        <!-- Meta Footer: Author, Waktu, Counter Komentar & Views -->
        <div class="flex flex-wrap items-center justify-between gap-3 mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500">
            <!-- Author & Time -->
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-800">
                    {{ $user->name ?? 'Traveler Hunter' }}
                </span>
                <span class="text-slate-300">•</span>
                <span>
                    {{ $thread->created_at ? $thread->created_at->diffForHumans() : 'baru saja' }}
                </span>
            </div>

            <!-- Stats Counters & CTA -->
            <div class="flex items-center gap-4">
                <!-- Komentar Counter -->
                <span class="flex items-center gap-1.5 font-semibold {{ $commentsCount > 0 ? 'text-sky-600' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>{{ $commentsCount }} Jawaban</span>
                </span>

                <!-- Views Count -->
                <span class="hidden sm:flex items-center gap-1 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>{{ number_format($thread->views_count) }} views</span>
                </span>

                <a
                    href="{{ route('threads.show', $thread->slug) }}"
                    class="font-bold text-sky-600 hover:text-sky-700 transition flex items-center gap-1"
                >
                    <span>Buka</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</div>
