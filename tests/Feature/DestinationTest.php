<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class DestinationTest extends TestCase
{
    use RefreshDatabase;

    public function test_destination_index_page_can_be_rendered(): void
    {
        Destination::factory()->count(3)->create();

        $response = $this->get(route('destinations.index'));

        $response->assertStatus(200);
        $response->assertSee('Katalog Destinasi Wisata');
        $response->assertSee('Comprehensive Directory');
    }

    public function test_destination_index_renders_responsive_grid(): void
    {
        Destination::factory()->create([
            'name' => 'Pantai Kuta Bali',
            'category' => 'Bahari',
        ]);

        $response = $this->get(route('destinations.index'));

        $response->assertStatus(200);
        $response->assertSee('grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3', false);
        $response->assertSee('Pantai Kuta Bali');
    }

    public function test_destination_card_component_renders_visual_specs_from_desain_md(): void
    {
        $destination = Destination::factory()->create([
            'name' => 'Gunung Bromo National Park',
            'category' => 'Alam',
            'city' => 'Probolinggo',
            'province' => 'Jawa Timur',
            'official_ticket_price' => 29000.00,
            'parking_fee_motor' => 5000.00,
            'parking_fee_car' => 10000.00,
            'cover_image' => 'destinations/bromo.jpg',
        ]);

        $user = User::factory()->create();
        Review::factory()->create([
            'destination_id' => $destination->id,
            'user_id' => $user->id,
            'rating' => 5,
            'review_text' => 'Pemandangan matahari terbit sangat magis!',
        ]);

        $destination->loadAvg('reviews', 'rating');
        $destination->loadCount('reviews');

        $rendered = Blade::render('<x-cards.destination-card :destination="$destination" />', ['destination' => $destination]);

        // 1. Tampilkan foto dan zoom effect
        $this->assertStringContainsString('destinations/bromo.jpg', $rendered);
        $this->assertStringContainsString('group-hover:scale-105', $rendered);

        // 2. Badge Kategori & Status Tiket Resmi
        $this->assertStringContainsString('Alam', $rendered);
        $this->assertStringContainsString('Tiket Resmi', $rendered);

        // 3. Nama tempat & Lokasi
        $this->assertStringContainsString('Gunung Bromo National Park', $rendered);
        $this->assertStringContainsString('Probolinggo, Jawa Timur', $rendered);

        // 4. Tarif tiket resmi
        $this->assertStringContainsString('Tiket Masuk Resmi', $rendered);
        $this->assertStringContainsString('Rp 29.000', $rendered);

        // 5. Tarif parkir motor & mobil
        $this->assertStringContainsString('Tarif Parkir Resmi', $rendered);
        $this->assertStringContainsString('Rp 5.000', $rendered);
        $this->assertStringContainsString('Rp 10.000', $rendered);

        // 6. Rating bintang & ulasan
        $this->assertStringContainsString('5.0', $rendered);
        $this->assertStringContainsString('(1)', $rendered);
    }

    public function test_destination_index_can_filter_by_category(): void
    {
        $alamDest = Destination::factory()->create([
            'name' => 'Ranu Kumbolo',
            'category' => 'Alam',
        ]);

        $bahariDest = Destination::factory()->create([
            'name' => 'Pantai Pink Lombok',
            'category' => 'Bahari',
        ]);

        $response = $this->get(route('destinations.index', ['category' => 'bahari']));

        $response->assertStatus(200);
        $response->assertSee('Pantai Pink Lombok');
        $response->assertDontSee('Ranu Kumbolo');
    }

    public function test_destination_index_can_search_by_keyword(): void
    {
        Destination::factory()->create([
            'name' => 'Candi Borobudur',
            'city' => 'Magelang',
        ]);

        Destination::factory()->create([
            'name' => 'Danau Toba',
            'city' => 'Samosir',
        ]);

        $response = $this->get(route('destinations.index', ['search' => 'Borobudur']));

        $response->assertStatus(200);
        $response->assertSee('Candi Borobudur');
        $response->assertDontSee('Danau Toba');
    }

    public function test_destination_show_page_can_be_rendered(): void
    {
        $destination = Destination::factory()->create([
            'name' => 'Kawah Putih Ciwidey',
            'slug' => 'kawah-putih-ciwidey',
            'category' => 'Alam',
            'official_ticket_price' => 28000,
        ]);

        $response = $this->get(route('destinations.show', $destination->slug));

        $response->assertStatus(200);
        $response->assertSee('Kawah Putih Ciwidey');
        $response->assertSee('Rp 28.000');
    }
}
