<x-layouts.app :title="$thread->title . ' — Forum Go Hunter'" :description="Str::limit($thread->content, 150)">
    @php
        $author = $thread->user;
        $destination = $thread->destination;
        $isAuthor = auth()->check() ? auth()->id() === $thread->user_id : true; // In open demo, allow interaction
    @endphp

    <div class="bg-slate-50 min-h-screen py-8 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
                <a href="{{ url('/') }}" class="hover:text-sky-600 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('threads.index') }}" class="hover:text-sky-600 transition">Forum Diskusi</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold truncate">{{ $thread->title }}</span>
            </nav>

            <!-- Main Thread Card -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 mb-8">
                <!-- Header Meta -->
                <div class="flex flex-wrap items-center justify-between gap-3 pb-5 border-b border-slate-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold border ring-1 ring-inset {{ $thread->category_badge_class }}">
                            {{ $thread->category_label }}
                        </span>

                        @if ($destination)
                            <a
                                href="{{ route('destinations.show', $destination->slug) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200 hover:bg-sky-100 transition"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $destination->name }} ({{ $destination->city }})</span>
                            </a>
                        @endif

                        @if ($thread->has_solution)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 ring-1 ring-emerald-600/20">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Solusi Terkonfirmasi
                            </span>
                        @endif
                    </div>

                    <!-- Views counter -->
                    <span class="text-xs text-slate-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>{{ number_format($thread->views_count) }} kali dibaca</span>
                    </span>
                </div>

                <!-- Title & Body -->
                <div class="mt-6 flex gap-4 sm:gap-6">
                    <!-- Left Upvote Widget with Alpine.js -->
                    <div
                        x-data="{
                            upvoted: false,
                            count: {{ $thread->estimated_upvotes ?? 1 }},
                            toggle() {
                                this.upvoted = !this.upvoted;
                                this.count += (this.upvoted ? 1 : -1);
                            }
                        }"
                        class="flex flex-col items-center flex-shrink-0"
                    >
                        <div class="flex flex-col items-center p-2 rounded-2xl bg-slate-50 border border-slate-100 w-12 text-center">
                            <button
                                type="button"
                                @click="toggle()"
                                :class="upvoted ? 'text-sky-600 bg-sky-100 scale-110 shadow-xs' : 'text-slate-400 hover:text-sky-600 hover:bg-slate-200/60'"
                                class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-150"
                                title="Bantu upvote diskusi ini"
                            >
                                <svg class="w-5 h-5 transition-transform" :class="upvoted ? '-translate-y-0.5' : ''" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <span x-text="count" class="text-xs font-bold text-slate-700 mt-1">
                                {{ $thread->estimated_upvotes }}
                            </span>
                            <span class="text-[9px] uppercase tracking-wider text-slate-400 font-medium">Vote</span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">
                            {{ $thread->title }}
                        </h1>

                        <div class="flex items-center gap-3 text-xs text-slate-500 mt-2 mb-6">
                            <span class="font-bold text-slate-800 flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-full bg-sky-600 text-white flex items-center justify-center text-[10px]">
                                    {{ strtoupper(substr($author->name ?? 'A', 0, 1)) }}
                                </span>
                                {{ $author->name ?? 'Traveler Hunter' }}
                            </span>
                            <span>•</span>
                            <span class="px-1.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold">
                                ★ {{ $author->reputation_points ?? 0 }}
                            </span>
                            <span>•</span>
                            <span>{{ $thread->created_at ? $thread->created_at->diffForHumans() : 'baru saja' }}</span>
                        </div>

                        <div class="text-slate-700 text-sm sm:text-base leading-relaxed whitespace-pre-line border-t border-slate-100 pt-5">
                            {{ $thread->content }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Highlighted Verified Solution Banner (If Exists) -->
            @if ($solutionComment)
                <div class="bg-gradient-to-r from-emerald-50 via-white to-emerald-50/50 rounded-3xl border-2 border-emerald-400 p-6 sm:p-8 shadow-sm mb-8 relative overflow-hidden">
                    <div class="flex items-center justify-between gap-3 pb-4 border-b border-emerald-200/60 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            <div>
                                <span class="text-xs font-black uppercase tracking-wider text-emerald-800">
                                    Jawaban Solusi Terverifikasi / Warga Lokal
                                </span>
                                <p class="text-xs text-emerald-700 mt-0.5">
                                    Ditandai oleh penanya sebagai solusi paling akurat dan membantu.
                                </p>
                            </div>
                        </div>

                        <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs">
                            +10 Poin Reputasi
                        </span>
                    </div>

                    <div class="text-slate-800 text-sm sm:text-base leading-relaxed whitespace-pre-line mb-4 font-medium">
                        {{ $solutionComment->content }}
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-emerald-100">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-800">{{ $solutionComment->user->name ?? 'Warga Lokal' }}</span>
                            <span>•</span>
                            <span>{{ $solutionComment->created_at ? $solutionComment->created_at->diffForHumans() : 'baru saja' }}</span>
                        </div>

                        <a href="#comment-{{ $solutionComment->id }}" class="font-bold text-emerald-700 hover:text-emerald-800">
                            Lihat di Utas Diskusi &darr;
                        </a>
                    </div>
                </div>
            @endif

            <!-- Comments Section -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 mb-8 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                        <span>Tanggapan & Jawaban</span>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                            {{ $comments->count() }}
                        </span>
                    </h2>
                </div>

                <!-- Comments List -->
                @if ($comments->count() > 0)
                    <div class="space-y-6">
                        @foreach ($comments as $comment)
                            <div
                                id="comment-{{ $comment->id }}"
                                x-data="{ showReply: false }"
                                class="p-5 rounded-2xl border transition-all duration-200 {{ $comment->is_solution ? 'bg-emerald-50/40 border-emerald-300 ring-2 ring-emerald-500/10' : 'bg-slate-50/70 border-slate-200/80 hover:bg-slate-50' }}"
                            >
                                <!-- Comment Author Header -->
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-sky-500 to-indigo-600 text-white font-bold flex items-center justify-center text-xs flex-shrink-0">
                                            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-sm text-slate-900">
                                                    {{ $comment->user->name ?? 'Kontributor' }}
                                                </span>
                                                <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                                    ★ {{ $comment->user->reputation_points ?? 0 }}
                                                </span>
                                                @if ($comment->is_solution)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500 text-white shadow-2xs">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Jawaban Solusi
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] text-slate-400 block -mt-0.5">
                                                {{ $comment->created_at ? $comment->created_at->diffForHumans() : 'baru saja' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Action: Tandai Solusi (Untuk Pemilik Thread) -->
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('comments.mark-as-solution', $comment) }}" method="POST">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold transition {{ $comment->is_solution ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-white border border-slate-200 text-slate-600 hover:text-emerald-700 hover:border-emerald-300' }}"
                                                title="Tandai sebagai jawaban solusi yang membantu"
                                            >
                                                <svg class="w-3.5 h-3.5 {{ $comment->is_solution ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>{{ $comment->is_solution ? 'Solusi Terpilih' : 'Tandai Solusi' }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Comment Body -->
                                <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line pl-10">
                                    {{ $comment->content }}
                                </p>

                                <!-- Reply Trigger -->
                                <div class="pl-10 mt-3 pt-2 border-t border-slate-200/50 flex items-center justify-between text-xs text-slate-500">
                                    <button
                                        type="button"
                                        @click="showReply = !showReply"
                                        class="font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        <span x-text="showReply ? 'Tutup Balasan' : 'Balas Jawaban Ini'"></span>
                                    </button>
                                </div>

                                <!-- Nested Reply Form (Accordion) -->
                                <div x-show="showReply" x-cloak class="mt-3 pl-10">
                                    <form action="{{ route('threads.comments.store', $thread->slug) }}" method="POST" class="space-y-3 bg-white p-3.5 rounded-xl border border-slate-200">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                        <textarea
                                            name="content"
                                            rows="2"
                                            required
                                            placeholder="Tulis balasan untuk {{ $comment->user->name ?? 'penjawab' }}..."
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white"
                                        ></textarea>
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                type="button"
                                                @click="showReply = false"
                                                class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-100 rounded-lg"
                                            >
                                                Batal
                                            </button>
                                            <button
                                                type="submit"
                                                class="px-3.5 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg transition"
                                            >
                                                Kirim Balasan
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Nested Replies Loop -->
                                @if ($comment->replies && $comment->replies->count() > 0)
                                    <div class="mt-4 pl-8 sm:pl-10 space-y-3 border-l-2 border-slate-200/80 ml-4">
                                        @foreach ($comment->replies as $reply)
                                            <div id="comment-{{ $reply->id }}" class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs">
                                                <div class="flex items-center gap-2 mb-1.5">
                                                    <span class="font-bold text-xs text-slate-800">{{ $reply->user->name ?? 'Traveler' }}</span>
                                                    <span class="text-[10px] text-slate-400">•</span>
                                                    <span class="text-[10px] text-slate-400">{{ $reply->created_at ? $reply->created_at->diffForHumans() : 'baru saja' }}</span>
                                                </div>
                                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                                                    {{ $reply->content }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <p class="text-sm font-semibold text-slate-700">Belum ada jawaban di pertanyaan ini</p>
                        <p class="text-xs text-slate-500 mt-1">Punya info terpercaya? Jadilah yang pertama memberikan solusi untuk penanya!</p>
                    </div>
                @endif

                <!-- Form Tambah Jawaban Baru -->
                <div class="pt-6 border-t border-slate-100">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 mb-3 flex items-center justify-between">
                        <span>Tulis Jawaban Anda</span>
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                            +2 Poin Reputasi
                        </span>
                    </h3>

                    <form action="{{ route('threads.comments.store', $thread->slug) }}" method="POST" class="space-y-4">
                        @csrf
                        <textarea
                            name="content"
                            rows="4"
                            required
                            placeholder="Bagikan pengalaman, kondisi nyata, atau tips yang kamu ketahui secara transparan..."
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                        ></textarea>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">
                                Jawaban yang diverifikasi warga lokal akan mendapat +10 poin tambahan.
                            </span>

                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs sm:text-sm shadow-sm transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                <span>Kirim Tanggapan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
