<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_settings', function (Blueprint $table) {
            $table->string('membership_code')->primary();
            $table->string('name');
            $table->integer('min_points')->default(0);
            $table->string('status')->default('published');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_settings');
    }
};
