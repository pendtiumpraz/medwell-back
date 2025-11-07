<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lipid_panels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profiles')->onDelete('cascade');
            $table->date('test_date');
            
            $table->decimal('total_cholesterol', 5, 1)->nullable();
            $table->decimal('ldl_cholesterol', 5, 1)->nullable();
            $table->decimal('hdl_cholesterol', 5, 1)->nullable();
            $table->decimal('triglycerides', 5, 1)->nullable();
            $table->enum('unit', ['mg/dL', 'mmol/L'])->default('mg/dL');
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['patient_id', 'test_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lipid_panels');
    }
};
