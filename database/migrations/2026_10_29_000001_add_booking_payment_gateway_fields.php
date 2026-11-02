<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            if (! Schema::hasColumn('bookings', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('bookings', 'booking_no')) {
                $table->string('booking_no')->nullable()->unique();
            }
            if (! Schema::hasColumn('bookings', 'item_type')) {
                $table->string('item_type')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'item_id')) {
                $table->unsignedBigInteger('item_id')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'check_in')) {
                $table->date('check_in')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'check_out')) {
                $table->date('check_out')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'travel_date')) {
                $table->date('travel_date')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'guest')) {
                $table->unsignedInteger('guest')->default(1);
            }
            if (! Schema::hasColumn('bookings', 'room_count')) {
                $table->unsignedInteger('room_count')->default(1);
            }
            if (! Schema::hasColumn('bookings', 'contact_name')) {
                $table->string('contact_name')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'contact_phone')) {
                $table->string('contact_phone', 24)->nullable();
            }
            if (! Schema::hasColumn('bookings', 'contact_email')) {
                $table->string('contact_email')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('bookings', 'taxable_amount')) {
                $table->decimal('taxable_amount', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('bookings', 'gst_percentage')) {
                $table->decimal('gst_percentage', 5, 2)->default(0);
            }
            if (! Schema::hasColumn('bookings', 'gst_amount')) {
                $table->decimal('gst_amount', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('bookings', 'grand_total')) {
                $table->decimal('grand_total', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('bookings', 'currency')) {
                $table->string('currency', 3)->default('INR');
            }
            if (! Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status')->default('pending');
            }
            if (! Schema::hasColumn('bookings', 'booking_status')) {
                $table->string('booking_status')->default('pending');
            }
        });

        Schema::table('hotels', function (Blueprint $table): void {
            if (! Schema::hasColumn('hotels', 'hotel_name')) {
                $table->string('hotel_name')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'slug')) {
                $table->string('slug')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'location')) {
                $table->string('location')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'city')) {
                $table->string('city')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'state')) {
                $table->string('state')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'description')) {
                $table->longText('description')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'thumbnail')) {
                $table->string('thumbnail')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'starting_price')) {
                $table->decimal('starting_price', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('hotels', 'rating')) {
                $table->decimal('rating', 3, 1)->default(0);
            }
            if (! Schema::hasColumn('hotels', 'status')) {
                $table->boolean('status')->default(true);
            }
        });

        Schema::table('packages', function (Blueprint $table): void {
            if (! Schema::hasColumn('packages', 'package_name')) {
                $table->string('package_name')->nullable();
            }
            if (! Schema::hasColumn('packages', 'destination')) {
                $table->string('destination')->nullable();
            }
            if (! Schema::hasColumn('packages', 'duration')) {
                $table->string('duration')->nullable();
            }
            if (! Schema::hasColumn('packages', 'price')) {
                $table->decimal('price', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('packages', 'discount_price')) {
                $table->decimal('discount_price', 12, 2)->nullable();
            }
            if (! Schema::hasColumn('packages', 'max_person')) {
                $table->unsignedInteger('max_person')->nullable();
            }
            if (! Schema::hasColumn('packages', 'thumbnail')) {
                $table->string('thumbnail')->nullable();
            }
            if (! Schema::hasColumn('packages', 'description')) {
                $table->longText('description')->nullable();
            }
            if (! Schema::hasColumn('packages', 'includes')) {
                $table->longText('includes')->nullable();
            }
            if (! Schema::hasColumn('packages', 'excludes')) {
                $table->longText('excludes')->nullable();
            }
            if (! Schema::hasColumn('packages', 'itinerary')) {
                $table->longText('itinerary')->nullable();
            }
            if (! Schema::hasColumn('packages', 'featured')) {
                $table->boolean('featured')->default(false);
            }
            if (! Schema::hasColumn('packages', 'status')) {
                $table->boolean('status')->default(true);
            }
        });

        Schema::table('booking_packages', function (Blueprint $table): void {
            if (! Schema::hasColumn('booking_packages', 'booking_id')) {
                $table->foreignId('booking_id')->nullable()->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('booking_packages', 'package_id')) {
                $table->foreignId('package_id')->nullable()->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('booking_packages', 'price')) {
                $table->decimal('price', 12, 2)->default(0);
            }
        });

        Schema::table('payments', function (Blueprint $table): void {
            if (! Schema::hasColumn('payments', 'booking_id')) {
                $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('payments', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('payments', 'user_email')) {
                $table->string('user_email')->nullable();
            }
            if (! Schema::hasColumn('payments', 'transaction_id')) {
                $table->string('transaction_id')->nullable();
            }
            if (! Schema::hasColumn('payments', 'gateway_name')) {
                $table->string('gateway_name')->default('razorpay');
            }
            if (! Schema::hasColumn('payments', 'gateway_order_id')) {
                $table->string('gateway_order_id')->nullable()->unique();
            }
            if (! Schema::hasColumn('payments', 'gateway_payment_id')) {
                $table->string('gateway_payment_id')->nullable()->unique();
            }
            if (! Schema::hasColumn('payments', 'gateway_signature')) {
                $table->string('gateway_signature', 128)->nullable();
            }
            if (! Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (! Schema::hasColumn('payments', 'currency')) {
                $table->string('currency', 3)->default('INR');
            }
            if (! Schema::hasColumn('payments', 'base_amount')) {
                $table->decimal('base_amount', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('payments', 'gst_percentage')) {
                $table->decimal('gst_percentage', 5, 2)->default(0);
            }
            if (! Schema::hasColumn('payments', 'gst_amount')) {
                $table->decimal('gst_amount', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('payments', 'amount')) {
                $table->decimal('amount', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('payments', 'payment_status')) {
                $table->string('payment_status')->default('created');
            }
            if (! Schema::hasColumn('payments', 'payment_date')) {
                $table->timestamp('payment_date')->nullable();
            }
            if (! Schema::hasColumn('payments', 'meta')) {
                $table->json('meta')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Existing installations may already have these columns; dropping them could destroy booking data.
    }
};
