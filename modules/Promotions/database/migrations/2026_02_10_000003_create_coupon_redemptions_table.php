<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')
                ->constrained('coupons')
                ->cascadeOnDelete();
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();
            $table->string('context_type', 64);
            $table->decimal('discount_amount', 16, 2);
            $table->string('status', 16)->default('applied');
            $table->timestamps();

            $table->index('coupon_id');
            $table->index('customer_id');
            $table->index('context_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_redemptions');
    }
};
