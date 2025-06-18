<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('is_approved');
            $table->dropColumn('approved_at');
            $table->dropColumn('approved_by');
            
            // Drop the index that includes is_approved
            $table->dropIndex(['book_id', 'is_approved']);
            
            // Recreate index without is_approved
            $table->index(['book_id']);
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_approved')->default(true)->after('comment');
            $table->timestamp('approved_at')->nullable()->after('is_approved');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            
            // Drop and recreate the original index
            $table->dropIndex(['book_id']);
            $table->index(['book_id', 'is_approved']);
        });
    }
};