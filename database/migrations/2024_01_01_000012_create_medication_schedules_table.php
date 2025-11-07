<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medication_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_medication_id')->constrained()->onDelete('cascade');
            
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            
            $table->enum('status', ['pending', 'taken', 'delayed', 'missed', 'skipped'])->default('pending');
            $table->timestamp('taken_at')->nullable();
            $table->time('delayed_time')->nullable();
            $table->text('notes')->nullable();
            $table->text('skip_reason')->nullable();
            
            $table->timestamps();
            
            $table->index(['patient_medication_id', 'scheduled_date']);
            $table->index(['scheduled_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medication_schedules');
    }
};
