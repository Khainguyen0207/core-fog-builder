<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')
                ->references('id')->on('bookings')->cascadeOnDelete();
            $table->foreignId('service_id')
                ->references('id')->on('services')->cascadeOnDelete();
            $table->string('service_name');
            $table->decimal('price', 12)->default(0);
            $table->integer('duration')->default(0);
            $table->string('status')->default('pending');
            $table->foreignId('assigned_staff_id')
                ->nullable()
                ->references('id')->on('staffs')->nullOnDelete();
            $table->text('note')->nullable();
            $table->dateTime('started_at');
            $table->dateTime('finished_at')->nullable();
            $table->timestamps();

            $table->unique(['booking_id', 'service_id']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('booking_services');
    }
};
