<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profiles')->onDelete('cascade');
            $table->foreignId('uploader_id')->constrained('users')->onDelete('cascade');
            
            $table->string('title')->nullable();
            $table->string('filename');
            $table->string('original_filename');
            $table->string('file_path');
            $table->bigInteger('file_size'); // bytes
            $table->string('mime_type', 100);
            
            $table->text('notes')->nullable();
            $table->enum('category', [
                'lab_result',
                'prescription',
                'medical_record',
                'insurance',
                'imaging',
                'other'
            ])->default('other');
            
            $table->boolean('shared_with_patient')->default(false);
            $table->timestamp('shared_at')->nullable();
            $table->boolean('viewed_by_patient')->default(false);
            $table->timestamp('viewed_at')->nullable();
            
            $table->enum('status', ['uploaded', 'shared', 'archived'])->default('uploaded');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['patient_id', 'status']);
            $table->index('shared_with_patient');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
