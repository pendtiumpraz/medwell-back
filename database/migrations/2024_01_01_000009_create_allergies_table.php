<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allergies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profiles')->onDelete('cascade');
            $table->string('allergen');
            $table->enum('severity', ['mild', 'moderate', 'severe', 'life_threatening'])->default('mild');
            $table->text('reaction')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('patient_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allergies');
    }
};
