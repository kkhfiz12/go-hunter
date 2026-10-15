<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Destination;
use App\Models\Trail;
use App\Models\TrailCheckpoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrbanRallyTest extends TestCase
{
    use RefreshDatabase;

    private City $city;

    private Destination $destination;

    private Trail $trail;

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

        $this->trail = Trail::create([
            'city_id' => $this->city->id,
            'destination_id' => $this->destination->id,
            'title' => 'Jelajah Monas via MRT & TransJakarta',
            'slug' => 'jelajah-monas-via-mrt-transjakarta',
            'transport_modes' => ['mrt', 'transjakarta', 'walking'],
            'is_recommended' => true,
            'recommendation_reason' => 'Paling efektif naik MRT lalu sambung TransJakarta Koridor 1',
            'estimated_time' => '35 Menit',
            'total_distance' => '4.8 km',
            'estimated_cost' => 'Rp 7.000',
            'description' => 'Rute transit cepat bebas macet menuju Monas.',
        ]);

        TrailCheckpoint::create([
            'trail_id' => $this->trail->id,
            'step_order' => 1,
            'mode' => 'mrt',
            'stop_name' => 'Stasiun MRT Bundaran HI',
            'instruction' => 'Naik MRT arah Bundaran HI.',
            'duration_minutes' => 7,
            'latitude' => -6.19312500,
            'longitude' => 106.82343800,
        ]);

        TrailCheckpoint::create([
            'trail_id' => $this->trail->id,
            'step_order' => 2,
            'mode' => 'transjakarta',
            'stop_name' => 'Halte TransJakarta Monas',
            'instruction' => 'Turun di Halte Monas.',
            'duration_minutes' => 15,
            'latitude' => -6.17540000,
            'longitude' => 106.82700000,
        ]);
    }

    public function test_urban_rally_index_page_is_accessible(): void
    {
        $response = $this->get(route('urban-rally.index'));

        $response->assertStatus(200);
        $response->assertSee('Urban Rally');
        $response->assertSee('Jelajah Monas via MRT &amp; TransJakarta', false);
        $response->assertSee('Paling efektif naik MRT lalu sambung TransJakarta Koridor 1');
    }

    public function test_urban_rally_index_filters_by_city(): void
    {
        $bandung = City::create([
            'name' => 'Kota Bandung',
            'slug' => 'bandung',
            'latitude' => -6.91750000,
            'longitude' => 107.61910000,
        ]);

        $bandungTrail = Trail::create([
            'city_id' => $bandung->id,
            'title' => 'Heritage Gedung Sate via Bus',
            'slug' => 'heritage-gedung-sate-via-bus',
            'transport_modes' => ['bus', 'walking'],
            'is_recommended' => true,
            'estimated_time' => '30 Menit',
            'total_distance' => '4.0 km',
            'estimated_cost' => 'Rp 4.900',
        ]);

        // Filter by Jakarta
        $responseJakarta = $this->get(route('urban-rally.index', ['city' => 'jakarta']));
        $responseJakarta->assertStatus(200);
        $responseJakarta->assertSee('Jelajah Monas via MRT &amp; TransJakarta', false);

        // Filter by Bandung
        $responseBandung = $this->get(route('urban-rally.index', ['city' => 'bandung']));
        $responseBandung->assertStatus(200);
        $responseBandung->assertSee('Heritage Gedung Sate via Bus');
    }

    public function test_urban_rally_show_page_displays_timeline_and_checkpoints(): void
    {
        $response = $this->get(route('urban-rally.show', $this->trail));

        $response->assertStatus(200);
        $response->assertSee('Jelajah Monas via MRT &amp; TransJakarta', false);
        $response->assertSee('Stasiun MRT Bundaran HI');
        $response->assertSee('Halte TransJakarta Monas');
        $response->assertSee('Naik MRT arah Bundaran HI.');
        $response->assertSee('35 Menit');
        $response->assertSee('4.8 km');
        $response->assertSee('Rp 7.000');
    }

    public function test_trail_relations_and_mode_metadata(): void
    {
        $this->assertCount(2, $this->trail->checkpoints);
        $this->assertEquals('DKI Jakarta', $this->trail->city->name);
        $this->assertEquals('Monumen Nasional', $this->trail->destination->name);

        $metadata = $this->trail->modes_metadata;
        $this->assertIsArray($metadata);
        $this->assertCount(3, $metadata);
        $this->assertEquals('MRT', $metadata[0]['label']);
        $this->assertEquals('TransJakarta', $metadata[1]['label']);
        $this->assertEquals('Jalan Kaki', $metadata[2]['label']);
    }
}
