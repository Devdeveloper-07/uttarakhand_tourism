<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_itineraries', function (Blueprint $table) {

            $table->id();

            $table->foreignId('package_id')->constrained()->cascadeOnDelete();

            $table->integer('day');

            $table->string('title');

            $table->longText('description');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_itineraries');
    }
};
