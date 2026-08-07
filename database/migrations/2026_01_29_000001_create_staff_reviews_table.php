<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();
            $table->foreignId('staff_id')
                ->constrained('staffs')
                ->cascadeOnDelete();
            $table->foreignId('booking_service_id')
                ->constrained('booking_services')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('staff_id');
            $table->index('customer_id');
            $table->index('booking_service_id');
            $table->unique(['customer_id', 'staff_id', 'booking_service_id'], 'staff_reviews_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_reviews');
    }
};
