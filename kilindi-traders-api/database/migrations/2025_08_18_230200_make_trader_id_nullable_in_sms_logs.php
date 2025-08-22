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
            $table->dropForeign(['trader_id']);
            $table->dropColumn('trader_id');
        });

        Schema::table('sms_logs', function (Blueprint $table) {
            $table->foreignId('trader_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_logs', function (Blueprint $table) {
            $table->dropForeign(['trader_id']);
            $table->dropColumn('trader_id');
        });

        Schema::table('sms_logs', function (Blueprint $table) {
            $table->foreignId('trader_id')->constrained()->onDelete('cascade');
        });
    }
};
