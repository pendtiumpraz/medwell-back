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
        // Step 1: Add message column (copy data from body if exists)
        if (Schema::hasColumn('notifications', 'body')) {
            // Add message column
            Schema::table('notifications', function (Blueprint $table) {
                $table->text('message')->nullable()->after('title');
            });
            
            // Copy data from body to message
            DB::table('notifications')->update(['message' => DB::raw('body')]);
            
            // Drop body column
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropColumn('body');
            });
        }
        
        // Step 2: Update type enum values
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('info', 'success', 'warning', 'error', 'alert', 'medication', 'message', 'document', 'schedule', 'system', 'appointment') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert type enum
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('medication', 'alert', 'message', 'document', 'schedule', 'system', 'appointment') NOT NULL");
        
        // Revert message to body
        if (Schema::hasColumn('notifications', 'message')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->text('body')->nullable()->after('title');
            });
            
            DB::table('notifications')->update(['body' => DB::raw('message')]);
            
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropColumn('message');
            });
        }
    }
};
