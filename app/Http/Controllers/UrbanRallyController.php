<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Trail;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UrbanRallyController extends Controller
{
    /**
     * Display the Urban Rally trails catalog and multi-modal transit recommendations.
     */
    public function index(Request $request): View
    {
        $cities = City::withCount('trails')->orderBy('name')->get();

        $selectedCitySlug = $request->query('city');
        $activeCity = null;

        if ($selectedCitySlug && $selectedCitySlug !== 'all') {
            $activeCity = $cities->firstWhere('slug', $selectedCitySlug);
        }

        // Recommended routes (featured on top)
        $recommendedQuery = Trail::with(['city', 'destination', 'checkpoints'])
            ->where('is_recommended', true);

        if ($activeCity) {
            $recommendedQuery->where('city_id', $activeCity->id);
        }

        $recommendedTrails = $recommendedQuery->latest()->get();

        // All routes query
        $trailsQuery = Trail::with(['city', 'destination', 'checkpoints'])
            ->withCount('checkpoints');

        if ($activeCity) {
            $trailsQuery->where('city_id', $activeCity->id);
        }

        $trails = $trailsQuery->orderByDesc('is_recommended')->latest()->get();

        // Extract available modes across all trails for filter badges
        $availableModes = [
            'transjakarta' => 'TransJakarta',
            'mrt' => 'MRT',
            'krl' => 'KRL',
            'lrt' => 'LRT',
            'bus' => 'Bus Kota',
            'walking' => 'Jalan Kaki',
        ];

        return view('urban-rally.index', [
            'cities' => $cities,
            'activeCity' => $activeCity,
            'recommendedTrails' => $recommendedTrails,
            'trails' => $trails,
            'availableModes' => $availableModes,
        ]);
    }

    /**
     * Display the step-by-step transit timeline and interactive checkpoint explorer for a trail.
     */
    public function show(Request $request, Trail $trail): View
    {
        $trail->load([
            'city',
            'destination',
            'checkpoints' => function ($query) {
                $query->orderBy('step_order');
            },
        ]);

        $relatedTrails = Trail::where('city_id', $trail->city_id)
            ->where('id', '!=', $trail->id)
            ->with(['city', 'destination'])
            ->withCount('checkpoints')
            ->take(3)
            ->get();

        return view('urban-rally.show', [
            'trail' => $trail,
            'relatedTrails' => $relatedTrails,
        ]);
    }
}
