<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_clinician', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profiles')->onDelete('cascade');
            $table->foreignId('clinician_id')->constrained('users')->onDelete('cascade');
            
            $table->enum('role', ['primary', 'secondary', 'consultant'])->default('primary');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('unassigned_at')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->unique(['patient_id', 'clinician_id', 'is_active']);
            $table->index(['clinician_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_clinician');
    }
};
