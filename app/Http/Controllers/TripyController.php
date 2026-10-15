<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Destination;
use App\Models\Spot;
use App\Models\UserCheckin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TripyController extends Controller
{
    /**
     * Display the TRIPY interactive map exploration page.
     */
    public function index(Request $request): View
    {
        $cities = City::withCount('destinations')->orderBy('name')->get();

        $selectedCitySlug = $request->query('city');
        $activeCity = null;

        if ($selectedCitySlug) {
            $activeCity = $cities->firstWhere('slug', $selectedCitySlug);
        }

        if (! $activeCity) {
            $activeCity = $cities->first();
        }

        $destinations = collect();
        $checkedInSpotIds = [];

        if ($activeCity) {
            $destinations = Destination::where('city_id', $activeCity->id)
                ->with(['spots' => function ($query) {
                    $query->orderBy('name');
                }])
                ->get();
        }

        if ($request->user()) {
            $checkedInSpotIds = UserCheckin::where('user_id', $request->user()->id)
                ->pluck('spot_id')
                ->toArray();
        }

        return view('tripy.index', [
            'cities' => $cities,
            'activeCity' => $activeCity,
            'destinations' => $destinations,
            'checkedInSpotIds' => $checkedInSpotIds,
        ]);
    }

    /**
     * Get destinations & spots data for a specific city via JSON.
     */
    public function cityData(Request $request, City $city): JsonResponse
    {
        $checkedInSpotIds = $request->user()
            ? UserCheckin::where('user_id', $request->user()->id)->pluck('spot_id')->toArray()
            : [];

        $destinations = Destination::where('city_id', $city->id)
            ->with(['spots'])
            ->get()
            ->map(function (Destination $destination) use ($checkedInSpotIds) {
                return [
                    'id' => $destination->id,
                    'name' => $destination->name,
                    'slug' => $destination->slug,
                    'type' => $destination->type ?? 'landmark',
                    'category' => $destination->category,
                    'short_description' => $destination->short_description ?? substr(strip_tags($destination->description), 0, 160).'...',
                    'latitude' => (float) $destination->latitude,
                    'longitude' => (float) $destination->longitude,
                    'cover_image' => $destination->cover_image,
                    'official_ticket_price' => (float) $destination->official_ticket_price,
                    'url' => route('destinations.show', $destination->slug),
                    'spots' => $destination->spots->map(function (Spot $spot) use ($checkedInSpotIds) {
                        return [
                            'id' => $spot->id,
                            'name' => $spot->name,
                            'category' => $spot->category,
                            'short_description' => $spot->short_description,
                            'latitude' => (float) $spot->latitude,
                            'longitude' => (float) $spot->longitude,
                            'points_reward' => (int) $spot->points_reward,
                            'photo_url' => $spot->photo_url,
                            'is_checked_in' => in_array($spot->id, $checkedInSpotIds, true),
                        ];
                    }),
                ];
            });

        return response()->json([
            'city' => [
                'id' => $city->id,
                'name' => $city->name,
                'slug' => $city->slug,
                'latitude' => (float) $city->latitude,
                'longitude' => (float) $city->longitude,
            ],
            'destinations' => $destinations,
        ]);
    }

    /**
     * Get spots around a specific destination.
     */
    public function spots(Request $request, Destination $destination): JsonResponse
    {
        $checkedInSpotIds = $request->user()
            ? UserCheckin::where('user_id', $request->user()->id)->pluck('spot_id')->toArray()
            : [];

        $spots = $destination->spots->map(function (Spot $spot) use ($checkedInSpotIds) {
            return [
                'id' => $spot->id,
                'destination_id' => $spot->destination_id,
                'name' => $spot->name,
                'category' => $spot->category,
                'short_description' => $spot->short_description,
                'latitude' => (float) $spot->latitude,
                'longitude' => (float) $spot->longitude,
                'points_reward' => (int) $spot->points_reward,
                'photo_url' => $spot->photo_url,
                'is_checked_in' => in_array($spot->id, $checkedInSpotIds, true),
            ];
        });

        return response()->json([
            'destination_id' => $destination->id,
            'destination_name' => $destination->name,
            'spots' => $spots,
        ]);
    }

    /**
     * Handle check-in & claim XP for a spot.
     */
    public function checkin(Request $request, Spot $spot): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masuk (login) ke akun Anda terlebih dahulu untuk melakukan check-in dan mengklaim XP.',
                'require_login' => true,
            ], 401);
        }

        $existingCheckin = UserCheckin::where('user_id', $user->id)
            ->where('spot_id', $spot->id)
            ->first();

        if ($existingCheckin) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah pernah melakukan check-in dan mengklaim XP di spot ini!',
                'is_checked_in' => true,
                'total_points' => $user->reputation_points,
            ], 422);
        }

        // Record check-in
        UserCheckin::create([
            'user_id' => $user->id,
            'spot_id' => $spot->id,
            'points_earned' => $spot->points_reward,
        ]);

        // Reward user
        $user->increment('reputation_points', $spot->points_reward);
        $user->refresh();

        return response()->json([
            'success' => true,
            'message' => "Luar biasa! Check-in berhasil di {$spot->name}. Anda mendapatkan +{$spot->points_reward} XP!",
            'points_earned' => (int) $spot->points_reward,
            'total_points' => (int) $user->reputation_points,
            'spot_id' => $spot->id,
            'is_checked_in' => true,
        ]);
    }
}
