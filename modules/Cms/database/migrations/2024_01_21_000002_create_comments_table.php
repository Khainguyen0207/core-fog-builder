<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->integer('comment_id', true);
            $table->integer('post_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->integer('parent_comment_id')->nullable()->index();
            $table->text('comment_body');
            $table->enum('status', ['approved', 'pending', 'spam'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('post_id')->references('post_id')->on('posts')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('parent_comment_id')->references('comment_id')->on('comments')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
