<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique(); // mã định danh session duy nhất
            $table->string('username')->nullable();  // tên user (nếu có)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_chat_sessions');
    }
};
