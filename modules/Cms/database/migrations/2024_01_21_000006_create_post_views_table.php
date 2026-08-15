<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_views', function (Blueprint $table) {
            $table->integer('post_view_id', true);
            $table->integer('post_id')->index();
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->timestamps();

            $table->foreign('post_id')->references('post_id')->on('posts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }
};
