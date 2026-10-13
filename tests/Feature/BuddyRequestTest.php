<?php

namespace Tests\Feature;

use App\Models\BuddyRequest;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class BuddyRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_buddy_request_index_renders_feed_and_filter_bar(): void
    {
        $destination = Destination::factory()->create(['name' => 'Gunung Bromo']);
        $user = User::factory()->create(['name' => 'Andi Wijaya']);

        BuddyRequest::factory()->create([
            'destination_id' => $destination->id,
            'user_id' => $user->id,
            'title' => 'Patungan Sewa Jeep Bromo 4 Lokasi',
            'slots_needed' => 4,
            'slots_filled' => 2,
            'estimated_budget_per_person' => 250000,
            'status' => 'open',
        ]);

        $response = $this->get(route('buddy-requests.index'));

        $response->assertStatus(200);
        $response->assertSee('Papan Teman Jalan (Travel Buddy Board)');
        $response->assertSee('Cari Rekan Jalan');
        $response->assertSee('Patungan Sewa Jeep Bromo 4 Lokasi');
        $response->assertSee('Gunung Bromo');
        $response->assertSee('Rp 250.000');
        $response->assertSee('+ Buat Ajakan Patungan');
    }

    public function test_buddy_request_index_can_filter_by_destination(): void
    {
        $destBromo = Destination::factory()->create(['name' => 'Gunung Bromo']);
        $destKuta = Destination::factory()->create(['name' => 'Pantai Kuta']);

        BuddyRequest::factory()->create([
            'destination_id' => $destBromo->id,
            'title' => 'Trip Bromo Sunrise Hemat',
        ]);

        BuddyRequest::factory()->create([
            'destination_id' => $destKuta->id,
            'title' => 'Surfing Bersama di Kuta',
        ]);

        $response = $this->get(route('buddy-requests.index', ['destination_id' => $destBromo->id]));

        $response->assertStatus(200);
        $response->assertSee('Trip Bromo Sunrise Hemat');
        $response->assertDontSee('Surfing Bersama di Kuta');
    }

    public function test_buddy_request_index_can_filter_by_budget_range(): void
    {
        $dest = Destination::factory()->create();

        BuddyRequest::factory()->create([
            'destination_id' => $dest->id,
            'title' => 'Trip Backpacker Murah',
            'estimated_budget_per_person' => 150000,
        ]);

        BuddyRequest::factory()->create([
            'destination_id' => $dest->id,
            'title' => 'Trip Kapal Phinisi Mewah',
            'estimated_budget_per_person' => 2500000,
        ]);

        $response = $this->get(route('buddy-requests.index', ['budget_range' => 'under_250k']));

        $response->assertStatus(200);
        $response->assertSee('Trip Backpacker Murah');
        $response->assertDontSee('Trip Kapal Phinisi Mewah');
    }

    public function test_buddy_request_index_can_filter_by_status(): void
    {
        $dest = Destination::factory()->create();

        BuddyRequest::factory()->create([
            'destination_id' => $dest->id,
            'title' => 'Trip Masih Buka Slot',
            'status' => 'open',
            'slots_needed' => 4,
            'slots_filled' => 2,
        ]);

        BuddyRequest::factory()->create([
            'destination_id' => $dest->id,
            'title' => 'Trip Sudah Penuh Rombongan',
            'status' => 'full',
            'slots_needed' => 4,
            'slots_filled' => 4,
        ]);

        $response = $this->get(route('buddy-requests.index', ['status' => 'open']));

        $response->assertStatus(200);
        $response->assertSee('Trip Masih Buka Slot');
        $response->assertDontSee('Trip Sudah Penuh Rombongan');
    }

    public function test_buddy_card_component_renders_visual_specs_from_desain_md(): void
    {
        $user = User::factory()->create([
            'name' => 'Rina Nose',
            'reputation_points' => 35,
        ]);

        $dest = Destination::factory()->create([
            'name' => 'Labuan Bajo',
            'city' => 'Manggarai Barat',
            'province' => 'Nusa Tenggara Timur',
        ]);

        $buddyRequest = BuddyRequest::factory()->create([
            'user_id' => $user->id,
            'destination_id' => $dest->id,
            'title' => 'Sewa Speedboat One Day Trip Komodo',
            'description' => 'Mencari 2 orang teman jalan patungan kapal speedboat keliling pulau Padar dan Komodo.',
            'departure_date' => now()->addDays(5)->toDateString(),
            'return_date' => now()->addDays(6)->toDateString(),
            'slots_needed' => 4,
            'slots_filled' => 2,
            'estimated_budget_per_person' => 650000,
            'status' => 'open',
            'contact_link' => '081234567890',
        ]);

        $rendered = Blade::render(
            '<x-cards.buddy-card :buddyRequest="$buddyRequest" />',
            ['buddyRequest' => $buddyRequest]
        );

        // 1. Host header, reputation, & status
        $this->assertStringContainsString('Rina Nose', $rendered);
        $this->assertStringContainsString('★ 35', $rendered);
        $this->assertStringContainsString('Open Trip', $rendered);

        // 2. Destinasi & Tanggal
        $this->assertStringContainsString('Labuan Bajo', $rendered);
        $this->assertStringContainsString('Manggarai Barat', $rendered);

        // 3. Visual Slot progress: Sisa 2 dari 4 Kursi
        $this->assertStringContainsString('Sisa', $rendered);
        $this->assertStringContainsString('2', $rendered);
        $this->assertStringContainsString('dari 4 Kursi', $rendered);

        // 4. Highlight estimasi bujet IDR tebal
        $this->assertStringContainsString('Rp 650.000', $rendered);
        $this->assertStringContainsString('/org', $rendered);

        // 5. Tombol CTA WhatsApp Host (wa.me) dengan format pesan otomatis
        $this->assertStringContainsString('https://wa.me/6281234567890', $rendered);
        $this->assertStringContainsString('Join WA', $rendered);
    }

    public function test_user_can_view_create_buddy_request_page(): void
    {
        Destination::factory()->count(3)->create();

        $response = $this->get(route('buddy-requests.create'));

        $response->assertStatus(200);
        $response->assertSee('Buat Ajakan Perjalanan Bersama');
        $response->assertSee('Destinasi Tujuan');
        $response->assertSee('Estimasi Bujet per Orang (Rp)');
        $response->assertSee('Kontak WhatsApp');
    }

    public function test_user_can_create_buddy_request_and_gain_reputation(): void
    {
        $user = User::factory()->create(['reputation_points' => 10]);
        $destination = Destination::factory()->create();

        $response = $this->actingAs($user)->post(route('buddy-requests.store'), [
            'destination_id' => $destination->id,
            'title' => 'Patungan Rafting Sungai Elo Magelang',
            'description' => 'Butuh 3 orang lagi untuk 1 boat rafting paket akhir pekan.',
            'departure_date' => now()->addDays(7)->toDateString(),
            'return_date' => now()->addDays(8)->toDateString(),
            'slots_needed' => 6,
            'slots_filled' => 3,
            'estimated_budget_per_person' => 175000,
            'contact_link' => 'https://wa.me/6281298765432',
        ]);

        $buddyRequest = BuddyRequest::where('title', 'Patungan Rafting Sungai Elo Magelang')->first();
        $this->assertNotNull($buddyRequest);
        $response->assertRedirect(route('buddy-requests.show', $buddyRequest));

        $user->refresh();
        $this->assertSame(15, $user->reputation_points);
        $this->assertSame('open', $buddyRequest->status);
        $this->assertSame(3, $buddyRequest->remaining_slots);
    }

    public function test_buddy_request_validation_fails_for_invalid_input(): void
    {
        $response = $this->post(route('buddy-requests.store'), [
            'destination_id' => 99999,
            'title' => '',
            'departure_date' => now()->subDays(5)->toDateString(), // Past date
            'slots_needed' => 0,
            'estimated_budget_per_person' => -5000,
            'contact_link' => '',
        ]);

        $response->assertSessionHasErrors([
            'destination_id',
            'title',
            'departure_date',
            'slots_needed',
            'estimated_budget_per_person',
            'contact_link',
        ]);
    }

    public function test_user_can_view_buddy_request_detail_page(): void
    {
        $destination = Destination::factory()->create(['name' => 'Kawah Ijen']);
        $user = User::factory()->create(['name' => 'Bayu Skak']);

        $buddyRequest = BuddyRequest::factory()->create([
            'destination_id' => $destination->id,
            'user_id' => $user->id,
            'title' => 'Midnight Trekking Blue Fire Ijen',
            'description' => 'Mendaki bareng jam 01.00 pagi untuk melihat fenomena blue fire.',
            'slots_needed' => 5,
            'slots_filled' => 2,
            'estimated_budget_per_person' => 200000,
            'status' => 'open',
            'contact_link' => '081233445566',
        ]);

        $response = $this->get(route('buddy-requests.show', $buddyRequest));

        $response->assertStatus(200);
        $response->assertSee('Midnight Trekking Blue Fire Ijen');
        $response->assertSee('Kawah Ijen');
        $response->assertSee('Bayu Skak');
        $response->assertSee('Rp 200.000');
        $response->assertSee('Sisa 3 Kursi');
        $response->assertSee('Hubungi Host via WA');
    }
}
