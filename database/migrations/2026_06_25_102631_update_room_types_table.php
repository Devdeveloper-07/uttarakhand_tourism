<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_types', function (Blueprint $table) {

            $table->string('name');

            $table->text('description')->nullable();

            $table->boolean('status')->default(true);

        });
    }

    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {

            $table->dropColumn([
                'name',
                'description',
                'status'
            ]);

        });
    }
};
