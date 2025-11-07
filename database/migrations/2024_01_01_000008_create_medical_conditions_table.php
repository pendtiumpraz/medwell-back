<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profiles')->onDelete('cascade');
            $table->string('condition_name');
            $table->date('diagnosed_date')->nullable();
            $table->text('notes')->nullable();
            $table->enum('severity', ['mild', 'moderate', 'severe'])->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['patient_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_conditions');
    }
};
