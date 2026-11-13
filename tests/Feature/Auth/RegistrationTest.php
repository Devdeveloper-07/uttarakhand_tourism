<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'mobile' => '9876543210',
            'gender' => 'Other',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('user.dashboard', absolute: false));
    }

    public function test_registration_during_a_booking_returns_to_that_booking(): void
    {
        $response = $this->withSession([
            'booking.pending' => [
                'type' => 'package',
                'id' => 42,
                'form' => [
                    'travel_date' => now()->addDays(10)->toDateString(),
                    'travelers' => 2,
                ],
            ],
        ])->post('/register', [
            'name' => 'Booking User',
            'email' => 'booking-user@example.com',
            'mobile' => '9876543210',
            'gender' => 'Other',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('package.details', 42, absolute: false));
    }
}
