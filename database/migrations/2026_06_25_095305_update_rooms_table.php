<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {

            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();

            $table->string('room_name');

            $table->string('room_number')->unique();

            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();

            $table->integer('total_rooms')->default(1);

            $table->integer('available_rooms')->default(1);

            $table->integer('capacity')->default(2);

            $table->decimal('price_per_night',10,2);

            $table->text('description')->nullable();

            $table->boolean('status')->default(true);

        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {

            $table->dropForeign(['hotel_id']);
            $table->dropForeign(['room_type_id']);

            $table->dropColumn([
                'hotel_id',
                'room_name',
                'room_number',
                'room_type_id',
                'total_rooms',
                'available_rooms',
                'capacity',
                'price_per_night',
                'description',
                'status'
            ]);

        });
    }
};
