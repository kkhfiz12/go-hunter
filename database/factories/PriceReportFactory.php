<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\PriceReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PriceReport>
 */
class PriceReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'destination_id' => Destination::factory(),
            'user_id' => User::factory(),
            'item_name' => fake()->words(2, true),
            'price_paid' => fake()->randomElement([10000, 20000, 35000, 50000]),
            'fairness_level' => fake()->randomElement(['cheap', 'fair', 'overpriced']),
            'proof_image' => null,
            'notes' => fake()->sentence(),
        ];
    }
}
