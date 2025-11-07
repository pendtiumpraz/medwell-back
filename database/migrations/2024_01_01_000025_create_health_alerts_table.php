<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profiles')->onDelete('cascade');
            
            $table->enum('alert_type', ['critical', 'warning', 'info'])->default('info');
            $table->string('parameter'); // spo2, heart_rate, blood_pressure, etc
            $table->decimal('value', 10, 2)->nullable();
            $table->string('threshold')->nullable();
            $table->text('message');
            
            $table->boolean('notified_clinician')->default(false);
            $table->timestamp('notified_at')->nullable();
            
            $table->foreignId('acknowledged_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('acknowledged_at')->nullable();
            $table->text('acknowledgement_note')->nullable();
            
            $table->boolean('resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_note')->nullable();
            
            $table->timestamps();
            
            $table->index(['patient_id', 'resolved']);
            $table->index(['alert_type', 'created_at']);
            $table->index('notified_clinician');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_alerts');
    }
};
