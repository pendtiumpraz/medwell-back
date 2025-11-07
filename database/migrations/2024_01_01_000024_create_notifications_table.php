<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('type', [
                'medication',
                'alert',
                'message',
                'document',
                'schedule',
                'system',
                'appointment'
            ]);
            
            $table->string('title');
            $table->text('body');
            $table->json('data')->nullable();
            $table->string('action_url')->nullable();
            
            $table->timestamp('read_at')->nullable();
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            
            $table->timestamps();
            
            $table->index(['user_id', 'read_at']);
            $table->index(['type', 'created_at']);
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
