<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('geolocation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profiles')->onDelete('cascade');
            
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('accuracy', 6, 2)->nullable();
            $table->decimal('altitude', 8, 2)->nullable();
            
            $table->timestamp('recorded_at');
            $table->boolean('is_breach')->default(false);
            $table->decimal('distance_from_home', 8, 2)->nullable(); // km
            
            $table->timestamps();
            
            $table->index(['patient_id', 'recorded_at']);
            $table->index('is_breach');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geolocation_logs');
    }
};
