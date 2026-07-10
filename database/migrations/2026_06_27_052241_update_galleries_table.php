<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {

            $table->string('title')->after('id');

            $table->string('image');

            $table->enum('type',[
                'hotel',
                'package',
                'tourism'
            ])->default('tourism');

            $table->text('description')->nullable();

            $table->boolean('status')->default(1);

        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {

            $table->dropColumn([
                'title',
                'image',
                'type',
                'description',
                'status'
            ]);

        });
    }
};
