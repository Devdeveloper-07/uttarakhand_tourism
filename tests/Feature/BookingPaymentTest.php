<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\Hotel;
use App\Models\HotelGstSlab;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
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

        $this->actingAs(User::factory()->create());
    }

    public function test_guest_hotel_booking_returns_to_form_with_saved_selection_and_account_details(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Login return hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 200,
            'status' => true,
        ]);
        $roomType = RoomType::create(['name' => 'Standard', 'status' => true]);
        $room = Room::create([
            'hotel_id' => $hotel->id,
            'room_name' => 'Garden room',
            'room_number' => 'LOGIN-ROOM-1',
            'room_type_id' => $roomType->id,
            'capacity' => 2,
            'total_rooms' => 2,
            'available_rooms' => 2,
            'price_per_night' => 1500,
            'status' => true,
        ]);
        $customer = User::factory()->create([
            'name' => 'Akash Negi',
            'email' => 'akash@example.com',
        ]);
        $customer->detail()->create(['mobile' => '9876543210', 'status' => true]);
        $checkIn = now()->addDays(8)->toDateString();
        $checkOut = now()->addDays(10)->toDateString();
        $checkoutToken = (string) \Illuminate\Support\Str::uuid();

        Auth::logout();
        $this->post(route('hotel.booking.start', $hotel), [
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests' => 2,
            'rooms' => 1,
            'room_allocations' => [$room->id => 1],
            'checkout_token' => $checkoutToken,
        ])->assertRedirect(route('login'))
            ->assertSessionHas('booking.pending.form.check_in', $checkIn);

        $this->post(route('login'), [
            'email' => $customer->email,
            'password' => 'password',
        ])->assertRedirect(route('hotel.details', $hotel->id));

        $this->get(route('hotel.details', $hotel->id))
            ->assertOk()
            ->assertSee('value="'.$checkIn.'"', false)
            ->assertSee('value="'.$checkOut.'"', false)
            ->assertSee('value="'.$checkoutToken.'"', false)
            ->assertSee('value="2" selected', false)
            ->assertSee('name="room_allocations['.$room->id.']"', false)
            ->assertSee('value="1"', false)
            ->assertSee('value="Akash Negi"', false)
            ->assertSee('value="9876543210"', false)
            ->assertSee('value="akash@example.com"', false);

        Http::fake([
            'api.razorpay.com/v1/orders' => Http::response([
                'id' => 'order_login_return_hotel',
                'amount' => 315000,
                'currency' => 'INR',
            ]),
        ]);
        $this->post(route('hotel.booking.start', $hotel), [
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests' => 2,
            'rooms' => 1,
            'room_allocations' => [$room->id => 1],
            'name' => 'Akash Negi',
            'phone' => '9876543210',
            'email' => 'akash@example.com',
            'checkout_token' => $checkoutToken,
        ])->assertOk()->assertViewIs('frontend.payment.checkout');
        $this->assertDatabaseHas('bookings', [
            'item_type' => 'hotel',
            'item_id' => $hotel->id,
            'user_id' => $customer->id,
        ]);
        $booking = Booking::query()->where('item_id', $hotel->id)->firstOrFail();
        $this->assertSame($checkIn, $booking->check_in->toDateString());
        $this->assertSame($checkOut, $booking->check_out->toDateString());
        $this->assertFalse(session()->has('booking.pending'));
    }

    public function test_guest_package_booking_returns_to_form_with_saved_selection_and_account_details(): void
    {
        $package = Package::create([
            'package_name' => 'Login return package',
            'destination' => 'Test destination',
            'duration' => 3,
            'price' => 1000,
            'discount_price' => 800,
            'max_person' => 8,
            'description' => 'Test package description',
            'status' => true,
        ]);
        $customer = User::factory()->create([
            'name' => 'Akash Negi',
            'email' => 'akash-package@example.com',
        ]);
        $customer->detail()->create(['mobile' => '9876543210', 'status' => true]);
        $travelDate = now()->addDays(12)->toDateString();
        $checkoutToken = (string) \Illuminate\Support\Str::uuid();

        Auth::logout();
        $this->post(route('package.booking.start', $package), [
            'travel_date' => $travelDate,
            'travelers' => 3,
            'checkout_token' => $checkoutToken,
        ])->assertRedirect(route('login'))
            ->assertSessionHas('booking.pending.form.travel_date', $travelDate);

        $this->post(route('login'), [
            'email' => $customer->email,
            'password' => 'password',
        ])->assertRedirect(route('package.details', $package->id));

        $this->get(route('package.details', $package->id))
            ->assertOk()
            ->assertSee('value="'.$travelDate.'"', false)
            ->assertSee('value="'.$checkoutToken.'"', false)
            ->assertSee('value="3"', false)
            ->assertSee('Base Amount (before GST)')
            ->assertSee('value="Akash Negi"', false)
            ->assertSee('value="9876543210"', false)
            ->assertSee('value="akash-package@example.com"', false);

        Http::fake([
            'api.razorpay.com/v1/orders' => Http::response([
                'id' => 'order_login_return_package',
                'amount' => 283200,
                'currency' => 'INR',
            ]),
        ]);
        $this->post(route('package.booking.start', $package), [
            'travel_date' => $travelDate,
            'travelers' => 3,
            'name' => 'Akash Negi',
            'phone' => '9876543210',
            'email' => 'akash-package@example.com',
            'checkout_token' => $checkoutToken,
        ])->assertOk()->assertViewIs('frontend.payment.checkout');
        $this->assertDatabaseHas('bookings', [
            'item_type' => 'package',
            'item_id' => $package->id,
            'user_id' => $customer->id,
            'guest' => 3,
        ]);
        $booking = Booking::query()->where('item_id', $package->id)->firstOrFail();
        $this->assertSame($travelDate, $booking->travel_date->toDateString());
        $this->assertFalse(session()->has('booking.pending'));
    }

    public function test_hotel_booking_phone_stays_editable_when_profile_mobile_is_missing(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Missing mobile hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 200,
            'status' => true,
        ]);
        $customer = User::factory()->create([
            'name' => 'No Mobile User',
            'email' => 'no-mobile@example.com',
        ]);

        $this->actingAs($customer)
            ->get(route('hotel.details', $hotel->id))
            ->assertOk()
            ->assertSee('id="mmtGuestPhone" value=""', false)
            ->assertSee('id="mmtGuestName" value="No Mobile User"', false)
            ->assertSee('id="mmtGuestEmail" value="no-mobile@example.com"', false);
    }

    public function test_hotel_catalog_filters_and_sorts_by_starting_price_and_hides_inactive_details(): void
    {
        $expensive = Hotel::create([
            'hotel_name' => 'Expensive stay',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel',
            'starting_price' => 900,
            'status' => true,
        ]);
        $affordable = Hotel::create([
            'hotel_name' => 'Affordable stay',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel',
            'starting_price' => 300,
            'status' => true,
        ]);
        $inactive = Hotel::create([
            'hotel_name' => 'Inactive stay',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel',
            'starting_price' => 100,
            'status' => false,
        ]);

        $this->get(route('hotels', ['sort' => 'price_low']))
            ->assertOk()
            ->assertViewHas('hotels', fn ($hotels) => $hotels->getCollection()->modelKeys() === [$affordable->id, $expensive->id]);
        $this->get(route('hotels', ['sort' => 'price_high']))
            ->assertOk()
            ->assertViewHas('hotels', fn ($hotels) => $hotels->getCollection()->modelKeys() === [$expensive->id, $affordable->id]);
        $this->get(route('hotels', ['price' => 500]))
            ->assertOk()
            ->assertViewHas('hotels', fn ($hotels) => $hotels->getCollection()->modelKeys() === [$affordable->id]);
        $this->get(route('hotel.details', $affordable->id))
            ->assertOk()
            ->assertDontSee('29% OFF')
            ->assertDontSee('mmtOldPrice');
        $this->get(route('hotel.details', $inactive->id))->assertNotFound();
    }

    public function test_inactive_package_details_are_not_publicly_accessible(): void
    {
        $package = Package::create([
            'package_name' => 'Inactive package',
            'destination' => 'Test destination',
            'duration' => 3,
            'price' => 1000,
            'discount_price' => null,
            'max_person' => 8,
            'description' => 'Test package description',
            'status' => false,
        ]);

        $this->get(route('package.details', $package->id))->assertNotFound();
    }

    public function test_checkout_token_cannot_resume_another_users_checkout(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Owned checkout hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 200,
            'status' => true,
        ]);
        $token = (string) \Illuminate\Support\Str::uuid();
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'booking_no' => 'BK-OWNED-CHECKOUT',
            'checkout_token' => $token,
            'item_type' => 'hotel',
            'item_id' => $hotel->id,
            'check_in' => now()->addDays(4)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guest' => 1,
            'room_count' => 1,
            'total_amount' => 200,
            'grand_total' => 200,
            'currency' => 'INR',
            'payment_status' => 'pending',
            'booking_status' => 'pending',
        ]);
        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'gateway_name' => 'razorpay',
            'gateway_order_id' => 'order_owned_checkout',
            'currency' => 'INR',
            'amount' => 200,
            'payment_status' => 'created',
        ]);
        $otherCustomer = User::factory()->create();

        $this->actingAs($otherCustomer)
            ->post(route('hotel.booking.start', $hotel), [
                'check_in' => now()->addDays(4)->toDateString(),
                'check_out' => now()->addDays(5)->toDateString(),
                'guests' => 1,
                'rooms' => 1,
                'name' => 'Another customer',
                'phone' => '9876543210',
                'email' => $otherCustomer->email,
                'checkout_token' => $token,
            ])->assertNotFound();
    }

    public function test_hotel_update_rejects_an_invalid_thumbnail_upload(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Upload validation hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 200,
            'status' => true,
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->put(route('admin.hotels.update', $hotel), [
                'hotel_name' => 'Upload validation hotel',
                'location' => 'Test location',
                'city' => 'Test city',
                'description' => 'Test hotel description',
                'starting_price' => 200,
                'thumbnail' => UploadedFile::fake()->create('not-an-image.txt', 4, 'text/plain'),
            ])
            ->assertSessionHasErrors('thumbnail');
    }

    public function test_admin_cannot_delete_hotel_or_package_booking_history(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Historical hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 200,
            'status' => true,
        ]);
        $package = Package::create([
            'package_name' => 'Historical package',
            'destination' => 'Test destination',
            'duration' => 3,
            'price' => 1000,
            'discount_price' => null,
            'max_person' => 8,
            'description' => 'Test package description',
            'status' => true,
        ]);
        Booking::create([
            'booking_no' => 'BK-HIST-HOTEL',
            'item_type' => 'hotel',
            'item_id' => $hotel->id,
            'guest' => 1,
            'room_count' => 1,
            'total_amount' => 200,
            'grand_total' => 200,
            'currency' => 'INR',
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);
        Booking::create([
            'booking_no' => 'BK-HIST-PACKAGE',
            'item_type' => 'package',
            'item_id' => $package->id,
            'guest' => 1,
            'room_count' => 1,
            'total_amount' => 1000,
            'grand_total' => 1180,
            'currency' => 'INR',
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->delete(route('admin.hotels.destroy', $hotel))
            ->assertSessionHasErrors('hotel');
        $this->delete(route('admin.packages.destroy', $package))
            ->assertSessionHasErrors('package');

        $this->assertDatabaseHas('hotels', ['id' => $hotel->id]);
        $this->assertDatabaseHas('packages', ['id' => $package->id]);
    }

    public function test_admin_can_create_a_customer_account_without_assigning_admin_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.users.create'))
            ->assertOk()
            ->assertSee('Create User');

        $this->post(route('admin.users.store'), [
            'name' => 'Created Customer',
            'email' => 'created-customer@example.com',
            'mobile' => '9876543210',
            'gender' => 'Other',
            'password' => 'TourPassword-123!',
            'password_confirmation' => 'TourPassword-123!',
            'status' => '1',
            'role' => 'admin',
        ])->assertRedirect(route('admin.users.index'));

        $customer = User::query()->where('email', 'created-customer@example.com')->firstOrFail();
        $this->assertSame('user', $customer->role);
        $this->assertDatabaseHas('user_details', [
            'user_id' => $customer->id,
            'mobile' => '9876543210',
            'gender' => 'Other',
            'status' => 1,
        ]);
    }

    public function test_admin_user_management_cannot_change_or_remove_an_admin_account(): void
    {
        $otherAdmin = User::factory()->create(['role' => 'admin']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.users.edit', $otherAdmin))
            ->assertNotFound();
        $this->delete(route('admin.users.destroy', $otherAdmin))->assertNotFound();

        $this->assertDatabaseHas('users', [
            'id' => $otherAdmin->id,
            'role' => 'admin',
        ]);
    }

    public function test_admin_cannot_delete_a_room_type_that_is_in_use(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Room type hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 200,
            'status' => true,
        ]);
        $roomType = RoomType::create(['name' => 'In-use type', 'status' => true]);
        $room = Room::create([
            'hotel_id' => $hotel->id,
            'room_name' => 'In-use room',
            'room_number' => 'TYPE-ROOM-1',
            'room_type_id' => $roomType->id,
            'capacity' => 2,
            'total_rooms' => 1,
            'available_rooms' => 1,
            'price_per_night' => 200,
            'status' => true,
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->delete(route('admin.room-types.destroy', $roomType))
            ->assertSessionHasErrors('room_type');

        $this->assertDatabaseHas('room_types', ['id' => $roomType->id]);
        $this->assertDatabaseHas('rooms', ['id' => $room->id]);
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

    public function test_authorized_payment_is_captured_before_confirming_the_booking(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Capture hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 200,
            'status' => true,
        ]);
        $booking = Booking::create([
            'booking_no' => 'BK-CAPTURE-TEST',
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
            'gateway_order_id' => 'order_capture_test',
            'currency' => 'INR',
            'amount' => 200,
            'payment_status' => 'created',
        ]);
        $paymentId = 'pay_capture_test';
        $signature = hash_hmac('sha256', 'order_capture_test|'.$paymentId, 'test_secret');

        Http::fake([
            'api.razorpay.com/v1/payments/'.$paymentId => Http::response([
                'id' => $paymentId,
                'order_id' => 'order_capture_test',
                'amount' => 20000,
                'currency' => 'INR',
                'status' => 'authorized',
                'method' => 'upi',
            ]),
            'api.razorpay.com/v1/payments/'.$paymentId.'/capture' => Http::response([
                'id' => $paymentId,
                'order_id' => 'order_capture_test',
                'amount' => 20000,
                'currency' => 'INR',
                'status' => 'captured',
                'method' => 'upi',
            ]),
        ]);

        $this->post(route('payments.verify', $payment), [
            'razorpay_payment_id' => $paymentId,
            'razorpay_order_id' => 'order_capture_test',
            'razorpay_signature' => $signature,
        ])->assertOk()->assertViewHas('status', 'success');

        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/payments/'.$paymentId.'/capture')
            && $request['amount'] === 20000
            && $request['currency'] === 'INR');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);
    }

    public function test_gateway_lookup_connection_failure_leaves_payment_pending(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Pending verification hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 200,
            'status' => true,
        ]);
        $booking = Booking::create([
            'booking_no' => 'BK-VERIFY-PENDING',
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
            'gateway_order_id' => 'order_verify_pending',
            'currency' => 'INR',
            'amount' => 200,
            'payment_status' => 'created',
        ]);
        $paymentId = 'pay_verify_pending';
        $signature = hash_hmac('sha256', 'order_verify_pending|'.$paymentId, 'test_secret');

        Http::fake(function () {
            throw new \Illuminate\Http\Client\ConnectionException('Test connection failure.');
        });

        $this->post(route('payments.verify', $payment), [
            'razorpay_payment_id' => $paymentId,
            'razorpay_order_id' => 'order_verify_pending',
            'razorpay_signature' => $signature,
        ])->assertStatus(202)->assertViewHas('status', 'pending');

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

    public function test_datewise_availability_reuses_rooms_on_checkout_and_nonoverlapping_dates(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Date availability hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 1000,
            'status' => true,
        ]);
        $roomType = RoomType::create(['name' => 'Deluxe', 'status' => true]);
        $room = Room::create([
            'hotel_id' => $hotel->id,
            'room_name' => 'Deluxe room',
            'room_number' => 'DATE-ROOM-1',
            'room_type_id' => $roomType->id,
            'capacity' => 2,
            'total_rooms' => 5,
            'available_rooms' => 0,
            'price_per_night' => 3000,
            'status' => true,
        ]);
        $checkIn = now()->addDays(3)->startOfDay();
        $checkOut = $checkIn->copy()->addDays(3);
        $booking = Booking::create([
            'booking_no' => 'BK-DATE-AVAILABILITY',
            'item_type' => 'hotel',
            'item_id' => $hotel->id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guest' => 2,
            'room_count' => 1,
            'total_amount' => 3000,
            'grand_total' => 3150,
            'currency' => 'INR',
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);
        BookingRoom::create([
            'booking_id' => $booking->id,
            'room_id' => $room->id,
            'quantity' => 1,
            'price' => 3000,
        ]);

        $availabilityUrl = route('hotel.availability', $hotel);
        $this->get($availabilityUrl.'?'.http_build_query([
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
        ]))
            ->assertOk()
            ->assertJsonPath('rooms.0.total', 5)
            ->assertJsonPath('rooms.0.available', 4)
            ->assertJsonPath('rooms.0.daily.0.available', 4)
            ->assertJsonPath('rooms.0.daily.1.available', 4)
            ->assertJsonPath('rooms.0.daily.2.available', 4);

        $this->get($availabilityUrl.'?'.http_build_query([
            'check_in' => $checkOut->toDateString(),
            'check_out' => $checkOut->copy()->addDays(2)->toDateString(),
        ]))
            ->assertOk()
            ->assertJsonPath('rooms.0.total', 5)
            ->assertJsonPath('rooms.0.available', 5);

        $this->get($availabilityUrl.'?'.http_build_query([
            'check_in' => $checkIn->copy()->addDays(2)->toDateString(),
            'check_out' => $checkOut->copy()->addDays(2)->toDateString(),
        ]))
            ->assertOk()
            ->assertJsonPath('rooms.0.daily.0.available', 4)
            ->assertJsonPath('rooms.0.daily.1.available', 5)
            ->assertJsonPath('rooms.0.daily.2.available', 5)
            ->assertJsonPath('rooms.0.available', 4);

        $this->get(route('hotels', [
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
        ]))
            ->assertOk()
            ->assertSee('4 of 5 rooms available for selected dates');
    }

    public function test_active_checkout_holds_reduce_availability_until_expired(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Temporary hold hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 1000,
            'status' => true,
        ]);
        $roomType = RoomType::create(['name' => 'Standard', 'status' => true]);
        $room = Room::create([
            'hotel_id' => $hotel->id,
            'room_name' => 'Standard room',
            'room_number' => 'HOLD-ROOM-1',
            'room_type_id' => $roomType->id,
            'capacity' => 2,
            'total_rooms' => 5,
            'available_rooms' => 5,
            'price_per_night' => 1000,
            'status' => true,
        ]);
        $checkIn = now()->addDays(3);
        $checkOut = $checkIn->copy()->addDays(2);
        $booking = Booking::create([
            'booking_no' => 'BK-TEMP-HOLD',
            'item_type' => 'hotel',
            'item_id' => $hotel->id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guest' => 2,
            'room_count' => 2,
            'total_amount' => 2000,
            'grand_total' => 2000,
            'currency' => 'INR',
            'payment_status' => 'pending',
            'booking_status' => 'pending',
            'hold_expires_at' => now()->addMinutes(15),
        ]);
        BookingRoom::create([
            'booking_id' => $booking->id,
            'room_id' => $room->id,
            'quantity' => 2,
            'price' => 1000,
        ]);
        $url = route('hotel.availability', $hotel).'?'.http_build_query([
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
        ]);

        $this->get($url)->assertOk()->assertJsonPath('rooms.0.available', 3);

        $booking->update(['hold_expires_at' => now()->subSecond()]);

        $this->get($url)->assertOk()->assertJsonPath('rooms.0.available', 5);
    }

    public function test_booking_rejects_a_quantity_above_datewise_inventory(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Inventory limit hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 1000,
            'status' => true,
        ]);
        $roomType = RoomType::create(['name' => 'Standard', 'status' => true]);
        $room = Room::create([
            'hotel_id' => $hotel->id,
            'room_name' => 'Standard room',
            'room_number' => 'LIMIT-ROOM-1',
            'room_type_id' => $roomType->id,
            'capacity' => 2,
            'total_rooms' => 5,
            'available_rooms' => 5,
            'price_per_night' => 1000,
            'status' => true,
        ]);

        $this->from(route('hotel.details', $hotel))->post(route('hotel.booking.start', $hotel), [
            'check_in' => now()->addDays(3)->toDateString(),
            'check_out' => now()->addDays(4)->toDateString(),
            'guests' => 1,
            'room_allocations' => [$room->id => 6],
            'name' => 'Test Guest',
            'phone' => '+91 9876543210',
            'email' => 'guest@example.com',
        ])->assertRedirect(route('hotel.details', $hotel))
            ->assertSessionHasErrors([
                'room_allocations' => 'Sorry, this room is no longer available for your selected dates. Please select another room or date.',
            ]);

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_checkout_reservation_prevents_a_second_booking_from_overselling_rooms(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Oversell protection hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 1000,
            'status' => true,
        ]);
        $roomType = RoomType::create(['name' => 'Standard', 'status' => true]);
        $room = Room::create([
            'hotel_id' => $hotel->id,
            'room_name' => 'Standard room',
            'room_number' => 'OVERSELL-ROOM-1',
            'room_type_id' => $roomType->id,
            'capacity' => 2,
            'total_rooms' => 5,
            'available_rooms' => 5,
            'price_per_night' => 1000,
            'status' => true,
        ]);
        Http::fake(function (ClientRequest $request) {
            return Http::response([
                'id' => 'order_oversell',
                'amount' => $request['amount'],
                'currency' => 'INR',
            ]);
        });
        $payload = [
            'check_in' => now()->addDays(3)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests' => 1,
            'room_allocations' => [$room->id => 3],
            'name' => 'Test Guest',
            'phone' => '+91 9876543210',
            'email' => 'guest@example.com',
        ];

        $this->post(route('hotel.booking.start', $hotel), $payload)->assertOk();
        $this->from(route('hotel.details', $hotel))
            ->post(route('hotel.booking.start', $hotel), $payload)
            ->assertRedirect(route('hotel.details', $hotel))
            ->assertSessionHasErrors([
                'room_allocations' => 'Sorry, this room is no longer available for your selected dates. Please select another room or date.',
            ]);

        $this->assertDatabaseCount('bookings', 1);
        $this->assertDatabaseHas('bookings', [
            'booking_status' => 'pending',
            'room_count' => 3,
        ]);
        $this->assertNotNull(Booking::query()->firstOrFail()->hold_expires_at);
    }

    public function test_failed_payment_releases_temporary_room_reservation(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Failed payment hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 1000,
            'status' => true,
        ]);
        $roomType = RoomType::create(['name' => 'Standard', 'status' => true]);
        $room = Room::create([
            'hotel_id' => $hotel->id,
            'room_name' => 'Standard room',
            'room_number' => 'FAILED-ROOM-1',
            'room_type_id' => $roomType->id,
            'capacity' => 2,
            'total_rooms' => 1,
            'available_rooms' => 1,
            'price_per_night' => 1000,
            'status' => true,
        ]);
        $checkIn = now()->addDays(3);
        $checkOut = $checkIn->copy()->addDay();
        $booking = Booking::create([
            'booking_no' => 'BK-FAILED-RELEASE',
            'item_type' => 'hotel',
            'item_id' => $hotel->id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guest' => 1,
            'room_count' => 1,
            'total_amount' => 1000,
            'grand_total' => 1000,
            'currency' => 'INR',
            'payment_status' => 'pending',
            'booking_status' => 'pending',
            'hold_expires_at' => now()->addMinutes(15),
        ]);
        BookingRoom::create([
            'booking_id' => $booking->id,
            'room_id' => $room->id,
            'quantity' => 1,
            'price' => 1000,
        ]);
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'gateway_name' => 'razorpay',
            'gateway_order_id' => 'order_failed_release',
            'currency' => 'INR',
            'amount' => 1000,
            'payment_status' => 'created',
        ]);
        $paymentId = 'pay_failed_release';
        Http::fake([
            'api.razorpay.com/v1/payments/'.$paymentId => Http::response([
                'id' => $paymentId,
                'order_id' => 'order_failed_release',
                'amount' => 100000,
                'currency' => 'INR',
                'status' => 'failed',
            ]),
        ]);

        $this->postJson(route('payments.failed', $payment), [
            'razorpay_payment_id' => $paymentId,
            'razorpay_order_id' => 'order_failed_release',
        ])
            ->assertOk()
            ->assertJsonPath('message', 'Payment failed and the temporary room reservation was released. Start a new booking to retry.');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'booking_status' => 'cancelled',
            'hold_expires_at' => null,
        ]);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => 'failed',
        ]);
        $this->get(route('hotel.availability', $hotel).'?'.http_build_query([
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
        ]))->assertOk()->assertJsonPath('rooms.0.available', 1);
    }

    public function test_admin_cancellation_releases_the_room_for_its_dates(): void
    {
        $admin = User::create([
            'name' => 'Inventory Admin',
            'email' => 'inventory-admin@example.com',
            'password' => 'test-password',
            'role' => 'admin',
        ]);
        $hotel = Hotel::create([
            'hotel_name' => 'Cancellation hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 1000,
            'status' => true,
        ]);
        $roomType = RoomType::create(['name' => 'Standard', 'status' => true]);
        $room = Room::create([
            'hotel_id' => $hotel->id,
            'room_name' => 'Standard room',
            'room_number' => 'CANCEL-ROOM-1',
            'room_type_id' => $roomType->id,
            'capacity' => 2,
            'total_rooms' => 1,
            'available_rooms' => 1,
            'price_per_night' => 1000,
            'status' => true,
        ]);
        $checkIn = now()->addDays(3);
        $checkOut = $checkIn->copy()->addDay();
        $booking = Booking::create([
            'booking_no' => 'BK-CANCEL-ROOM',
            'item_type' => 'hotel',
            'item_id' => $hotel->id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guest' => 1,
            'room_count' => 1,
            'total_amount' => 1000,
            'grand_total' => 1000,
            'currency' => 'INR',
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);
        BookingRoom::create([
            'booking_id' => $booking->id,
            'room_id' => $room->id,
            'quantity' => 1,
            'price' => 1000,
        ]);
        $availabilityUrl = route('hotel.availability', $hotel).'?'.http_build_query([
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
        ]);

        $this->get($availabilityUrl)->assertOk()->assertJsonPath('rooms.0.available', 0);

        $this->actingAs($admin)
            ->post(route('admin.bookings.cancel', $booking))
            ->assertRedirect(route('admin.bookings.show', $booking))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'booking_status' => 'cancelled',
            'payment_status' => 'paid',
        ]);
        $this->get($availabilityUrl)->assertOk()->assertJsonPath('rooms.0.available', 1);
    }

    public function test_admin_room_inventory_uses_total_rooms_and_renders_live_availability(): void
    {
        $admin = User::create([
            'name' => 'Room Admin',
            'email' => 'room-admin@example.com',
            'password' => 'test-password',
            'role' => 'admin',
        ]);
        $hotel = Hotel::create([
            'hotel_name' => 'Admin inventory hotel',
            'location' => 'Test location',
            'city' => 'Test city',
            'state' => 'Uttarakhand',
            'description' => 'Test hotel description',
            'starting_price' => 1000,
            'status' => true,
        ]);
        $roomType = RoomType::create(['name' => 'Premium', 'status' => true]);

        $this->actingAs($admin)
            ->post(route('admin.rooms.store'), [
                'hotel_id' => $hotel->id,
                'room_name' => 'Premium room',
                'room_number' => 'ADMIN-INV-1',
                'room_type_id' => $roomType->id,
                'total_rooms' => 5,
                'capacity' => 3,
                'price_per_night' => 2500,
                'description' => 'Room inventory test',
                'amenities_text' => 'Wi-Fi, Balcony',
                'status' => 1,
            ])
            ->assertRedirect(route('admin.rooms.index'));

        $room = Room::query()->where('room_number', 'ADMIN-INV-1')->firstOrFail();
        $this->assertSame(5, $room->total_rooms);
        $this->assertSame(5, $room->available_rooms);
        $this->assertSame(['Wi-Fi', 'Balcony'], $room->amenities);

        $this->get(route('admin.rooms.index'))
            ->assertOk()
            ->assertSee('Total Inventory')
            ->assertSee('By night')
            ->assertSee('Admin inventory hotel');
        $this->get(route('admin.rooms.show', $room))
            ->assertOk()
            ->assertSee('available today');

        $this->put(route('admin.rooms.update', $room), [
            'hotel_id' => $hotel->id,
            'room_name' => 'Premium room',
            'room_number' => 'ADMIN-INV-1',
            'room_type_id' => $roomType->id,
            'total_rooms' => 7,
            'capacity' => 3,
            'price_per_night' => 2500,
            'description' => 'Room inventory test',
            'amenities_text' => 'Wi-Fi, Balcony',
            'status' => 1,
        ])->assertRedirect(route('admin.rooms.index'));

        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'total_rooms' => 7,
            'available_rooms' => 7,
        ]);
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
