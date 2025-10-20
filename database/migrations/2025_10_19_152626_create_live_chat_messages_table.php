<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('session_id'); // liên kết với session
            $table->string('sender');     // 'user' hoặc 'admin'
            $table->text('message');
            $table->timestamps();

            $table->foreign('session_id')
                ->references('session_id')
                ->on('live_chat_sessions')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_chat_messages');
    }
};
