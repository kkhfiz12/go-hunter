<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DualLevelNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_route_is_accessible(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Go');
        $response->assertSee('Hunter');
    }

    public function test_top_navbar_renders_main_categories_as_clickable_links(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Top navbar module direct links
        $response->assertSee(route('dashboard'));
        $response->assertSee(route('tripy.index'));
        $response->assertSee(route('eksplor-pedia.index'));
        $response->assertSee(route('urban-rally.index'));
    }

    public function test_dynamic_sidebar_sub_menus_exist_in_layout(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Dashboard sub-menu items
        $response->assertSee('Destinasi');
        $response->assertSee('Cek Harga Wajar');
        $response->assertSee('Teman Jalan');
        $response->assertSee('Diskusi');

        // TRIPY sub-menu items
        $response->assertSee('Peta Interaktif');
        $response->assertSee('Filter Kota');
        $response->assertSee('Spot Rekomendasi');
        $response->assertSee('Riwayat Check-in');

        // Eksplor Pedia sub-menu items
        $response->assertSee('Katalog Kuis Kota');
        $response->assertSee('Leaderboard');
        $response->assertSee('Tukar Reward');

        // Urban Rally sub-menu items
        $response->assertSee('Pilih Transportasi');
        $response->assertSee('Rekomendasi Rute');
    }

    public function test_logo_has_dashboard_target(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('dashboard'));
    }

    public function test_hero_blinking_badge_is_removed(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertDontSee('animate-ping');
        $response->assertDontSee('Super-App Wisata Bebas Getok Harga');
    }
}
