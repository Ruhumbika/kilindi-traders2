<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sms_logs', function (Blueprint $table) {
            // First, drop the foreign key constraint
            $table->dropForeign(['trader_id']);
            
            // Then modify the column to be nullable
            $table->foreignId('trader_id')
                  ->nullable()
                  ->change();
                  
            // Re-add the foreign key constraint
            $table->foreign('trader_id')
                  ->references('id')
                  ->on('traders')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_logs', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['trader_id']);
            
            // Make the column required again
            $table->foreignId('trader_id')
                  ->nullable(false)
                  ->change();
                  
            // Re-add the foreign key constraint
            $table->foreign('trader_id')
                  ->references('id')
                  ->on('traders')
                  ->onDelete('cascade');
        });
    }
};
