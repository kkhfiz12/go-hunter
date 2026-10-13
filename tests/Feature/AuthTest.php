<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang Kembali');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'email' => 'traveler@gohunter.id',
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => 'traveler@gohunter.id',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'traveler@gohunter.id',
            'password' => 'password123',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'traveler@gohunter.id',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Gabung Menjadi Hunter');
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Hunter',
            'email' => 'budi@gohunter.id',
            'phone_number' => '081234567890',
            'password' => 'hunterpassword123',
            'password_confirmation' => 'hunterpassword123',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'email' => 'budi@gohunter.id',
            'name' => 'Budi Hunter',
            'phone_number' => '081234567890',
            'reputation_points' => 10,
        ]);
    }

    public function test_registration_requires_terms_acceptance(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Budi Hunter',
            'email' => 'budi2@gohunter.id',
            'password' => 'hunterpassword123',
            'password_confirmation' => 'hunterpassword123',
            // terms missing
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('terms');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('home'));
    }

    public function test_authenticated_user_is_redirected_away_from_guest_pages(): void
    {
        $user = User::factory()->create();

        $loginResponse = $this->actingAs($user)->get('/login');
        $loginResponse->assertRedirect();

        $registerResponse = $this->actingAs($user)->get('/register');
        $registerResponse->assertRedirect();
    }
}
