<x-layouts.app
    title="Papan Peringkat Global (Leaderboard) — Eksplor Pedia"
    description="Papan peringkat top 50 penjelajah sejarah Go-Hunter dengan poin XP tertinggi dan badge reputasi legendaris."
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold uppercase">
                        ★ Leaderboard Nasional
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight mt-1">
                    Papan Peringkat Penjelajah Sejarah
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Daftar 50 traveler dengan perolehan skor XP tertinggi dari modul kuis edukasi Go-Hunter.
                </p>
            </div>

            <!-- Tab Navigation Links -->
            <div class="flex items-center gap-2">
                <a
                    href="{{ route('eksplor-pedia.index') }}"
                    class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition-all"
                >
                    &larr; Katalog Kuis
                </a>
                <a
                    href="{{ route('eksplor-pedia.rewards') }}"
                    class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs transition-all shadow-sm"
                >
                    Tukar Hadiah &rarr;
                </a>
            </div>
        </div>

        <!-- Top 3 Podium (Gold, Silver, Bronze) -->
        @if ($topUsers->count() >= 3)
            @php
                $rank1 = $topUsers[0];
                $rank2 = $topUsers[1];
                $rank3 = $topUsers[2];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end pt-8 pb-4">
                <!-- Rank 2: Silver (Left) -->
                <div class="order-2 md:order-1 bg-gradient-to-b from-slate-100 to-white rounded-3xl p-6 border-2 border-slate-300 text-center space-y-3 shadow-md relative">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 w-10 h-10 rounded-full bg-slate-300 text-slate-800 font-black text-sm flex items-center justify-center ring-4 ring-white shadow-sm">
                        #2
                    </div>
                    <div class="pt-2">
                        @if($rank2->avatar)
                            <img src="{{ $rank2->avatar }}" alt="{{ $rank2->name }}" class="w-16 h-16 mx-auto rounded-2xl object-cover ring-4 ring-slate-300">
                        @else
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-200 text-slate-700 font-black text-xl flex items-center justify-center ring-4 ring-slate-300 shadow-inner">
                                {{ strtoupper(substr($rank2->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 truncate">{{ $rank2->name }}</h3>
                        <span class="inline-block mt-0.5 text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-700">
                            {{ $rank2->badge_level ?? 'Penjelajah Aktif' }}
                        </span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                        <div class="text-[10px] text-slate-400 uppercase font-semibold">Total Poin XP</div>
                        <div class="text-xl font-black text-slate-800">
                            ★ {{ number_format(max($rank2->total_points, $rank2->reputation_points)) }}
                        </div>
                    </div>
                </div>

                <!-- Rank 1: Gold Crown (Center, Tallest) -->
                <div class="order-1 md:order-2 bg-gradient-to-b from-amber-50 to-white rounded-3xl p-8 border-2 border-amber-400 text-center space-y-4 shadow-xl relative -mt-4">
                    <div class="absolute -top-7 left-1/2 -translate-x-1/2 w-14 h-14 rounded-full bg-gradient-to-tr from-amber-400 to-yellow-300 text-slate-950 font-black text-base flex flex-col items-center justify-center ring-4 ring-white shadow-md">
                        <span class="text-xs leading-none">👑</span>
                        <span class="leading-none mt-0.5">#1</span>
                    </div>
                    <div class="pt-4">
                        @if($rank1->avatar)
                            <img src="{{ $rank1->avatar }}" alt="{{ $rank1->name }}" class="w-20 h-20 mx-auto rounded-2xl object-cover ring-4 ring-amber-400 shadow-md">
                        @else
                            <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-tr from-amber-400 to-amber-500 text-slate-950 font-black text-2xl flex items-center justify-center ring-4 ring-amber-400 shadow-md">
                                {{ strtoupper(substr($rank1->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 truncate">{{ $rank1->name }}</h3>
                        <span class="inline-block mt-1 text-xs font-black px-3 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-300">
                            {{ $rank1->badge_level ?? 'Master Penjelajah' }}
                        </span>
                    </div>
                    <div class="p-4 rounded-2xl bg-amber-100/60 border border-amber-300/80">
                        <div class="text-[10px] text-amber-900 uppercase font-bold">Juara Tertinggi</div>
                        <div class="text-2xl font-black text-amber-700">
                            ★ {{ number_format(max($rank1->total_points, $rank1->reputation_points)) }} XP
                        </div>
                    </div>
                </div>

                <!-- Rank 3: Bronze (Right) -->
                <div class="order-3 bg-gradient-to-b from-amber-900/5 to-white rounded-3xl p-6 border-2 border-amber-600/30 text-center space-y-3 shadow-md relative">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 w-10 h-10 rounded-full bg-amber-700 text-white font-black text-sm flex items-center justify-center ring-4 ring-white shadow-sm">
                        #3
                    </div>
                    <div class="pt-2">
                        @if($rank3->avatar)
                            <img src="{{ $rank3->avatar }}" alt="{{ $rank3->name }}" class="w-16 h-16 mx-auto rounded-2xl object-cover ring-4 ring-amber-600/30">
                        @else
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-100 text-amber-900 font-black text-xl flex items-center justify-center ring-4 ring-amber-600/30 shadow-inner">
                                {{ strtoupper(substr($rank3->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 truncate">{{ $rank3->name }}</h3>
                        <span class="inline-block mt-0.5 text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800">
                            {{ $rank3->badge_level ?? 'Penjelajah Aktif' }}
                        </span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                        <div class="text-[10px] text-slate-400 uppercase font-semibold">Total Poin XP</div>
                        <div class="text-xl font-black text-slate-800">
                            ★ {{ number_format(max($rank3->total_points, $rank3->reputation_points)) }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Full Table Ranking (#1 to #50) -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">Peringkat 50 Besar Pengguna</h2>
                <span class="text-xs text-slate-400 font-medium">Diperbarui real-time</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th scope="col" class="py-3.5 px-4 w-16 text-center">Rank</th>
                            <th scope="col" class="py-3.5 px-4">Nama Penjelajah</th>
                            <th scope="col" class="py-3.5 px-4 hidden sm:table-cell">Gelar / Badge Level</th>
                            <th scope="col" class="py-3.5 px-4 text-right">Total XP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($topUsers as $index => $u)
                            @php
                                $isCurrentUser = auth()->check() && auth()->user()->id === $u->id;
                                $rank = $index + 1;
                                $displayPoints = max($u->total_points, $u->reputation_points);
                            @endphp
                            <tr class="transition-colors {{ $isCurrentUser ? 'bg-amber-50/80 font-bold' : 'hover:bg-slate-50/70' }}">
                                <td class="py-3.5 px-4 text-center font-black">
                                    @if ($rank === 1)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-amber-400 text-slate-950 text-xs">🥇 1</span>
                                    @elseif ($rank === 2)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-300 text-slate-900 text-xs">🥈 2</span>
                                    @elseif ($rank === 3)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-amber-700 text-white text-xs">🥉 3</span>
                                    @else
                                        <span class="text-slate-500 font-bold text-xs">#{{ $rank }}</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        @if($u->avatar)
                                            <img src="{{ $u->avatar }}" alt="{{ $u->name }}" class="w-9 h-9 rounded-xl object-cover">
                                        @else
                                            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center">
                                                {{ strtoupper(substr($u->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                                {{ $u->name }}
                                                @if ($isCurrentUser)
                                                    <span class="ml-1 text-[10px] font-extrabold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full">
                                                        Anda
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="sm:hidden text-[10px] text-slate-400">
                                                {{ $u->badge_level ?? 'Penjelajah Pemula' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 hidden sm:table-cell">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ $u->badge_level ?? 'Penjelajah Pemula' }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-right">
                                    <span class="font-black text-amber-600 text-xs sm:text-sm">
                                        ★ {{ number_format($displayPoints) }} XP
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sticky User Rank Status Bar (if logged in) -->
        @auth
            <div class="sticky bottom-6 z-30 max-w-2xl mx-auto bg-slate-900 text-white rounded-2xl p-4 shadow-xl border border-slate-700 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 font-black text-sm flex items-center justify-center shadow-md">
                        #{{ $currentUserRank ?? '-' }}
                    </div>
                    <div>
                        <div class="text-xs text-slate-300 font-semibold">Peringkat Anda Saat Ini</div>
                        <div class="text-sm font-black text-white">{{ auth()->user()->name }}</div>
                    </div>
                </div>

                <div class="text-right">
                    <div class="text-base font-black text-amber-400">
                        ★ {{ number_format(max(auth()->user()->total_points ?? 0, auth()->user()->reputation_points ?? 0)) }} XP
                    </div>
                    <a href="{{ route('eksplor-pedia.index') }}" class="text-[11px] font-bold text-sky-400 hover:text-sky-300">
                        Kerjakan kuis untuk naik peringkat &rarr;
                    </a>
                </div>
            </div>
        @endauth

    </div>
</x-layouts.app>
