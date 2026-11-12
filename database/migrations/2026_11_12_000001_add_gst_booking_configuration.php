<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table): void {
            if (! Schema::hasColumn('packages', 'gst_category')) {
                $table->string('gst_category')->default('tour_operator_with_itc');
            }
            if (! Schema::hasColumn('packages', 'gst_percentage')) {
                $table->decimal('gst_percentage', 5, 2)->default(18);
            }
        });

        Schema::table('bookings', function (Blueprint $table): void {
            if (! Schema::hasColumn('bookings', 'gst_category')) {
                $table->string('gst_category')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'gst_breakdown')) {
                $table->json('gst_breakdown')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'checkout_token')) {
                $table->uuid('checkout_token')->nullable()->unique();
            }
        });

        Schema::table('payments', function (Blueprint $table): void {
            if (! Schema::hasColumn('payments', 'gst_category')) {
                $table->string('gst_category')->nullable();
            }
        });

        if (! Schema::hasTable('hotel_gst_slabs')) {
            Schema::create('hotel_gst_slabs', function (Blueprint $table): void {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->decimal('min_tariff', 12, 2);
                $table->decimal('max_tariff', 12, 2)->nullable();
                $table->decimal('gst_percentage', 5, 2);
                $table->timestamps();
            });
        }

        if (DB::table('hotel_gst_slabs')->count() === 0) {
            DB::table('hotel_gst_slabs')->insert([
                [
                    'code' => 'hotel_up_to_1000',
                    'name' => 'Up to ₹1,000 per room, per night',
                    'min_tariff' => 0,
                    'max_tariff' => 1000,
                    'gst_percentage' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'code' => 'hotel_1000_to_7500',
                    'name' => 'Above ₹1,000 up to ₹7,500 per room, per night',
                    'min_tariff' => 1000.01,
                    'max_tariff' => 7500,
                    'gst_percentage' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'code' => 'hotel_above_7500',
                    'name' => 'Above ₹7,500 per room, per night',
                    'min_tariff' => 7500.01,
                    'max_tariff' => null,
                    'gst_percentage' => 18,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_gst_slabs');

        Schema::table('payments', function (Blueprint $table): void {
            if (Schema::hasColumn('payments', 'gst_category')) {
                $table->dropColumn('gst_category');
            }
        });

        Schema::table('bookings', function (Blueprint $table): void {
            $columns = array_filter([
                Schema::hasColumn('bookings', 'gst_category') ? 'gst_category' : null,
                Schema::hasColumn('bookings', 'gst_breakdown') ? 'gst_breakdown' : null,
                Schema::hasColumn('bookings', 'checkout_token') ? 'checkout_token' : null,
            ]);
            if ($columns) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('packages', function (Blueprint $table): void {
            $columns = array_filter([
                Schema::hasColumn('packages', 'gst_category') ? 'gst_category' : null,
                Schema::hasColumn('packages', 'gst_percentage') ? 'gst_percentage' : null,
            ]);
            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
