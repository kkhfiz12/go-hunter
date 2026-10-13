<?php

namespace App\Http\Controllers;

use App\Models\BuddyRequest;
use App\Models\Destination;
use App\Models\PriceReport;
use App\Models\Thread;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the Go Hunter landing page with curated highlights.
     */
    public function index(): View
    {
        // 1. 3 Destinasi terpopuler (berdasarkan jumlah ulasan & rating)
        $popularDestinations = Destination::query()
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_count')
            ->orderByDesc('reviews_avg_rating')
            ->latest('id')
            ->limit(3)
            ->get();

        // 2. 2 Laporan getok harga terbaru untuk Alert Banner
        $recentGetokAlerts = PriceReport::query()
            ->with(['destination', 'user'])
            ->where('fairness_level', 'overpriced')
            ->latest('id')
            ->limit(2)
            ->get();

        // 3. 2 Ajakan Travel Buddy dengan sisa kursi paling sedikit / hampir penuh
        $urgentBuddyRequests = BuddyRequest::query()
            ->with(['destination', 'user'])
            ->where('status', 'open')
            ->whereRaw('(slots_needed - slots_filled) > 0')
            ->orderByRaw('(slots_needed - slots_filled) ASC')
            ->orderBy('departure_date', 'asc')
            ->limit(2)
            ->get();

        // Fallback jika belum ada yang hampir penuh
        if ($urgentBuddyRequests->isEmpty()) {
            $urgentBuddyRequests = BuddyRequest::query()
                ->with(['destination', 'user'])
                ->where('status', 'open')
                ->latest('id')
                ->limit(2)
                ->get();
        }

        // 4. 3 Diskusi Q&A terhangat (views terbanyak dan interaksi komentar)
        $hotThreads = Thread::query()
            ->with(['destination', 'user'])
            ->withCount('comments')
            ->withExists(['comments as has_solution' => function ($q) {
                $q->where('is_solution', true);
            }])
            ->orderByDesc('views_count')
            ->orderByDesc('comments_count')
            ->latest('id')
            ->limit(3)
            ->get();

        return view('welcome', [
            'popularDestinations' => $popularDestinations,
            'recentGetokAlerts' => $recentGetokAlerts,
            'urgentBuddyRequests' => $urgentBuddyRequests,
            'hotThreads' => $hotThreads,
        ]);
    }
}
