<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class LayoutAndComponentTest extends TestCase
{
    public function test_badge_component_renders_correct_types(): void
    {
        $renderedFair = Blade::render('<x-ui.badge type="fair">Harga Wajar</x-ui.badge>');
        $this->assertStringContainsString('Harga Wajar', $renderedFair);
        $this->assertStringContainsString('bg-emerald-50', $renderedFair);
        $this->assertStringContainsString('text-emerald-700', $renderedFair);

        $renderedGetok = Blade::render('<x-ui.badge type="overpriced">Waspada Getok</x-ui.badge>');
        $this->assertStringContainsString('Waspada Getok', $renderedGetok);
        $this->assertStringContainsString('bg-red-50', $renderedGetok);
        $this->assertStringContainsString('text-red-700', $renderedGetok);

        $renderedWarning = Blade::render('<x-ui.badge type="warning">Cukup Wajar</x-ui.badge>');
        $this->assertStringContainsString('bg-amber-50', $renderedWarning);

        $renderedPrimary = Blade::render('<x-ui.badge type="primary">Populer</x-ui.badge>');
        $this->assertStringContainsString('bg-sky-50', $renderedPrimary);
    }

    public function test_badge_component_with_dot(): void
    {
        $rendered = Blade::render('<x-ui.badge type="fair" :dot="true">Wajar</x-ui.badge>');
        $this->assertStringContainsString('<circle', $rendered);
        $this->assertStringContainsString('fill-emerald-500', $rendered);
    }

    public function test_navbar_component_renders_all_required_menu_items(): void
    {
        $rendered = Blade::render('<x-navigation.navbar />');
        $this->assertStringContainsString('Go', $rendered);
        $this->assertStringContainsString('Hunter', $rendered);
        $this->assertStringContainsString('Destinasi', $rendered);
        $this->assertStringContainsString('Cek Harga Wajar', $rendered);
        $this->assertStringContainsString('Teman Jalan', $rendered);
        $this->assertStringContainsString('Diskusi', $rendered);
        $this->assertStringContainsString('Lapor Harga', $rendered);
    }

    public function test_app_layout_renders_google_fonts_and_slot(): void
    {
        $rendered = Blade::render(
            '<x-layouts.app title="Halaman Uji"><div>Konten Uji Go Hunter</div></x-layouts.app>'
        );

        $this->assertStringContainsString('Halaman Uji — Go Hunter', $rendered);
        $this->assertStringContainsString('fonts.googleapis.com', $rendered);
        $this->assertStringContainsString('Plus+Jakarta+Sans', $rendered);
        $this->assertStringContainsString('Konten Uji Go Hunter', $rendered);
        $this->assertStringContainsString('Destinasi', $rendered);
        $this->assertStringContainsString('Cek Harga Wajar', $rendered);
        $this->assertStringContainsString('Teman Jalan', $rendered);
        $this->assertStringContainsString('Diskusi', $rendered);
    }
}
