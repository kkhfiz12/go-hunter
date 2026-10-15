<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Destination;
use App\Models\Spot;
use App\Models\User;
use App\Models\UserCheckin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripyTest extends TestCase
{
    use RefreshDatabase;

    private City $city;

    private Destination $destination;

    private Spot $spot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->city = City::create([
            'name' => 'DKI Jakarta',
            'slug' => 'jakarta',
            'latitude' => -6.20880000,
            'longitude' => 106.84560000,
        ]);

        $this->destination = Destination::create([
            'city_id' => $this->city->id,
            'name' => 'Monumen Nasional',
            'slug' => 'monumen-nasional',
            'category' => 'Budaya',
            'type' => 'landmark',
            'short_description' => 'Ikon kebanggaan ibu kota setinggi 132 meter.',
            'city' => 'Jakarta Pusat',
            'province' => 'DKI Jakarta',
            'address' => 'Gambir, Jakarta Pusat',
            'latitude' => -6.17539240,
            'longitude' => 106.82715280,
            'description' => 'Monumen Nasional atau Monas didirikan untuk mengenang perjuangan rakyat.',
            'official_ticket_price' => 15000,
        ]);

        $this->spot = Spot::create([
            'destination_id' => $this->destination->id,
            'name' => 'Lenggang Jakarta Kuliner',
            'category' => 'kuliner',
            'short_description' => 'Pusat kuliner legendaris Betawi.',
            'latitude' => -6.17750000,
            'longitude' => 106.82850000,
            'points_reward' => 15,
        ]);
    }

    public function test_tripy_index_page_renders_successfully(): void
    {
        $response = $this->get(route('tripy.index'));

        $response->assertOk();
        $response->assertSee('TRIPY');
        $response->assertSee('DKI Jakarta');
        $response->assertSee('Monumen Nasional');
        $response->assertSee('XP Penjelajah');
    }

    public function test_tripy_city_data_endpoint_returns_json(): void
    {
        $response = $this->getJson(route('tripy.cities.data', $this->city->slug));

        $response->assertOk();
        $response->assertJsonPath('city.name', 'DKI Jakarta');
        $response->assertJsonPath('destinations.0.name', 'Monumen Nasional');
        $response->assertJsonPath('destinations.0.spots.0.name', 'Lenggang Jakarta Kuliner');
    }

    public function test_tripy_spots_endpoint_returns_spots(): void
    {
        $response = $this->getJson(route('tripy.destinations.spots', $this->destination->id));

        $response->assertOk();
        $response->assertJsonPath('destination_id', $this->destination->id);
        $response->assertJsonPath('spots.0.name', 'Lenggang Jakarta Kuliner');
        $response->assertJsonPath('spots.0.category', 'kuliner');
    }

    public function test_authenticated_user_can_checkin_and_claim_xp(): void
    {
        $user = User::factory()->create([
            'reputation_points' => 20,
        ]);

        $response = $this->actingAs($user)->postJson(route('tripy.spots.checkin', $this->spot->id));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'points_earned' => 15,
            'total_points' => 35,
            'spot_id' => $this->spot->id,
            'is_checked_in' => true,
        ]);

        $this->assertDatabaseHas('user_checkins', [
            'user_id' => $user->id,
            'spot_id' => $this->spot->id,
            'points_earned' => 15,
        ]);

        $this->assertEquals(35, $user->fresh()->reputation_points);
    }

    public function test_user_cannot_checkin_twice_to_same_spot(): void
    {
        $user = User::factory()->create([
            'reputation_points' => 20,
        ]);

        UserCheckin::create([
            'user_id' => $user->id,
            'spot_id' => $this->spot->id,
            'points_earned' => 15,
        ]);

        $response = $this->actingAs($user)->postJson(route('tripy.spots.checkin', $this->spot->id));

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'is_checked_in' => true,
        ]);
    }

    public function test_guest_cannot_checkin(): void
    {
        $response = $this->postJson(route('tripy.spots.checkin', $this->spot->id));

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'require_login' => true,
        ]);
    }
}
