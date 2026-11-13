<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render(): void
    {
        foreach ([
            route('home'),
            route('hotels'),
            route('packages'),
            route('gallery'),
            route('about'),
            route('contact'),
            route('login'),
            route('register'),
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_index_and_create_pages_render(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        foreach ([
            'admin.dashboard',
            'admin.hotels.index', 'admin.hotels.create',
            'admin.rooms.index', 'admin.rooms.create',
            'admin.room-types.index', 'admin.room-types.create',
            'admin.packages.index', 'admin.packages.create',
            'admin.galleries.index', 'admin.galleries.create',
            'admin.testimonials.index', 'admin.testimonials.create',
            'admin.users.index', 'admin.users.create',
            'admin.bookings.index',
            'admin.payments.index',
            'admin.gst-settings.index',
        ] as $routeName) {
            $this->get(route($routeName))->assertOk();
        }
    }

    public function test_user_dashboard_and_history_pages_render(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        foreach ([
            'user.dashboard',
            'user.bookings',
            'user.hotels',
            'user.packages',
            'user.profile',
        ] as $routeName) {
            $this->get(route($routeName))->assertOk();
        }
    }
}
