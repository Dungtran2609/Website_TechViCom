<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('notification_id')->constrained('notifications')->cascadeOnDelete();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            $table->unique(['user_id', 'notification_id']); // tránh trùng lặp
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
