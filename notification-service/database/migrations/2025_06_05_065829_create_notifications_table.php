<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('type'); // borrowing, reminder, fine, availability
            $table->string('title');
            $table->text('message');
            $table->string('status')->default('sent'); // sent, failed
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};