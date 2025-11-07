<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profiles')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->enum('schedule_type', [
                'report_symptoms',
                'exercise',
                'hydration',
                'rest',
                'custom'
            ]);
            
            $table->date('start_date');
            $table->date('end_date')->nullable();
            
            $table->enum('frequency', ['daily', 'weekly', 'custom'])->default('daily');
            $table->integer('times_per_day')->default(1);
            $table->json('timings')->nullable(); // ["08:00", "12:00", "18:00"]
            
            $table->json('settings')->nullable(); // Type-specific settings
            $table->text('instructions')->nullable();
            
            $table->enum('status', ['active', 'paused', 'completed'])->default('active');
            $table->timestamps();
            
            $table->index(['patient_id', 'status']);
            $table->index('schedule_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_schedules');
    }
};
