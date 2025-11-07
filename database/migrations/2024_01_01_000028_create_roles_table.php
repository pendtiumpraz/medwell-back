<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // super_admin, organization_admin, admin, clinician
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->json('permissions')->nullable(); // Array of permission slugs
            $table->integer('level')->default(0); // Hierarchy level (0=highest)
            $table->boolean('is_system')->default(false); // Cannot be deleted
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
