<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {

            $table->string('package_name')->after('id');

            $table->string('destination');

            $table->integer('duration');

            $table->decimal('price',10,2);

            $table->decimal('discount_price',10,2)->nullable();

            $table->integer('max_person');

            $table->string('thumbnail')->nullable();

            $table->longText('description');

            $table->longText('includes')->nullable();

            $table->longText('excludes')->nullable();

            $table->longText('itinerary')->nullable();

            $table->boolean('featured')->default(false);

            $table->boolean('status')->default(true);

        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {

            $table->dropColumn([
                'package_name',
                'destination',
                'duration',
                'price',
                'discount_price',
                'max_person',
                'thumbnail',
                'description',
                'includes',
                'excludes',
                'itinerary',
                'featured',
                'status'
            ]);

        });
    }
};
