<?php

namespace App\Http\Controllers;

use App\Models\BuddyRequest;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuddyRequestController extends Controller
{
    /**
     * Display a listing of travel buddy requests with filters.
     */
    public function index(Request $request): View
    {
        $destinationId = $request->input('destination_id');
        $budgetRange = $request->input('budget_range');
        $minBudget = $request->input('min_budget');
        $maxBudget = $request->input('max_budget');
        $status = $request->input('status', 'open');
        $search = $request->string('search')->trim()->value();

        $query = BuddyRequest::query()
            ->with(['user', 'destination']);

        // Filter by Destination ID
        if (! empty($destinationId) && $destinationId !== 'all') {
            $query->where('destination_id', $destinationId);
        }

        // Filter by Search Keyword (Title, Description, Destination Name, or City)
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('destination', function ($dq) use ($search) {
                        $dq->where('name', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%")
                            ->orWhere('province', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by Status (open / full / all)
        if (! empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by Budget Range presets
        if (! empty($budgetRange) && $budgetRange !== 'all') {
            match ($budgetRange) {
                'under_250k' => $query->where('estimated_budget_per_person', '<=', 250000),
                '250k_500k' => $query->whereBetween('estimated_budget_per_person', [250000, 500000]),
                '500k_1000k' => $query->whereBetween('estimated_budget_per_person', [500000, 1000000]),
                'over_1000k' => $query->where('estimated_budget_per_person', '>', 1000000),
                default => null,
            };
        } else {
            // Filter by explicit min/max budget if passed
            if ($minBudget !== null && is_numeric($minBudget)) {
                $query->where('estimated_budget_per_person', '>=', (float) $minBudget);
            }
            if ($maxBudget !== null && is_numeric($maxBudget)) {
                $query->where('estimated_budget_per_person', '<=', (float) $maxBudget);
            }
        }

        // Order open slots first, then soonest departure date
        $buddyRequests = $query
            ->orderByRaw("CASE WHEN status = 'open' THEN 0 ELSE 1 END")
            ->orderBy('departure_date', 'asc')
            ->latest('id')
            ->paginate(9)
            ->withQueryString();

        $destinations = Destination::orderBy('name')->get(['id', 'name', 'city']);

        return view('buddy.index', [
            'buddyRequests' => $buddyRequests,
            'destinations' => $destinations,
            'selectedDestination' => $destinationId ?: 'all',
            'selectedBudgetRange' => $budgetRange ?: 'all',
            'selectedStatus' => $status,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new travel buddy request.
     */
    public function create(): View
    {
        $destinations = Destination::orderBy('name')->get(['id', 'name', 'city']);

        return view('buddy.create', [
            'destinations' => $destinations,
        ]);
    }

    /**
     * Store a newly created travel buddy request in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'destination_id' => ['required', 'exists:destinations,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['nullable', 'date', 'after_or_equal:departure_date'],
            'slots_needed' => ['required', 'integer', 'min:1', 'max:50'],
            'slots_filled' => ['nullable', 'integer', 'min:1'],
            'estimated_budget_per_person' => ['required', 'numeric', 'min:0'],
            'contact_link' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();

        if (! $user) {
            $user = User::first() ?? User::factory()->create([
                'name' => 'Host Traveler',
                'email' => 'host@gohunter.id',
            ]);
        }

        // Reward user reputation points for creating a travel buddy plan
        $user->increment('reputation_points', 5);

        $slotsFilled = $validated['slots_filled'] ?? 1;
        $status = ($slotsFilled >= $validated['slots_needed']) ? 'full' : 'open';

        $buddyRequest = BuddyRequest::create([
            'user_id' => $user->id,
            'destination_id' => $validated['destination_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'departure_date' => $validated['departure_date'],
            'return_date' => $validated['return_date'] ?? null,
            'slots_needed' => $validated['slots_needed'],
            'slots_filled' => $slotsFilled,
            'estimated_budget_per_person' => $validated['estimated_budget_per_person'],
            'status' => $status,
            'contact_link' => $validated['contact_link'],
        ]);

        return redirect()
            ->route('buddy-requests.show', $buddyRequest)
            ->with('success', 'Ajakan perjalanan (Travel Buddy) berhasil dipublikasikan! Anda mendapatkan +5 poin reputasi.');
    }

    /**
     * Display the specified travel buddy request.
     */
    public function show(BuddyRequest $buddyRequest): View
    {
        $buddyRequest->load(['user', 'destination']);

        // Recommendations: other open trips in the same province/category or recent
        $otherTrips = BuddyRequest::with(['user', 'destination'])
            ->where('id', '!=', $buddyRequest->id)
            ->where('status', 'open')
            ->latest()
            ->limit(3)
            ->get();

        return view('buddy.show', [
            'buddyRequest' => $buddyRequest,
            'otherTrips' => $otherTrips,
        ]);
    }
}
