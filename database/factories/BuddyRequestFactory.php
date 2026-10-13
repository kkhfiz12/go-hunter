<?php

namespace Database\Factories;

use App\Models\BuddyRequest;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BuddyRequest>
 */
class BuddyRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'destination_id' => Destination::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'departure_date' => now()->addDays(7)->toDateString(),
            'return_date' => now()->addDays(10)->toDateString(),
            'slots_needed' => fake()->numberBetween(1, 4),
            'slots_filled' => 1,
            'estimated_budget_per_person' => fake()->randomElement([250000, 500000, 1000000]),
            'status' => 'open',
            'contact_link' => 'https://wa.me/6281234567890',
        ];
    }
}
