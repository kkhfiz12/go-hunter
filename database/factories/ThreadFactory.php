<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Thread>
 */
class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'user_id' => User::factory(),
            'destination_id' => Destination::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(4),
            'content' => fake()->paragraphs(2, true),
            'category' => fake()->randomElement(['general', 'traffic_transit', 'weather_update', 'tips']),
            'views_count' => fake()->numberBetween(0, 500),
        ];
    }
}
