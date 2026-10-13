<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    /**
     * Display a listing of destinations.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value() ?: $request->string('q')->trim()->value();
        $category = $request->string('category')->trim()->value();
        $sort = $request->string('sort')->trim()->value();

        $query = Destination::query()
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('province', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (! empty($category) && strtolower($category) !== 'semua') {
            $query->where('category', 'like', $category);
        }

        match ($sort) {
            'cheapest', 'price_asc' => $query->orderBy('official_ticket_price', 'asc'),
            'priciest', 'price_desc' => $query->orderBy('official_ticket_price', 'desc'),
            'rating', 'rating_desc' => $query->orderByDesc('reviews_avg_rating'),
            default => $query->latest(),
        };

        $destinations = $query->paginate(9)->withQueryString();

        $categories = ['Semua', 'Alam', 'Bahari', 'Budaya', 'Kuliner'];

        return view('destinations.index', [
            'destinations' => $destinations,
            'categories' => $categories,
            'selectedCategory' => $category ?: 'Semua',
            'search' => $search,
            'sort' => $sort ?: 'newest',
        ]);
    }

    /**
     * Display the specified destination.
     */
    public function show(Destination $destination): View
    {
        $destination->load([
            'reviews.user',
            'priceReports' => function ($query) {
                $query->with('user')->latest();
            },
            'threads' => function ($query) {
                $query->latest()->limit(5);
            },
        ]);

        $destination->loadAvg('reviews', 'rating');
        $destination->loadCount('reviews');

        return view('destinations.show', [
            'destination' => $destination,
        ]);
    }
}
