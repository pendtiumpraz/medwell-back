<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vital_signs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profiles')->onDelete('cascade');
            $table->timestamp('recorded_at');
            
            // Blood Pressure
            $table->integer('systolic')->nullable();
            $table->integer('diastolic')->nullable();
            $table->integer('pulse')->nullable();
            
            // Blood Glucose
            $table->decimal('glucose_value', 5, 1)->nullable();
            $table->enum('glucose_unit', ['mg/dL', 'mmol/L'])->default('mg/dL');
            $table->enum('glucose_context', [
                'fasting_8hr',
                'before_meal',
                'after_meal_2hr',
                'before_workout',
                'after_workout',
                'bedtime',
                'random'
            ])->nullable();
            
            // Body Temperature
            $table->decimal('temperature', 4, 2)->nullable();
            $table->enum('temperature_unit', ['C', 'F'])->default('C');
            $table->enum('temperature_location', ['oral', 'armpit', 'forehead', 'ear', 'rectal'])->nullable();
            $table->decimal('core_temperature', 4, 2)->nullable(); // Calculated
            
            // SpO2 & PR
            $table->integer('spo2_value')->nullable();
            $table->integer('pr_bpm')->nullable();
            
            // Body Measurements
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('waist_circumference', 5, 2)->nullable();
            $table->decimal('bmi', 4, 2)->nullable(); // Calculated
            
            // Source
            $table->enum('source', ['manual', 'wearable', 'device', 'clinician'])->default('manual');
            $table->string('device_type')->nullable(); // fitbit, huawei, etc.
            
            $table->timestamps();
            
            $table->index(['patient_id', 'recorded_at']);
            $table->index('recorded_at');
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vital_signs');
    }
};
