<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to modify the enum since Laravel doesn't support enum modification directly
        DB::statement("ALTER TABLE sms_logs MODIFY COLUMN sms_type ENUM('reminder', 'notification', 'marketing', 'manual', 'test') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE sms_logs MODIFY COLUMN sms_type ENUM('reminder', 'notification', 'marketing', 'manual') NULL");
    }
};
