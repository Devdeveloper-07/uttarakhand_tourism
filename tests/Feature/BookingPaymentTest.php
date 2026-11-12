<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelGstSlab;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
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
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 200,
            'status' => true,
        ]);
        Http::fake([
            'api.razorpay.com/v1/orders' => Http::response([
                'id' => 'order_hotel_test',
                'amount' => 40000,
                'currency' => 'INR',
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
            'destination' => 'Test destination',
            'duration' => 3,
            'price' => 1000,
            'discount_price' => 700,
            'max_person' => 8,
            'description' => 'Test package description',
            'status' => true,
        ]);
        Http::fake([
            'api.razorpay.com/v1/orders' => Http::response([
                'id' => 'order_package_test',
                'amount' => 247800,
                'currency' => 'INR',
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
            'total_amount' => 2100,
            'gst_amount' => 378,
            'grand_total' => 2478,
            'gst_category' => 'tour_operator_with_itc',
            'guest' => 3,
        ]);
        $this->assertSame(now()->addDays(10)->toDateString(), Booking::query()->where('item_type', 'package')->firstOrFail()->travel_date->toDateString());
        $this->assertDatabaseHas('booking_packages', [
            'package_id' => $package->id,
            'price' => 2100,
        ]);
        $this->assertDatabaseHas('payments', [
            'booking_id' => Booking::query()->where('item_type', 'package')->value('id'),
            'base_amount' => 2100,
            'gst_amount' => 378,
            'amount' => 2478,
        ]);
        Http::assertSent(fn ($request) => $request->url() === 'https://api.razorpay.com/v1/orders'
            && $request['amount'] === 247800);
    }

    public function test_valid_gateway_signature_and_captured_payment_confirm_booking(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Test hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
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

        $payload = [
            'razorpay_payment_id' => 'pay_verify_test',
            'razorpay_order_id' => 'order_verify_test',
            'razorpay_signature' => $signature,
        ];

        $this->post(route('payments.verify', $payment), $payload)
            ->assertOk()
            ->assertViewIs('frontend.payment.result')
            ->assertViewHas('status', 'success');

        $this->post(route('payments.verify', $payment), $payload)
            ->assertOk()
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
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
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

    public function test_gateway_amount_mismatch_does_not_confirm_booking(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Test hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 210,
            'status' => true,
        ]);
        $booking = Booking::create([
            'booking_no' => 'BK-AMOUNT-MISMATCH',
            'item_type' => 'hotel',
            'item_id' => $hotel->id,
            'guest' => 1,
            'room_count' => 1,
            'total_amount' => 200,
            'gst_amount' => 10,
            'grand_total' => 210,
            'currency' => 'INR',
            'payment_status' => 'pending',
            'booking_status' => 'pending',
        ]);
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'gateway_name' => 'razorpay',
            'gateway_order_id' => 'order_amount_mismatch',
            'currency' => 'INR',
            'amount' => 210,
            'payment_status' => 'created',
        ]);
        $paymentId = 'pay_amount_mismatch';
        $signature = hash_hmac('sha256', 'order_amount_mismatch|'.$paymentId, 'test_secret');

        Http::fake([
            'api.razorpay.com/v1/payments/'.$paymentId => Http::response([
                'id' => $paymentId,
                'order_id' => 'order_amount_mismatch',
                'amount' => 20999,
                'currency' => 'INR',
                'status' => 'captured',
            ]),
        ]);

        $this->post(route('payments.verify', $payment), [
            'razorpay_payment_id' => $paymentId,
            'razorpay_order_id' => 'order_amount_mismatch',
            'razorpay_signature' => $signature,
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
    }

    public function test_signed_paid_booking_invoice_shows_saved_gst_breakdown(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Invoice hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Invoice test hotel',
            'starting_price' => 3000,
            'status' => true,
        ]);
        $booking = Booking::create([
            'booking_no' => 'BK-INVOICE-TEST',
            'item_type' => 'hotel',
            'item_id' => $hotel->id,
            'guest' => 1,
            'room_count' => 1,
            'total_amount' => 3000,
            'taxable_amount' => 3000,
            'gst_percentage' => 5,
            'gst_amount' => 150,
            'gst_category' => 'hotel_accommodation_tariff',
            'gst_breakdown' => [[
                'description' => 'Standard room',
                'quantity' => 1,
                'nights' => 1,
                'unit_price' => 3000,
                'base_amount' => 3000,
                'gst_percentage' => 5,
                'gst_amount' => 150,
            ]],
            'grand_total' => 3150,
            'currency' => 'INR',
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
            'contact_name' => 'Invoice Guest',
            'contact_email' => 'invoice@example.com',
        ]);
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'gateway_name' => 'razorpay',
            'gateway_order_id' => 'order_invoice_test',
            'gateway_payment_id' => 'pay_invoice_test',
            'transaction_id' => 'pay_invoice_test',
            'currency' => 'INR',
            'base_amount' => 3000,
            'gst_percentage' => 5,
            'gst_amount' => 150,
            'amount' => 3150,
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);
        $url = URL::temporarySignedRoute(
            'payments.invoice',
            now()->addDay(),
            ['payment' => $payment]
        );

        $this->get($url)
            ->assertOk()
            ->assertViewIs('frontend.payment.invoice')
            ->assertSee('BK-INVOICE-TEST')
            ->assertSee('5.00%')
            ->assertSee('GST total')
            ->assertSee('3,150.00')
            ->assertSee('No CGST/SGST or IGST split');
    }

    public function test_admin_can_update_contiguous_hotel_gst_slabs(): void
    {
        $admin = User::create([
            'name' => 'GST Admin',
            'email' => 'gst-admin@example.com',
            'password' => 'test-password',
            'role' => 'admin',
        ]);
        $slabs = HotelGstSlab::query()->orderBy('min_tariff')->get();

        $response = $this->actingAs($admin)->put(route('admin.gst-settings.hotel-slabs.update'), [
            'slabs' => [
                $slabs[0]->id => ['min_tariff' => 0, 'max_tariff' => 1000, 'gst_percentage' => 0],
                $slabs[1]->id => ['min_tariff' => 1000.01, 'max_tariff' => 7500, 'gst_percentage' => 5],
                $slabs[2]->id => ['min_tariff' => 7500.01, 'max_tariff' => null, 'gst_percentage' => 18],
            ],
        ]);

        $response->assertRedirect(route('admin.gst-settings.index'));
        $this->assertSame(3, HotelGstSlab::query()->count());
        $this->assertDatabaseHas('hotel_gst_slabs', [
            'id' => $slabs[1]->id,
            'gst_percentage' => 5,
        ]);
    }

    public function test_hotel_tariff_slabs_apply_to_each_room_night(): void
    {
        $rates = [
            800 => [0, 800],
            1000 => [0, 1000],
            1500 => [5, 1575],
            3000 => [5, 3150],
            7500 => [5, 7875],
            8000 => [18, 9440],
        ];
        $order = 0;
        Http::fake(function (ClientRequest $request) use (&$order) {
            $order++;

            return Http::response([
                'id' => 'order_hotel_gst_'.$order,
                'amount' => $request['amount'],
                'currency' => 'INR',
            ]);
        });

        foreach ($rates as $tariff => [$rate, $payable]) {
            $hotel = Hotel::create([
                'hotel_name' => 'GST hotel '.$tariff,
                'location' => 'Test location',
                'city' => 'Test city',
                'state' => 'Uttarakhand',
                'description' => 'Test hotel description',
                'starting_price' => $tariff,
                'status' => true,
            ]);

            $this->post(route('hotel.booking.start', $hotel), [
                'check_in' => now()->addDays(3)->toDateString(),
                'check_out' => now()->addDays(4)->toDateString(),
                'guests' => 1,
                'rooms' => 1,
                'name' => 'Test Guest',
                'phone' => '+91 9876543210',
                'email' => 'guest@example.com',
            ])->assertOk()->assertViewHas('amountPaise', $payable * 100);

            $this->assertDatabaseHas('bookings', [
                'item_id' => $hotel->id,
                'taxable_amount' => $tariff,
                'gst_percentage' => $rate,
                'gst_amount' => $payable - $tariff,
                'grand_total' => $payable,
            ]);
        }
    }

    public function test_mixed_hotel_room_tariffs_are_calculated_separately_across_nights(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Mixed tariff hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 500,
            'status' => true,
        ]);
        $roomType = RoomType::create(['name' => 'Standard', 'status' => true]);
        $lowerRoom = Room::create([
            'hotel_id' => $hotel->id,
            'room_name' => 'Standard room',
            'room_number' => 'GST-LOW-1',
            'room_type_id' => $roomType->id,
            'capacity' => 2,
            'total_rooms' => 2,
            'available_rooms' => 2,
            'price_per_night' => 1500,
            'status' => true,
        ]);
        $higherRoom = Room::create([
            'hotel_id' => $hotel->id,
            'room_name' => 'Premium room',
            'room_number' => 'GST-HIGH-1',
            'room_type_id' => $roomType->id,
            'capacity' => 2,
            'total_rooms' => 1,
            'available_rooms' => 1,
            'price_per_night' => 8000,
            'status' => true,
        ]);
        Http::fake([
            'api.razorpay.com/v1/orders' => Http::response([
                'id' => 'order_mixed_hotel',
                'amount' => 2203000,
                'currency' => 'INR',
            ]),
        ]);

        $this->post(route('hotel.booking.start', $hotel), [
            'check_in' => now()->addDays(3)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests' => 4,
            'room_allocations' => [
                $lowerRoom->id => 1,
                $higherRoom->id => 1,
            ],
            'name' => 'Test Guest',
            'phone' => '+91 9876543210',
            'email' => 'guest@example.com',
        ])->assertOk()->assertViewHas('amountPaise', 2203000);

        $booking = Booking::query()->latest('id')->firstOrFail();
        $this->assertSame(19000.0, (float) $booking->taxable_amount);
        $this->assertSame(3030.0, (float) $booking->gst_amount);
        $this->assertSame(22030.0, (float) $booking->grand_total);
        $this->assertCount(2, $booking->gst_breakdown);
        $this->assertSame([5, 18], array_column($booking->gst_breakdown, 'gst_percentage'));
        $this->assertDatabaseHas('booking_rooms', [
            'booking_id' => $booking->id,
            'room_id' => $lowerRoom->id,
            'quantity' => 1,
        ]);
        Http::assertSent(fn ($request) => $request['amount'] === 2203000);
    }

    public function test_package_category_controls_the_saved_rate_and_gateway_amount(): void
    {
        Http::fake(function (ClientRequest $request) {
            return Http::response([
                'id' => 'order_package_'.$request['receipt'],
                'amount' => $request['amount'],
                'currency' => 'INR',
            ]);
        });

        foreach ([
            'tour_operator_eligible' => [5, 1050],
            'tour_operator_with_itc' => [18, 1180],
        ] as $category => [$rate, $expectedTotal]) {
            $package = Package::create([
                'package_name' => 'GST package '.$rate,
                'destination' => 'Test destination',
                'duration' => 3,
                'price' => 1000,
                'discount_price' => null,
                'max_person' => 5,
                'description' => 'Test package description',
                'gst_category' => $category,
                'gst_percentage' => $rate,
                'status' => true,
            ]);

            $this->post(route('package.booking.start', $package), [
                'travel_date' => now()->addDays(10)->toDateString(),
                'travelers' => 1,
                'name' => 'Test Guest',
                'phone' => '+91 9876543210',
                'email' => 'guest@example.com',
            ])->assertOk()->assertViewHas('amountPaise', $expectedTotal * 100);

            $this->assertDatabaseHas('bookings', [
                'item_id' => $package->id,
                'gst_category' => $category,
                'gst_percentage' => $rate,
                'gst_amount' => $expectedTotal - 1000,
                'grand_total' => $expectedTotal,
            ]);
            Http::assertSent(fn ($request) => $request['amount'] === $expectedTotal * 100);
        }
    }

    public function test_checkout_token_reuses_the_existing_booking_and_payment_order(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Idempotent hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 3000,
            'status' => true,
        ]);
        Http::fake([
            'api.razorpay.com/v1/orders' => Http::response([
                'id' => 'order_idempotent',
                'amount' => 315000,
                'currency' => 'INR',
            ]),
        ]);
        $payload = [
            'check_in' => now()->addDays(3)->toDateString(),
            'check_out' => now()->addDays(4)->toDateString(),
            'guests' => 1,
            'rooms' => 1,
            'name' => 'Test Guest',
            'phone' => '+91 9876543210',
            'email' => 'guest@example.com',
            'checkout_token' => (string) \Illuminate\Support\Str::uuid(),
        ];

        $this->post(route('hotel.booking.start', $hotel), $payload)
            ->assertOk()
            ->assertViewHas('payment', fn ($payment) => $payment->gateway_order_id === 'order_idempotent');
        $this->post(route('hotel.booking.start', $hotel), $payload)
            ->assertOk()
            ->assertViewHas('payment', fn ($payment) => $payment->gateway_order_id === 'order_idempotent');

        $this->assertDatabaseCount('bookings', 1);
        $this->assertDatabaseCount('payments', 1);
        Http::assertSentCount(1);
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
