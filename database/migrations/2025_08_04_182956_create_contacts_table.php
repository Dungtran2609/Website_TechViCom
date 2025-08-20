<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            
            // Thông tin người liên hệ
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');

            // Người gửi (nếu có tài khoản)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // Người xử lý liên hệ (admin hoặc nhân viên)
            $table->foreignId('handled_by')->nullable()->constrained('users')->onDelete('set null');

            // Trạng thái xử lý
            $table->enum('status', ['pending', 'in_progress', 'responded', 'rejected'])->default('pending');

            // Nội dung phản hồi và thời gian phản hồi
            $table->text('response')->nullable();
            $table->timestamp('responded_at')->nullable();

            // Cờ đánh dấu đã đọc (hiển thị thông báo mới)
            $table->boolean('is_read')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
