<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', [
                'super_admin',
                'organization_admin', 
                'admin',
                'clinician',
                'health_coach',
                'patient',
                'support',
                'manager'
            ])->default('patient');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('phone', 50)->nullable();
            $table->string('avatar')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['email', 'status']);
            $table->index(['role', 'status']);
            $table->index('organization_id');
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
