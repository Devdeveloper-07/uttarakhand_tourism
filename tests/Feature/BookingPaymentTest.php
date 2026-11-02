<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BookingPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.razorpay.key' => 'rzp_test_public',
            'services.razorpay.secret' => 'test_secret',
        ]);

        $this->addColumns('hotels', [
            'hotel_name' => fn ($table) => $table->string('hotel_name')->nullable(),
            'slug' => fn ($table) => $table->string('slug')->nullable(),
            'location' => fn ($table) => $table->string('location')->nullable(),
            'city' => fn ($table) => $table->string('city')->nullable(),
            'state' => fn ($table) => $table->string('state')->nullable(),
            'description' => fn ($table) => $table->text('description')->nullable(),
            'thumbnail' => fn ($table) => $table->string('thumbnail')->nullable(),
            'starting_price' => fn ($table) => $table->decimal('starting_price', 12, 2)->default(0),
            'rating' => fn ($table) => $table->decimal('rating', 3, 1)->default(0),
            'status' => fn ($table) => $table->boolean('status')->default(true),
        ]);

        $this->addColumns('packages', [
            'package_name' => fn ($table) => $table->string('package_name')->nullable(),
            'destination' => fn ($table) => $table->string('destination')->nullable(),
            'duration' => fn ($table) => $table->string('duration')->nullable(),
            'price' => fn ($table) => $table->decimal('price', 12, 2)->default(0),
            'discount_price' => fn ($table) => $table->decimal('discount_price', 12, 2)->nullable(),
            'max_person' => fn ($table) => $table->unsignedInteger('max_person')->nullable(),
            'thumbnail' => fn ($table) => $table->string('thumbnail')->nullable(),
            'description' => fn ($table) => $table->text('description')->nullable(),
            'includes' => fn ($table) => $table->text('includes')->nullable(),
            'excludes' => fn ($table) => $table->text('excludes')->nullable(),
            'itinerary' => fn ($table) => $table->text('itinerary')->nullable(),
            'featured' => fn ($table) => $table->boolean('featured')->default(false),
            'status' => fn ($table) => $table->boolean('status')->default(true),
        ]);
    }

    public function test_hotel_checkout_uses_server_calculated_stay_total(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Test hotel',
            'starting_price' => 200,
            'status' => true,
        ]);
        Http::fake([
            'api.razorpay.com/v1/orders' => Http::response([
                'id' => 'order_hotel_test',
                'amount' => 40000,
            ]),
        ]);

        $response = $this->post(route('hotel.booking.start', $hotel), [
            'check_in' => now()->addDays(3)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests' => 2,
            'rooms' => 1,
            'name' => 'Test Guest',
            'phone' => '+91 9876543210',
            'email' => 'guest@example.com',
        ]);

        $response->assertOk()->assertViewIs('frontend.payment.checkout');
        $this->assertDatabaseHas('bookings', [
            'item_type' => 'hotel',
            'item_id' => $hotel->id,
            'total_amount' => 400,
            'guest' => 2,
            'room_count' => 1,
        ]);
        $this->assertDatabaseHas('payments', [
            'gateway_order_id' => 'order_hotel_test',
            'amount' => 400,
            'payment_status' => 'created',
        ]);
        Http::assertSent(fn ($request) => $request->url() === 'https://api.razorpay.com/v1/orders'
            && $request['amount'] === 40000);
    }

    public function test_package_checkout_uses_discount_price_and_traveler_count(): void
    {
        $package = Package::create([
            'package_name' => 'Test package',
            'price' => 1000,
            'discount_price' => 700,
            'max_person' => 8,
            'status' => true,
        ]);
        Http::fake([
            'api.razorpay.com/v1/orders' => Http::response([
                'id' => 'order_package_test',
                'amount' => 210000,
            ]),
        ]);

        $response = $this->post(route('package.booking.start', $package), [
            'travel_date' => now()->addDays(10)->toDateString(),
            'travelers' => 3,
            'name' => 'Test Guest',
            'phone' => '+91 9876543210',
            'email' => 'guest@example.com',
        ]);

        $response->assertOk()->assertViewIs('frontend.payment.checkout');
        $this->assertDatabaseHas('bookings', [
            'item_type' => 'package',
            'item_id' => $package->id,
            'travel_date' => now()->addDays(10)->toDateString(),
            'total_amount' => 2100,
            'guest' => 3,
        ]);
        $this->assertDatabaseHas('booking_packages', [
            'package_id' => $package->id,
            'price' => 2100,
        ]);
        Http::assertSent(fn ($request) => $request->url() === 'https://api.razorpay.com/v1/orders'
            && $request['amount'] === 210000);
    }

    public function test_valid_gateway_signature_and_captured_payment_confirm_booking(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Test hotel',
            'starting_price' => 200,
            'status' => true,
        ]);
        $booking = Booking::create([
            'booking_no' => 'BK-TEST-1',
            'item_type' => 'hotel',
            'item_id' => $hotel->id,
            'guest' => 1,
            'room_count' => 1,
            'total_amount' => 200,
            'grand_total' => 200,
            'currency' => 'INR',
            'payment_status' => 'pending',
            'booking_status' => 'pending',
            'contact_name' => 'Test Guest',
            'contact_email' => 'guest@example.com',
            'contact_phone' => '+91 9876543210',
        ]);
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'gateway_name' => 'razorpay',
            'gateway_order_id' => 'order_verify_test',
            'currency' => 'INR',
            'amount' => 200,
            'payment_status' => 'created',
        ]);
        $signature = hash_hmac('sha256', 'order_verify_test|pay_verify_test', 'test_secret');

        Http::fake([
            'api.razorpay.com/v1/payments/pay_verify_test' => Http::response([
                'id' => 'pay_verify_test',
                'order_id' => 'order_verify_test',
                'amount' => 20000,
                'currency' => 'INR',
                'status' => 'captured',
                'method' => 'upi',
            ]),
        ]);

        $this->post(route('payments.verify', $payment), [
            'razorpay_payment_id' => 'pay_verify_test',
            'razorpay_order_id' => 'order_verify_test',
            'razorpay_signature' => $signature,
        ])
            ->assertOk()
            ->assertViewIs('frontend.payment.result')
            ->assertViewHas('status', 'success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'gateway_payment_id' => 'pay_verify_test',
            'payment_status' => 'paid',
            'payment_method' => 'upi',
        ]);
    }

    public function test_invalid_gateway_signature_does_not_confirm_or_change_payment(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Test hotel',
            'starting_price' => 200,
            'status' => true,
        ]);
        $booking = Booking::create([
            'booking_no' => 'BK-TEST-2',
            'item_type' => 'hotel',
            'item_id' => $hotel->id,
            'guest' => 1,
            'room_count' => 1,
            'total_amount' => 200,
            'grand_total' => 200,
            'currency' => 'INR',
            'payment_status' => 'pending',
            'booking_status' => 'pending',
        ]);
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'gateway_name' => 'razorpay',
            'gateway_order_id' => 'order_invalid_test',
            'currency' => 'INR',
            'amount' => 200,
            'payment_status' => 'created',
        ]);

        $this->post(route('payments.verify', $payment), [
            'razorpay_payment_id' => 'pay_invalid_test',
            'razorpay_order_id' => 'order_invalid_test',
            'razorpay_signature' => str_repeat('0', 64),
        ])
            ->assertOk()
            ->assertViewHas('status', 'failed');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'payment_status' => 'pending',
            'booking_status' => 'pending',
        ]);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => 'created',
        ]);
        Http::assertNothingSent();
    }

    private function addColumns(string $tableName, array $columns): void
    {
        foreach ($columns as $columnName => $defineColumn) {
            if (! Schema::hasColumn($tableName, $columnName)) {
                Schema::table($tableName, $defineColumn);
            }
        }
    }
}
