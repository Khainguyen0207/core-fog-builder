<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();

            $table->foreignId('category_id')
                ->nullable()
                ->references('id')
                ->on('categories')
                ->nullOnDelete();

            $table->string('status')->default('active');
            $table->decimal('price')->default(0);
            $table->unsignedBigInteger('time_do')->default(0);
            $table->string('time_unit')->default('minute');
            $table->unsignedInteger('priority')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
