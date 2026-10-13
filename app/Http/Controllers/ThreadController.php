<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ThreadController extends Controller
{
    /**
     * Display a listing of forum threads with category filters and sorting.
     */
    public function index(Request $request): View
    {
        $category = $request->input('category');
        $destinationId = $request->input('destination_id');
        $sort = $request->input('sort', 'newest');
        $search = $request->string('search')->trim()->value();

        $query = Thread::query()
            ->with(['user', 'destination'])
            ->withCount('comments')
            ->withExists(['comments as has_solution' => function ($q) {
                $q->where('is_solution', true);
            }]);

        // Filter by category
        if (! empty($category) && $category !== 'all') {
            $query->where('category', $category);
        }

        // Filter by destination
        if (! empty($destinationId) && $destinationId !== 'all') {
            $query->where('destination_id', $destinationId);
        }

        // Filter by search keyword
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('destination', function ($dq) use ($search) {
                        $dq->where('name', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting
        match ($sort) {
            'popular' => $query->orderByDesc('views_count')->orderByDesc('comments_count'),
            'most_commented' => $query->orderByDesc('comments_count'),
            'solved' => $query->orderByDesc('has_solution')->latest('id'),
            default => $query->latest('id'),
        };

        $threads = $query->paginate(10)->withQueryString();
        $destinations = Destination::orderBy('name')->get(['id', 'name', 'city']);

        $categories = [
            'all' => 'Semua Topik',
            'weather_update' => 'Kondisi Terkini',
            'traffic_transit' => 'Transportasi & Jalur',
            'tips' => 'Tips & Trik',
            'general' => 'Diskusi Umum',
        ];

        return view('forum.index', [
            'threads' => $threads,
            'categories' => $categories,
            'destinations' => $destinations,
            'selectedCategory' => $category ?: 'all',
            'selectedDestination' => $destinationId ?: 'all',
            'selectedSort' => $sort,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new thread.
     */
    public function create(): View
    {
        $destinations = Destination::orderBy('name')->get(['id', 'name', 'city']);

        return view('forum.create', [
            'destinations' => $destinations,
        ]);
    }

    /**
     * Store a newly created thread in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:general,traffic_transit,weather_update,tips'],
            'content' => ['required', 'string'],
            'destination_id' => ['nullable', 'exists:destinations,id'],
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug ?: 'diskusi-'.Str::random(6);
        $counter = 1;

        while (Thread::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $user = $request->user();

        if (! $user) {
            $user = User::first() ?? User::factory()->create([
                'name' => 'Traveler Hunter',
                'email' => 'traveler@gohunter.id',
            ]);
        }

        // Reward user reputation points for creating a discussion thread
        $user->increment('reputation_points', 5);

        $thread = Thread::create([
            'user_id' => $user->id,
            'destination_id' => $validated['destination_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'category' => $validated['category'],
            'views_count' => 0,
        ]);

        return redirect()
            ->route('threads.show', $thread)
            ->with('success', 'Pertanyaan berhasil dipublikasikan! Anda mendapatkan +5 poin reputasi.');
    }

    /**
     * Display the specified thread with nested comments.
     */
    public function show(Thread $thread): View
    {
        $thread->increment('views_count');
        $thread->load(['user', 'destination']);

        // Load nested comments: top-level comments with replies, solutions prioritized first
        $comments = $thread->comments()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderByDesc('is_solution')
            ->oldest()
            ->get();

        $solutionComment = $comments->firstWhere('is_solution', true)
            ?? $thread->comments()->where('is_solution', true)->with('user')->first();

        return view('forum.show', [
            'thread' => $thread,
            'comments' => $comments,
            'solutionComment' => $solutionComment,
        ]);
    }
}
