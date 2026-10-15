<x-layouts.app
    :title="'Mengerjakan: ' . $quiz->title . ' — Eksplor Pedia'"
    :description="'Kuis interaktif ' . $quiz->title"
    :hideFooter="true"
>
    @php
        $questionsJson = json_encode($clientQuestions);
        $timeLimitSeconds = $quiz->time_limit_minutes * 60;
    @endphp

    <div
        x-data="quizRunner({
            quizSlug: '{{ $quiz->slug }}',
            submitUrl: '{{ route('eksplor-pedia.submit', $quiz->slug) }}',
            csrfToken: '{{ csrf_token() }}',
            questions: {{ $questionsJson }},
            totalTime: {{ $timeLimitSeconds }},
            pointsPerQuestion: {{ $quiz->points_per_question }},
            isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},
        })"
        class="max-w-4xl mx-auto px-4 sm:px-6 py-6 space-y-6"
    >

        <!-- Quiz Play Header with Timer & Progress -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-6 shadow-sm sticky top-20 z-20 backdrop-blur-md bg-white/95">
            <div class="flex items-center justify-between gap-4">
                <!-- Left: Title & Question Count -->
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-sky-600 uppercase tracking-wide">
                            {{ $quiz->category->city->name ?? 'Kuis' }}
                        </span>
                        <span class="text-slate-300">•</span>
                        @if ($quiz->difficulty === 'easy')
                            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                Mudah (+{{ $quiz->points_per_question }} XP/soal)
                            </span>
                        @elseif ($quiz->difficulty === 'medium')
                            <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                Sedang (+{{ $quiz->points_per_question }} XP/soal)
                            </span>
                        @else
                            <span class="text-[11px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">
                                Sulit (+{{ $quiz->points_per_question }} XP/soal)
                            </span>
                        @endif
                    </div>
                    <h1 class="text-base sm:text-lg font-black text-slate-900 truncate mt-0.5">
                        {{ $quiz->title }}
                    </h1>
                </div>

                <!-- Right: Timer Pill -->
                <div
                    class="shrink-0 flex items-center gap-2 px-3.5 py-2 rounded-2xl border transition-all"
                    :class="timeRemaining <= 60 && hasStarted ? 'bg-rose-50 border-rose-200 text-rose-600 animate-pulse' : 'bg-slate-50 border-slate-200 text-slate-700'"
                >
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-black font-mono tracking-tight" x-text="formatTime(timeRemaining)"></span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-4">
                <div class="flex-1 bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div
                        class="bg-sky-600 h-full rounded-full transition-all duration-300"
                        :style="`width: ${progressPercentage}%`"
                    ></div>
                </div>
                <div class="text-xs font-bold text-slate-500 shrink-0">
                    <span class="text-sky-600 font-black" x-text="answeredCount"></span> / <span x-text="questions.length"></span> Terjawab
                </div>
            </div>
        </div>

        <!-- Question Box (Active Quiz) -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
                <!-- Question Header & Indicator -->
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-sky-50 text-sky-700 border border-sky-200">
                        Soal <span x-text="currentQuestionIndex + 1"></span> dari <span x-text="questions.length"></span>
                    </span>

                    <span class="text-xs font-semibold text-slate-500">
                        Nilai Soal Ini: <strong class="text-amber-600 font-black">+{{ $quiz->points_per_question }} XP</strong>
                    </span>
                </div>

                <!-- Question Text -->
                <h2
                    class="text-lg sm:text-xl font-bold text-slate-900 leading-snug"
                    x-text="currentQuestion.question_text"
                ></h2>

                <!-- Options Grid (A, B, C, D) -->
                <div class="grid grid-cols-1 gap-3 pt-2">
                    <template x-for="(optionText, optIndex) in currentQuestion.options" :key="optIndex">
                        <button
                            type="button"
                            @click="selectAnswer(currentQuestion.id, optIndex)"
                            class="w-full text-left p-4 rounded-2xl border-2 transition-all flex items-center gap-3 group cursor-pointer focus:outline-none"
                            :class="selectedAnswers[currentQuestion.id] === optIndex
                                ? 'border-sky-600 bg-sky-50/70 text-sky-950 font-bold shadow-xs'
                                : 'border-slate-200/90 bg-white hover:border-slate-300 hover:bg-slate-50 text-slate-700'"
                        >
                            <!-- Option Letter Circle (A, B, C, D) -->
                            <div
                                class="w-8 h-8 rounded-xl font-black text-xs flex items-center justify-center shrink-0 transition-colors"
                                :class="selectedAnswers[currentQuestion.id] === optIndex
                                    ? 'bg-sky-600 text-white shadow-xs'
                                    : 'bg-slate-100 text-slate-500 group-hover:bg-slate-200'"
                                x-text="getOptionLetter(optIndex)"
                            ></div>

                            <div class="flex-1 text-sm leading-relaxed" x-text="optionText"></div>

                            <!-- Checkmark if selected -->
                            <div
                                x-show="selectedAnswers[currentQuestion.id] === optIndex"
                                class="text-sky-600 shrink-0"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Navigation Action Buttons & Question Jump Grid -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Jump to Question Pills -->
                <div class="flex items-center gap-1.5 flex-wrap">
                    <template x-for="(q, idx) in questions" :key="q.id">
                        <button
                            type="button"
                            @click="goToQuestion(idx)"
                            class="w-8 h-8 rounded-lg text-xs font-bold transition-all flex items-center justify-center cursor-pointer"
                            :class="{
                                'ring-2 ring-sky-500 ring-offset-2': currentQuestionIndex === idx,
                                'bg-sky-600 text-white shadow-xs': selectedAnswers[q.id] !== undefined,
                                'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50': selectedAnswers[q.id] === undefined
                            }"
                            x-text="idx + 1"
                        ></button>
                    </template>
                </div>

                <!-- Prev, Next & Submit Buttons -->
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button
                        type="button"
                        @click="prevQuestion()"
                        :disabled="currentQuestionIndex === 0"
                        class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold disabled:opacity-40 disabled:cursor-not-allowed transition-all cursor-pointer"
                    >
                        &larr; Sebelumnya
                    </button>

                    <button
                        type="button"
                        x-show="currentQuestionIndex < questions.length - 1"
                        @click="nextQuestion()"
                        class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all cursor-pointer"
                    >
                        Berikutnya &rarr;
                    </button>

                    <button
                        type="button"
                        @click="openSubmitModal()"
                        :disabled="isSubmitting"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-black text-xs shadow-md transition-all active:scale-95 disabled:opacity-50 cursor-pointer"
                    >
                        <span x-text="isSubmitting ? 'Mengirim Jawaban...' : 'Selesai & Kumpulkan'"></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Review Section (Displayed Below after quiz is submitted) -->
        <div x-show="isSubmitted && results" class="space-y-4 pt-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-black text-slate-900 tracking-tight">
                    Pembahasan Edukasi Sejarah Soal
                </h3>
                <span class="text-xs text-slate-500 font-medium">Pelajari fakta sejarah di balik tiap pertanyaan</span>
            </div>

            <div class="space-y-4">
                <template x-if="results">
                    <template x-for="(item, index) in results.detailed_results" :key="item.question_id">
                        <div
                            class="bg-white rounded-2xl border p-5 sm:p-6 space-y-4 shadow-sm"
                            :class="item.is_correct ? 'border-emerald-200' : 'border-rose-200'"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-lg text-xs font-black flex items-center justify-center"
                                        :class="item.is_correct ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'"
                                        x-text="index + 1"
                                    ></span>
                                    <span
                                        class="text-xs font-bold px-2 py-0.5 rounded-md uppercase"
                                        :class="item.is_correct ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'"
                                        x-text="item.is_correct ? 'Jawaban Benar' : 'Jawaban Kurang Tepat'"
                                    ></span>
                                </div>
                            </div>

                            <h4 class="text-sm sm:text-base font-bold text-slate-900" x-text="item.question_text"></h4>

                            <!-- Answers comparison -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                                    <div class="text-slate-400 font-medium mb-1">Pilihan Anda:</div>
                                    <div class="font-bold" :class="item.is_correct ? 'text-emerald-700' : 'text-rose-600'" x-text="item.selected_text"></div>
                                </div>
                                <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-200">
                                    <div class="text-emerald-700 font-medium mb-1">Kunci Jawaban:</div>
                                    <div class="font-bold text-emerald-900" x-text="item.correct_text"></div>
                                </div>
                            </div>

                            <!-- History Explanation -->
                            <template x-if="item.explanation">
                                <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200/80 text-xs text-slate-700 leading-relaxed space-y-1">
                                    <div class="font-extrabold text-amber-900 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Fakta & Pembahasan Sejarah:</span>
                                    </div>
                                    <p x-text="item.explanation"></p>
                                </div>
                            </template>
                        </div>
                    </template>
                </template>
            </div>
        </div>

        <!-- ======================================================= -->
        <!-- POP-UP MODAL 1: Konfirmasi Mulai Kuis (Ready to Start) -->
        <!-- ======================================================= -->
        <div
            x-show="showStartModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm"
        >
            <div
                class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 space-y-6 animate-scaleUp"
            >
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl mx-auto shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>

                <div class="text-center space-y-2">
                    <span class="inline-block px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-sky-50 text-sky-700">
                        {{ $quiz->category->city->name ?? 'Kuis Sejarah' }}
                    </span>
                    <h3 class="text-xl font-black text-slate-900 leading-tight">
                        Siap Mengerjakan Kuis?
                    </h3>
                    <p class="text-xs text-slate-500 font-medium">
                        {{ $quiz->title }}
                    </p>
                </div>

                <!-- Quiz Specs Grid -->
                <div class="grid grid-cols-3 gap-2.5 p-3.5 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                    <div class="p-2">
                        <div class="text-[10px] text-slate-400 uppercase font-bold">Soal</div>
                        <div class="text-sm font-black text-slate-900 mt-0.5">{{ $quiz->total_questions }} Butir</div>
                    </div>
                    <div class="p-2 border-x border-slate-200">
                        <div class="text-[10px] text-slate-400 uppercase font-bold">Waktu</div>
                        <div class="text-sm font-black text-slate-900 mt-0.5">{{ $quiz->time_limit_minutes }} Menit</div>
                    </div>
                    <div class="p-2">
                        <div class="text-[10px] text-slate-400 uppercase font-bold">Maks XP</div>
                        <div class="text-sm font-black text-amber-600 mt-0.5">+{{ $quiz->max_points }} XP</div>
                    </div>
                </div>

                <div class="text-xs text-slate-500 bg-amber-50 border border-amber-200/80 p-3 rounded-xl flex items-start gap-2">
                    <span class="text-amber-600 font-bold">💡</span>
                    <span>Waktu akan mulai dihitung mundur setelah Anda menekan tombol di bawah. Jawablah dengan cermat!</span>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a
                        href="{{ route('eksplor-pedia.category', $quiz->category->slug) }}"
                        class="w-full py-3 px-4 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs text-center hover:bg-slate-50 transition-colors"
                    >
                        Batal
                    </a>
                    <button
                        type="button"
                        @click="startQuizNow()"
                        class="w-full py-3 px-4 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-black text-xs shadow-md transition-all active:scale-95 cursor-pointer"
                    >
                        Mulai Sekarang &rarr;
                    </button>
                </div>
            </div>
        </div>

        <!-- ======================================================= -->
        <!-- POP-UP MODAL 2: Konfirmasi Mengirim Jawaban (Submit)   -->
        <!-- ======================================================= -->
        <div
            x-show="showSubmitModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm"
        >
            <div
                @click.outside="showSubmitModal = false"
                class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 space-y-6 animate-scaleUp"
            >
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl mx-auto shadow-inner">
                    🎯
                </div>

                <div class="text-center space-y-2">
                    <h3 class="text-xl font-black text-slate-900 leading-tight">
                        Kumpulkan Jawaban Anda?
                    </h3>
                    <p class="text-xs text-slate-500">
                        Pastikan seluruh pertanyaan telah Anda teliti sebelum mengirim jawaban akhir.
                    </p>
                </div>

                <!-- Answered Status Indicator -->
                <div class="p-4 rounded-2xl border" :class="answeredCount < questions.length ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-emerald-50 border-emerald-200 text-emerald-900'">
                    <div class="flex items-center justify-between font-bold text-xs">
                        <span>Status Jawaban:</span>
                        <span class="text-sm font-black" x-text="`${answeredCount} dari ${questions.length} Soal Terjawab`"></span>
                    </div>
                    <template x-if="answeredCount < questions.length">
                        <p class="text-[11px] text-amber-700 mt-2 font-medium">
                            ⚠️ Perhatian: Masih ada <strong x-text="questions.length - answeredCount"></strong> soal yang belum Anda jawab. Soal yang kosong akan bernilai 0 poin.
                        </p>
                    </template>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button
                        type="button"
                        @click="showSubmitModal = false"
                        class="w-full py-3 px-4 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition-colors cursor-pointer"
                    >
                        Periksa Lagi
                    </button>
                    <button
                        type="button"
                        @click="submitQuiz()"
                        :disabled="isSubmitting"
                        class="w-full py-3 px-4 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-black text-xs shadow-md transition-all active:scale-95 disabled:opacity-50 cursor-pointer"
                    >
                        <span x-text="isSubmitting ? 'Mengirim...' : 'Ya, Kumpulkan'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ======================================================= -->
        <!-- POP-UP MODAL 3: Pop-up Modern Hasil Kuis (Quiz Results) -->
        <!-- ======================================================= -->
        <div
            x-show="showResultModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md"
        >
            <div
                class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-6 animate-scaleUp text-center"
            >
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-black uppercase">
                    🎉 Kuis Selesai!
                </div>

                <!-- Big Score Circular Display -->
                <div class="space-y-1">
                    <div class="text-6xl font-black text-sky-600 tracking-tight" x-text="results ? `${results.score}%` : '0%'"></div>
                    <div class="text-xs font-bold text-slate-500">
                        Menjawab benar <strong class="text-slate-900" x-text="results?.correct_count"></strong> dari <strong class="text-slate-900" x-text="results?.total_questions"></strong> soal
                    </div>
                </div>

                <!-- XP Reward Box -->
                <div class="p-4 rounded-2xl bg-gradient-to-r from-amber-50 via-yellow-50 to-amber-50 border border-amber-200 text-center max-w-xs mx-auto shadow-xs">
                    <div class="text-[11px] font-bold text-amber-800 uppercase tracking-wide">Poin XP Didapatkan</div>
                    <div class="text-3xl font-black text-amber-600 mt-0.5">
                        +<span x-text="results?.points_earned"></span> XP
                    </div>
                    <template x-if="results?.is_authenticated">
                        <div class="text-xs text-slate-600 mt-1 font-medium">
                            Saldo Akun: <strong class="text-slate-900">★ <span x-text="results?.new_total_points"></span> XP</strong>
                            <div class="text-[11px] text-sky-700 font-bold mt-0.5" x-text="results?.new_badge_level"></div>
                        </div>
                    </template>
                    <template x-if="results && !results.is_authenticated">
                        <div class="text-[11px] text-amber-700 mt-1">
                            <a href="{{ route('login') }}" class="underline font-bold">Masuk akun</a> untuk mengklaim poin ini!
                        </div>
                    </template>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center gap-2.5 pt-2">
                    <button
                        type="button"
                        @click="showResultModal = false"
                        class="w-full sm:flex-1 py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-colors cursor-pointer"
                    >
                        Baca Pembahasan &rarr;
                    </button>
                    <a
                        href="{{ route('eksplor-pedia.leaderboard') }}"
                        class="w-full sm:flex-1 py-3 px-4 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs transition-colors text-center shadow-xs"
                    >
                        Cek Leaderboard
                    </a>
                    <a
                        href="{{ route('eksplor-pedia.category', $quiz->category->slug) }}"
                        class="w-full sm:w-auto py-3 px-4 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition-colors text-center"
                    >
                        Kuis Lain
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Alpine.js Quiz State Controller Script -->
    <script>
        function quizRunner(config) {
            return {
                questions: config.questions || [],
                submitUrl: config.submitUrl,
                csrfToken: config.csrfToken,
                currentQuestionIndex: 0,
                selectedAnswers: {},
                timeRemaining: config.totalTime,
                timerInterval: null,
                hasStarted: false,
                isSubmitting: false,
                isSubmitted: false,
                results: null,

                // Modal States
                showStartModal: true,
                showSubmitModal: false,
                showResultModal: false,

                get currentQuestion() {
                    return this.questions[this.currentQuestionIndex] || {};
                },

                get answeredCount() {
                    return Object.keys(this.selectedAnswers).length;
                },

                get progressPercentage() {
                    if (this.questions.length === 0) return 0;
                    return Math.round((this.answeredCount / this.questions.length) * 100);
                },

                startQuizNow() {
                    this.showStartModal = false;
                    this.hasStarted = true;
                    this.startTimer();
                },

                selectAnswer(questionId, optionIndex) {
                    this.selectedAnswers[questionId] = optionIndex;
                },

                goToQuestion(index) {
                    if (index >= 0 && index < this.questions.length) {
                        this.currentQuestionIndex = index;
                    }
                },

                prevQuestion() {
                    if (this.currentQuestionIndex > 0) {
                        this.currentQuestionIndex--;
                    }
                },

                nextQuestion() {
                    if (this.currentQuestionIndex < this.questions.length - 1) {
                        this.currentQuestionIndex++;
                    }
                },

                getOptionLetter(index) {
                    const letters = ['A', 'B', 'C', 'D', 'E'];
                    return letters[index] || (index + 1);
                },

                startTimer() {
                    this.timerInterval = setInterval(() => {
                        if (this.timeRemaining > 0) {
                            this.timeRemaining--;
                        } else {
                            clearInterval(this.timerInterval);
                            this.showSubmitModal = false;
                            this.submitQuiz();
                        }
                    }, 1000);
                },

                formatTime(seconds) {
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                },

                openSubmitModal() {
                    this.showSubmitModal = true;
                },

                async submitQuiz() {
                    if (this.isSubmitting || this.isSubmitted) return;
                    this.isSubmitting = true;
                    clearInterval(this.timerInterval);

                    try {
                        const response = await fetch(this.submitUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                            },
                            body: JSON.stringify({
                                answers: this.selectedAnswers
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.results = data;
                            this.isSubmitted = true;
                            this.showSubmitModal = false;
                            this.showResultModal = true;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        } else {
                            alert('Terjadi kesalahan saat memproses jawaban. Silakan coba lagi.');
                        }
                    } catch (error) {
                        console.error('Submission error:', error);
                        alert('Gagal mengirim jawaban. Periksa koneksi internet Anda.');
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            };
        }
    </script>
</x-layouts.app>
