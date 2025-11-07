<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('geofence_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->unique()->constrained('patient_profiles')->onDelete('cascade');
            
            $table->boolean('enabled')->default(false);
            $table->decimal('center_latitude', 10, 8)->nullable();
            $table->decimal('center_longitude', 11, 8)->nullable();
            $table->decimal('radius', 8, 2)->default(1); // km
            $table->text('address')->nullable();
            
            $table->boolean('patient_consent')->default(false);
            $table->timestamp('consent_given_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geofence_settings');
    }
};
