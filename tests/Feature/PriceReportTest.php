<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\PriceReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PriceReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_destination_fairness_percentage_calculation(): void
    {
        $destination = Destination::factory()->create();

        // 0 reports -> 0%
        $this->assertSame(0, $destination->fairnessPercentage());
        $this->assertSame(0, $destination->fairness_percentage);

        // 2 fair, 1 cheap, 1 overpriced -> 3/4 = 75%
        PriceReport::factory()->create([
            'destination_id' => $destination->id,
            'fairness_level' => 'fair',
        ]);
        PriceReport::factory()->create([
            'destination_id' => $destination->id,
            'fairness_level' => 'fair',
        ]);
        PriceReport::factory()->create([
            'destination_id' => $destination->id,
            'fairness_level' => 'cheap',
        ]);
        PriceReport::factory()->create([
            'destination_id' => $destination->id,
            'fairness_level' => 'overpriced',
        ]);

        $destination->unsetRelation('priceReports');
        $this->assertSame(75, $destination->fairnessPercentage());
        $this->assertSame(75, $destination->fairness_percentage);
    }

    public function test_destination_price_reports_breakdown(): void
    {
        $destination = Destination::factory()->create();

        PriceReport::factory()->count(2)->create([
            'destination_id' => $destination->id,
            'fairness_level' => 'cheap',
        ]);
        PriceReport::factory()->count(3)->create([
            'destination_id' => $destination->id,
            'fairness_level' => 'fair',
        ]);
        PriceReport::factory()->count(1)->create([
            'destination_id' => $destination->id,
            'fairness_level' => 'overpriced',
        ]);

        $breakdown = $destination->priceReportsBreakdown();

        $this->assertSame(2, $breakdown['cheap']);
        $this->assertSame(3, $breakdown['fair']);
        $this->assertSame(1, $breakdown['overpriced']);
        $this->assertSame(6, $breakdown['total']);
    }

    public function test_user_can_submit_price_report_and_gains_five_reputation_points(): void
    {
        $user = User::factory()->create(['reputation_points' => 10]);
        $destination = Destination::factory()->create(['slug' => 'pantai-parangtritis']);

        $response = $this->actingAs($user)->post(route('destinations.price-reports.store', $destination), [
            'item_name' => 'Kelapa Muda Dingin',
            'price_paid' => 15000,
            'fairness_level' => 'fair',
            'notes' => 'Beli di warung pinggir pantai, harga sangat wajar.',
        ]);

        $response->assertRedirect(route('destinations.show', $destination));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('price_reports', [
            'destination_id' => $destination->id,
            'user_id' => $user->id,
            'item_name' => 'Kelapa Muda Dingin',
            'price_paid' => 15000,
            'fairness_level' => 'fair',
            'notes' => 'Beli di warung pinggir pantai, harga sangat wajar.',
        ]);

        $user->refresh();
        $this->assertSame(15, $user->reputation_points);
    }

    public function test_user_can_submit_price_report_with_proof_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $destination = Destination::factory()->create();
        $image = UploadedFile::fake()->image('nota_pembelian.jpg', 600, 600);

        $response = $this->actingAs($user)->post(route('destinations.price-reports.store', $destination), [
            'item_name' => 'Parkir Mobil Liar',
            'price_paid' => 25000,
            'fairness_level' => 'overpriced',
            'proof_image' => $image,
            'notes' => 'Petugas liar tanpa karcis resmi mematok 25rb.',
        ]);

        $response->assertRedirect(route('destinations.show', $destination));

        $report = PriceReport::where('destination_id', $destination->id)->first();
        $this->assertNotNull($report);
        $this->assertNotNull($report->proof_image);
        Storage::disk('public')->assertExists($report->proof_image);
    }

    public function test_price_report_validation_errors(): void
    {
        $destination = Destination::factory()->create();

        $response = $this->post(route('destinations.price-reports.store', $destination), [
            'item_name' => '',
            'price_paid' => -1000,
            'fairness_level' => 'invalid_level',
        ]);

        $response->assertSessionHasErrors(['item_name', 'price_paid', 'fairness_level']);
    }

    public function test_price_fairness_meter_component_renders_correctly(): void
    {
        $destination = Destination::factory()->create();

        PriceReport::factory()->create([
            'destination_id' => $destination->id,
            'fairness_level' => 'fair',
        ]);

        $rendered = Blade::render(
            '<x-destination.price-fairness-meter :destination="$destination" />',
            ['destination' => $destination]
        );

        $this->assertStringContainsString('Anti-Getok Meter', $rendered);
        $this->assertStringContainsString('100% Pengunjung Melaporkan Harga Wajar', $rendered);
        $this->assertStringContainsString('bg-fairness-fair', $rendered);
        $this->assertStringContainsString('+ Lapor Harga Riil', $rendered);
    }

    public function test_price_report_card_component_renders_correctly(): void
    {
        $user = User::factory()->create(['name' => 'Budi Santoso']);
        $report = PriceReport::factory()->create([
            'user_id' => $user->id,
            'item_name' => 'Es Kelapa Ijo',
            'price_paid' => 12000,
            'fairness_level' => 'cheap',
            'notes' => 'Porsi jumbo dan manis alami',
        ]);

        $rendered = Blade::render(
            '<x-cards.price-report-card :report="$report" />',
            ['report' => $report]
        );

        $this->assertStringContainsString('Es Kelapa Ijo', $rendered);
        $this->assertStringContainsString('Rp 12.000', $rendered);
        $this->assertStringContainsString('Harga Murah / Hemat', $rendered);
        $this->assertStringContainsString('Budi Santoso', $rendered);
        $this->assertStringContainsString('bg-fairness-fair-bg', $rendered);
        $this->assertStringContainsString('Porsi jumbo dan manis alami', $rendered);
    }

    public function test_report_price_modal_component_renders_form_fields(): void
    {
        $destination = Destination::factory()->create(['name' => 'Candi Prambanan']);

        $rendered = Blade::render(
            '<x-destination.report-price-modal :destination="$destination" />',
            ['destination' => $destination]
        );

        $this->assertStringContainsString('Candi Prambanan', $rendered);
        $this->assertStringContainsString('+5 Poin Reputasi', $rendered);
        $this->assertStringContainsString('name="item_name"', $rendered);
        $this->assertStringContainsString('name="price_paid"', $rendered);
        $this->assertStringContainsString('name="fairness_level"', $rendered);
        $this->assertStringContainsString('value="cheap"', $rendered);
        $this->assertStringContainsString('value="fair"', $rendered);
        $this->assertStringContainsString('value="overpriced"', $rendered);
        $this->assertStringContainsString('name="proof_image"', $rendered);
    }

    public function test_destination_show_page_displays_price_checker_module(): void
    {
        $destination = Destination::factory()->create([
            'name' => 'Pantai Indrayanti',
            'slug' => 'pantai-indrayanti',
        ]);

        $user = User::factory()->create(['name' => 'Dewi Lestari']);
        PriceReport::factory()->create([
            'destination_id' => $destination->id,
            'user_id' => $user->id,
            'item_name' => 'Sewa Payung Pantai',
            'price_paid' => 20000,
            'fairness_level' => 'fair',
        ]);

        $response = $this->get(route('destinations.show', $destination->slug));

        $response->assertStatus(200);
        $response->assertSee('Anti-Getok Meter');
        $response->assertSee('Katalog Harga Riil Pengunjung');
        $response->assertSee('Sewa Payung Pantai');
        $response->assertSee('Rp 20.000');
        $response->assertSee('Dewi Lestari');
    }
}
