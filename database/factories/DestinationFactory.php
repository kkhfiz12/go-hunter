<?php

namespace Database\Factories;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Destination>
 */
class DestinationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->city().' Park';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->randomNumber(4),
            'category' => fake()->randomElement(['Alam', 'Bahari', 'Budaya', 'Kuliner']),
            'city' => fake()->city(),
            'province' => fake()->state(),
            'address' => fake()->address(),
            'latitude' => fake()->latitude(-10, 5),
            'longitude' => fake()->longitude(95, 140),
            'description' => fake()->paragraphs(3, true),
            'operating_hours' => [
                'monday' => '08:00 - 17:00',
                'weekend' => '07:00 - 18:00',
            ],
            'official_ticket_price' => fake()->randomElement([15000, 25000, 50000, 100000]),
            'parking_fee_motor' => 5000,
            'parking_fee_car' => 10000,
            'nearest_transit_info' => 'Halte Bus 500m / Stasiun KRL 2km',
            'cover_image' => 'destinations/sample.jpg',
        ];
    }
}
