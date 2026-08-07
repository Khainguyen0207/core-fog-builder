<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 255)->unique();
            $table->string('type', 32);
            $table->decimal('value', 16, 2);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->integer('max_redemptions')->nullable();
            $table->integer('max_redemptions_per_user')->nullable();
            $table->decimal('min_order_amount', 16, 2)->nullable();
            $table->string('status', 16)->default('published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
