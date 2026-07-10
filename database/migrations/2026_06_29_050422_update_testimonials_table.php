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
        Schema::table('testimonials', function (Blueprint $table) {

            $table->string('name')->after('id');

            $table->string('designation')->nullable()->after('name');

            $table->string('image')->nullable()->after('designation');

            $table->tinyInteger('rating')->default(5)->after('image');

            $table->text('message')->after('rating');

            $table->boolean('status')->default(1)->after('message');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {

            $table->dropColumn([
                'name',
                'designation',
                'image',
                'rating',
                'message',
                'status'
            ]);

        });
    }
};
