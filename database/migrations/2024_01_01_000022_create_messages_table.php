<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->nullable()->constrained('users')->onDelete('cascade');
            
            $table->enum('message_type', ['private', 'broadcast', 'system'])->default('private');
            $table->string('subject')->nullable();
            $table->text('body');
            
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['recipient_id', 'read_at']);
            $table->index('message_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
