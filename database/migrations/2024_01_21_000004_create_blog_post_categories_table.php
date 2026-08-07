<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_post_categories', function (Blueprint $table) {
            $table->integer('post_id')->index();
            $table->integer('category_id')->index();

            $table->primary(['post_id', 'category_id']);

            $table->foreign('post_id')->references('post_id')->on('posts')->cascadeOnDelete();
            $table->foreign('category_id')->references('category_id')->on('blog_categories')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_post_categories');
    }
};
