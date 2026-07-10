<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {

            $table->string('hotel_name')->after('id');

            $table->string('slug')->nullable();

            $table->string('location');

            $table->string('city');

            $table->string('state')->default('Uttarakhand');

            $table->longText('description');

            $table->string('thumbnail')->nullable();

            $table->decimal('starting_price',10,2);

            $table->decimal('rating',2,1)->default(5);

            $table->boolean('status')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {

            $table->dropColumn([
                'hotel_name',
                'slug',
                'location',
                'city',
                'state',
                'description',
                'thumbnail',
                'starting_price',
                'rating',
                'status'
            ]);

        });
    }
};
