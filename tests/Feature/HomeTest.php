<?php

namespace Tests\Feature;

use App\Models\BuddyRequest;
use App\Models\Destination;
use App\Models\PriceReport;
use App\Models\Review;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Info Nyata Tanpa Tipu');
        $response->assertSee('Destinasi Terpopuler Minggu Ini');
        $response->assertSee('Papan Teman Jalan');
        $response->assertSee('Diskusi Terhangat Komunitas');
    }

    public function test_landing_page_displays_top_three_destinations(): void
    {
        $user = User::factory()->create();

        $destinations = Destination::factory()->count(4)->create();

        // Add reviews to create popularity
        foreach ($destinations as $index => $destination) {
            Review::factory()->count($index + 1)->create([
                'destination_id' => $destination->id,
                'user_id' => $user->id,
                'rating' => 5,
            ]);
        }

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee($destinations[3]->name);
        $response->assertSee($destinations[2]->name);
        $response->assertSee($destinations[1]->name);
    }

    public function test_landing_page_displays_recent_getok_alerts(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create(['name' => 'Pantai Parangtritis']);

        $alert1 = PriceReport::factory()->create([
            'destination_id' => $destination->id,
            'user_id' => $user->id,
            'item_name' => 'Parkir Motor Liar Parangtritis',
            'price_paid' => 20000,
            'fairness_level' => 'overpriced',
            'created_at' => now()->subMinute(),
        ]);

        $alert2 = PriceReport::factory()->create([
            'destination_id' => $destination->id,
            'user_id' => $user->id,
            'item_name' => 'Kelapa Muda 50 Ribu',
            'price_paid' => 50000,
            'fairness_level' => 'overpriced',
            'created_at' => now(),
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Waspada Getok Harga Terkini');
        $response->assertSee('Parkir Motor Liar Parangtritis');
        $response->assertSee('Kelapa Muda 50 Ribu');
    }

    public function test_landing_page_displays_urgent_travel_buddy_requests(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create();

        $buddy = BuddyRequest::factory()->create([
            'user_id' => $user->id,
            'destination_id' => $destination->id,
            'title' => 'Patungan Jeep Bromo Sunrise Spot',
            'status' => 'open',
            'departure_date' => now()->addDays(2),
            'slots_needed' => 5,
            'slots_filled' => 4,
            'estimated_budget_per_person' => 150000,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Patungan Jeep Bromo Sunrise Spot');
    }

    public function test_landing_page_displays_hot_forum_threads(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create();

        $thread = Thread::factory()->create([
            'user_id' => $user->id,
            'destination_id' => $destination->id,
            'title' => 'Apakah Jalur Ciwidey Macet Parah Weekend Ini?',
            'category' => 'traffic_transit',
            'views_count' => 120,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Apakah Jalur Ciwidey Macet Parah Weekend Ini?');
    }
}
