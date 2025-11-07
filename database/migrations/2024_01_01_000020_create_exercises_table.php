<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('duration')->nullable(); // minutes
            $table->enum('intensity', ['light', 'moderate', 'vigorous'])->default('moderate');
            $table->text('instructions')->nullable();
            $table->string('video_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->json('equipment')->nullable(); // ["yoga mat", "dumbbells"]
            $table->integer('calories_estimate')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('name');
            $table->index('intensity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
