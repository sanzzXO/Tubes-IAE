<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->integer('rating')->between(1, 5);
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['book_id', 'is_approved']);
            $table->index(['user_id']);
            $table->unique(['user_id', 'book_id']); // User hanya bisa review 1x per buku
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};