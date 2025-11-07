<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hba1c_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profiles')->onDelete('cascade');
            $table->date('test_date');
            
            $table->decimal('hba1c_value', 4, 2);
            $table->enum('unit', ['%', 'mmol/mol'])->default('%');
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['patient_id', 'test_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hba1c_readings');
    }
};
