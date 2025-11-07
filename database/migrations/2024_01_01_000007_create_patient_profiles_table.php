<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            
            // Personal Information
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('racial_origin', 50)->nullable();
            
            // Initial Health Metrics
            $table->decimal('height', 5, 2)->nullable(); // cm
            $table->decimal('weight', 5, 2)->nullable(); // kg
            $table->string('blood_type', 5)->nullable();
            
            // Wearable Integration
            $table->enum('wearable_type', ['fitbit', 'apple', 'samsung', 'huawei', 'none'])->default('none');
            $table->string('fitbit_user_id')->nullable();
            $table->text('fitbit_access_token')->nullable();
            $table->text('fitbit_refresh_token')->nullable();
            $table->timestamp('fitbit_token_expires_at')->nullable();
            
            $table->string('huawei_user_id')->nullable();
            $table->text('huawei_access_token')->nullable();
            $table->text('huawei_refresh_token')->nullable();
            $table->timestamp('huawei_token_expires_at')->nullable();
            
            $table->string('apple_user_id')->nullable();
            $table->text('apple_access_token')->nullable();
            
            $table->string('samsung_user_id')->nullable();
            $table->text('samsung_access_token')->nullable();
            
            // Onboarding Status
            $table->boolean('onboarding_completed')->default(false);
            $table->timestamp('onboarding_completed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'onboarding_completed']);
            $table->index('wearable_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_profiles');
    }
};
