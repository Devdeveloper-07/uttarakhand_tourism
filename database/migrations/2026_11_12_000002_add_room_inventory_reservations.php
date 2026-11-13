<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            if (! Schema::hasColumn('bookings', 'hold_expires_at')) {
                $table->timestamp('hold_expires_at')->nullable()->index();
            }
        });

        Schema::table('rooms', function (Blueprint $table): void {
            if (! Schema::hasColumn('rooms', 'amenities')) {
                $table->json('amenities')->nullable();
            }
            if (! Schema::hasColumn('rooms', 'image_path')) {
                $table->string('image_path')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table): void {
            $columns = array_filter([
                Schema::hasColumn('rooms', 'amenities') ? 'amenities' : null,
                Schema::hasColumn('rooms', 'image_path') ? 'image_path' : null,
            ]);
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('bookings', function (Blueprint $table): void {
            if (Schema::hasColumn('bookings', 'hold_expires_at')) {
                $table->dropIndex(['hold_expires_at']);
                $table->dropColumn('hold_expires_at');
            }
        });
    }
};
