<x-layouts.app
    title="Riwayat Kuis Saya — Eksplor Pedia"
    description="Daftar riwayat paket kuis sejarah yang telah Anda selesaikan beserta skor dan perolehan poin XP."
>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-6">
            <div>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold uppercase">
                    📜 Riwayat Aktivitas
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                    Riwayat Kuis Saya
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Catatan seluruh paket kuis sejarah yang pernah Anda selesaikan.
                </p>
            </div>

            <a
                href="{{ route('eksplor-pedia.index') }}"
                class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition-all shadow-xs"
            >
                + Kerjakan Kuis Baru
            </a>
        </div>

        @if ($histories->isEmpty())
            <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 space-y-3">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl font-bold">
                    📝
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Riwayat Kuis</h3>
                <p class="text-xs sm:text-sm text-slate-500 max-w-sm mx-auto">
                    Anda belum pernah menyelesaikan kuis. Jelajahi katalog kota dan raih poin XP pertamamu sekarang!
                </p>
                <div class="pt-2">
                    <a href="{{ route('eksplor-pedia.index') }}" class="inline-block px-5 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs">
                        Buka Katalog Kuis
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-5">Paket Kuis & Wilayah</th>
                                <th class="py-3.5 px-4 text-center">Tingkat</th>
                                <th class="py-3.5 px-4 text-center">Skor</th>
                                <th class="py-3.5 px-4 text-right">Poin Diperoleh</th>
                                <th class="py-3.5 px-5 text-right">Tanggal Selesai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($histories as $history)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-4 px-5">
                                        <div class="font-bold text-slate-900">{{ $history->quiz->title ?? 'Kuis' }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">
                                            {{ $history->quiz->category->city->name ?? 'Indonesia' }}
                                        </div>
                                    </td>

                                    <td class="py-4 px-4 text-center">
                                        @if (($history->quiz->difficulty ?? 'easy') === 'easy')
                                            <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700">Mudah</span>
                                        @elseif (($history->quiz->difficulty ?? '') === 'medium')
                                            <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700">Sedang</span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700">Sulit</span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-4 text-center">
                                        <span class="font-black text-slate-800 {{ $history->score >= 80 ? 'text-emerald-600' : ($history->score >= 50 ? 'text-amber-600' : 'text-slate-600') }}">
                                            {{ $history->score }}%
                                        </span>
                                    </td>

                                    <td class="py-4 px-4 text-right font-black text-amber-600">
                                        +{{ $history->points_earned }} XP
                                    </td>

                                    <td class="py-4 px-5 text-right text-xs text-slate-400 font-medium">
                                        {{ $history->completed_at ? $history->completed_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($histories->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $histories->links() }}
                    </div>
                @endif
            </div>
        @endif

    </div>
</x-layouts.app>
