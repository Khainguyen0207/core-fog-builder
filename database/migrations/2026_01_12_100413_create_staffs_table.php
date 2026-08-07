<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('staff_code')->unique();
            $table->string('name');
            $table->string('phone');
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('level')->default('trainee');
            $table->boolean('is_active')->default(true);
            $table->decimal('salary', 12);
            $table->dateTime('joined_at');
            $table->dateTime('resigned_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
