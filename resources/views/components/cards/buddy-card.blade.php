@props([
    'buddyRequest',
])

@php
    $user = $buddyRequest->user;
    $destination = $buddyRequest->destination;
    $totalSlots = max($buddyRequest->slots_needed, $buddyRequest->slots_filled);
    $filledSlots = min($buddyRequest->slots_filled, $totalSlots);
    $remainingSlots = max(0, $totalSlots - $filledSlots);
    $isOpen = $buddyRequest->status === 'open' && $remainingSlots > 0;

    $formattedBudget = 'Rp ' . number_format($buddyRequest->estimated_budget_per_person, 0, ',', '.');
    $dateRange = $buddyRequest->formatted_date_range;
    $whatsappUrl = $buddyRequest->whatsapp_url;
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between overflow-hidden relative group']) }}>
    <!-- Ticket Top Section (Main Details) -->
    <div class="p-5 sm:p-6 pb-4">
        <!-- Host Header & Status Pill -->
        <div class="flex items-center justify-between gap-3 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-600 text-white font-bold flex items-center justify-center text-sm shadow-sm flex-shrink-0">
                    {{ strtoupper(substr($user->name ?? 'H', 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-sm text-slate-900 truncate block max-w-[140px] sm:max-w-[170px]">
                            {{ $user->name ?? 'Host Hunter' }}
                        </span>
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/70" title="Reputasi Traveler">
                            ★ {{ $user->reputation_points ?? 0 }}
                        </span>
                    </div>
                    <span class="text-[11px] text-slate-400 block -mt-0.5">
                        {{ $buddyRequest->created_at ? $buddyRequest->created_at->diffForHumans() : 'Baru saja' }}
                    </span>
                </div>
            </div>

            <!-- Status Pill -->
            <div>
                @if ($isOpen)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 ring-1 ring-emerald-600/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Open Trip
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 ring-1 ring-purple-600/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                        Slot Penuh
                    </span>
                @endif
            </div>
        </div>

        <!-- Destination & Date Header (Boarding Pass Route Style) -->
        <div class="bg-slate-50/80 rounded-2xl p-3.5 border border-slate-100 mb-4">
            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                <span class="font-semibold uppercase tracking-wider text-[10px] text-sky-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Tujuan Perjalanan
                </span>
                <span class="font-medium text-slate-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $dateRange }}
                </span>
            </div>

            <a href="{{ route('buddy-requests.show', $buddyRequest) }}" class="text-base font-black text-slate-900 group-hover:text-sky-600 transition block truncate">
                {{ $destination->name ?? 'Destinasi Wisata' }}
            </a>
            <p class="text-xs text-slate-500 truncate mt-0.5">
                {{ $destination->city ?? 'Indonesia' }}, {{ $destination->province ?? '' }}
            </p>
        </div>

        <!-- Trip Title & Short Description -->
        <div>
            <h3 class="font-bold text-sm sm:text-base text-slate-900 group-hover:text-sky-600 transition line-clamp-1 mb-1">
                {{ $buddyRequest->title }}
            </h3>
            <p class="text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed">
                {{ $buddyRequest->description }}
            </p>
        </div>

        <!-- Criteria Tags -->
        <div class="flex flex-wrap items-center gap-1.5 mt-3">
            <span class="px-2 py-0.5 rounded-lg bg-sky-50 text-sky-700 text-[11px] font-semibold border border-sky-100">
                Cost-Sharing
            </span>
            <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-slate-600 text-[11px] font-semibold">
                Backpacker
            </span>
            <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-slate-600 text-[11px] font-semibold">
                Bisa Patungan Mobil/BBM
            </span>
        </div>
    </div>

    <!-- Ticket Perforation Separator (Boarding Pass Notch) -->
    <div class="relative py-2 flex items-center">
        <!-- Left Notch Cutout -->
        <div class="w-4 h-7 bg-slate-50 border-r border-slate-200/90 rounded-r-full -ml-px absolute left-0 shadow-inner"></div>
        <!-- Dashed Divider Line -->
        <div class="w-full border-b-2 border-dashed border-slate-200 mx-5"></div>
        <!-- Right Notch Cutout -->
        <div class="w-4 h-7 bg-slate-50 border-l border-slate-200/90 rounded-l-full -mr-px absolute right-0 shadow-inner"></div>
    </div>

    <!-- Ticket Bottom Section (Seats, Budget & Action) -->
    <div class="p-5 sm:p-6 pt-2 bg-gradient-to-b from-white to-slate-50/50">
        <!-- Visual Slot Progress -->
        <div class="mb-4">
            <div class="flex items-center justify-between text-xs mb-2">
                <span class="font-bold text-slate-700">
                    Sisa <span class="{{ $remainingSlots > 0 ? 'text-emerald-600' : 'text-purple-600' }}">{{ $remainingSlots }}</span> dari {{ $totalSlots }} Kursi
                </span>
                <span class="text-[11px] text-slate-400 font-medium">
                    {{ $filledSlots }} Terisi
                </span>
            </div>

            <!-- Dots indicator: filled vs empty seats -->
            <div class="flex items-center gap-2">
                @for ($i = 1; $i <= min(8, $totalSlots); $i++)
                    @if ($i <= $filledSlots)
                        <!-- Filled Seat Dot -->
                        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold shadow-xs ring-2 ring-emerald-100" title="Kursi Terisi">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    @else
                        <!-- Available Seat Dot -->
                        <div class="w-6 h-6 rounded-full border-2 border-dashed border-slate-300 bg-white text-slate-400 flex items-center justify-center text-[10px] font-bold" title="Kursi Kosong">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                        </div>
                    @endif
                @endfor

                @if ($totalSlots > 8)
                    <span class="text-[11px] text-slate-400 font-bold ml-1">
                        +{{ $totalSlots - 8 }} lagi
                    </span>
                @endif
            </div>
        </div>

        <!-- Budget & Action Row -->
        <div class="flex items-center justify-between gap-3 pt-3 border-t border-slate-100">
            <div>
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Estimasi Patungan</span>
                <div class="text-base sm:text-lg font-black text-sky-600">
                    {{ $formattedBudget }}
                    <span class="text-[11px] font-normal text-slate-500">/org</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a
                    href="{{ route('buddy-requests.show', $buddyRequest) }}"
                    class="px-3 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-sky-600 bg-slate-100 hover:bg-slate-200 transition"
                    title="Lihat Detail Rencana"
                >
                    Detail
                </a>

                @if ($isOpen)
                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white text-xs font-bold shadow-sm hover:shadow-md transition"
                    >
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.152.569 4.17 1.564 5.917l-1.637 5.986 6.136-1.609c1.701.928 3.649 1.456 5.72 1.456 6.627 0 12-5.373 12-12s-5.373-12-12-12zm.001 22.083c-1.896 0-3.666-.543-5.161-1.482l-.37-.232-3.834 1.006 1.024-3.738-.255-.399c-1.077-1.688-1.648-3.659-1.648-5.698 0-5.753 4.678-10.431 10.43-10.431 5.753 0 10.432 4.678 10.432 10.431 0 5.753-4.679 10.431-10.432 10.431z"/>
                        </svg>
                        <span>Join WA</span>
                    </a>
                @else
                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition"
                        title="Tanyakan waiting list"
                    >
                        <span>Waiting List</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
