<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->text('token');
            $table->string('transaction_code')->unique();
            $table->string('provider_code')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->bigInteger('amount');
            $table->string('currency')->default('VND');
            $table->string('status')->default('pending');
            $table->dateTime('expired_at');
            $table->string('payment_method');
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
