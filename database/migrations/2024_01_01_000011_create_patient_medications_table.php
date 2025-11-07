<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profiles')->onDelete('cascade');
            $table->foreignId('medication_id')->constrained()->onDelete('cascade');
            $table->foreignId('prescriber_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->decimal('dosage', 10, 2);
            $table->string('dosage_unit', 20)->default('mg');
            $table->string('frequency'); // e.g., "twice daily", "every 8 hours"
            $table->integer('times_per_day')->default(1);
            $table->json('times')->nullable(); // ["08:00", "20:00"]
            
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('instructions')->nullable();
            $table->text('prescriber_notes')->nullable();
            
            $table->enum('consent_status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->timestamp('consent_given_at')->nullable();
            $table->text('consent_comment')->nullable();
            
            $table->enum('status', ['active', 'paused', 'completed', 'discontinued'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['patient_id', 'status']);
            $table->index('consent_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_medications');
    }
};
