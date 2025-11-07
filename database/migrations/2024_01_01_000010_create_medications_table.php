<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->json('dosage_forms')->nullable();
            $table->json('strengths')->nullable();
            $table->string('route')->nullable();
            $table->boolean('requires_prescription')->default(true);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('manufacturer')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('name');
            $table->index('generic_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
