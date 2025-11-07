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
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('code', 50)->nullable()->after('name');
            $table->enum('type', ['hospital', 'clinic', 'pharmacy', 'laboratory', 'other'])->default('hospital')->after('code');
            
            $table->index('code');
            $table->index('type');
        });
        
        // Generate unique codes for existing organizations
        \DB::statement("UPDATE organizations SET code = CONCAT('ORG', LPAD(id, 3, '0')) WHERE code IS NULL");
        
        // Make code unique and not nullable
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('code', 50)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropIndex(['code']);
            $table->dropIndex(['type']);
            $table->dropColumn(['code', 'type']);
        });
    }
};
