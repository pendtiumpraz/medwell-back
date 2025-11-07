<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predefined_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');
            
            $table->text('message_text');
            $table->string('category', 100)->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->index(['organization_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predefined_messages');
    }
};
