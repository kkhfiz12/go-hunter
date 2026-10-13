<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PriceReportController extends Controller
{
    /**
     * Store a newly created price report for the specified destination.
     */
    public function store(Request $request, Destination $destination): RedirectResponse
    {
        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'price_paid' => ['required', 'numeric', 'min:0'],
            'fairness_level' => ['required', 'string', 'in:cheap,fair,overpriced'],
            'proof_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $proofImagePath = null;
        if ($request->hasFile('proof_image')) {
            $proofImagePath = $request->file('proof_image')->store('price-reports', 'public');
        }

        $user = $request->user();

        if (! $user) {
            $user = User::first() ?? User::factory()->create([
                'name' => 'Kontributor Tamu',
                'email' => 'tamu@gohunter.id',
            ]);
        }

        $user->increment('reputation_points', 5);

        $destination->priceReports()->create([
            'user_id' => $user->id,
            'item_name' => $validated['item_name'],
            'price_paid' => $validated['price_paid'],
            'fairness_level' => $validated['fairness_level'],
            'proof_image' => $proofImagePath,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('destinations.show', $destination)
            ->with('success', 'Laporan harga riil berhasil dikirim! Anda mendapatkan +5 poin reputasi.');
    }
}
