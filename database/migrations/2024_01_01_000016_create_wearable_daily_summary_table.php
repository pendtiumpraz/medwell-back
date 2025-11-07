<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wearable_daily_summary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profiles')->onDelete('cascade');
            $table->date('date');
            
            // Activity
            $table->integer('steps')->default(0);
            $table->decimal('distance', 8, 2)->default(0); // km
            $table->integer('floors_climbed')->default(0);
            $table->integer('active_minutes')->default(0);
            $table->integer('calories_burned')->default(0);
            
            // Heart Rate
            $table->integer('resting_heart_rate')->nullable();
            $table->integer('avg_heart_rate')->nullable();
            $table->integer('max_heart_rate')->nullable();
            $table->integer('min_heart_rate')->nullable();
            
            // Sleep
            $table->integer('sleep_duration')->nullable(); // minutes
            $table->integer('deep_sleep')->nullable();
            $table->integer('light_sleep')->nullable();
            $table->integer('rem_sleep')->nullable();
            $table->integer('awake_time')->nullable();
            $table->integer('sleep_score')->nullable();
            
            // SpO2
            $table->integer('avg_spo2')->nullable();
            $table->integer('min_spo2')->nullable();
            
            // Wellness Score
            $table->integer('wellness_score')->nullable();
            
            // Sync Info
            $table->timestamp('last_synced_at')->nullable();
            $table->string('device_type')->nullable();
            
            $table->timestamps();
            
            $table->unique(['patient_id', 'date']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wearable_daily_summary');
    }
};
