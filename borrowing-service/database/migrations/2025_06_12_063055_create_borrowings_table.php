<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // ID dari User Service
            $table->string('book_id'); // ID dari Book Catalog Service
            $table->string('isbn')->nullable();
            $table->string('book_title');
            $table->string('user_name');
            $table->string('user_email');
            $table->date('borrowed_date');
            $table->date('due_date');
            $table->date('returned_date')->nullable();
            $table->enum('status', ['borrowed', 'returned', 'overdue'])->default('borrowed');
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['book_id', 'status']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('borrowings');
    }
};