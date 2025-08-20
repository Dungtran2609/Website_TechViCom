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
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên mẫu mail (VD: Chào mừng, Sinh nhật...)
            $table->string('subject'); // Tiêu đề mail
            $table->text('content'); // Nội dung mail (có thể dùng blade syntax)
            $table->boolean('is_active')->default(true); // Có đang sử dụng không
            $table->boolean('auto_send')->default(false); // Có tự động gửi không
            $table->string('type')->nullable(); // Loại mail (welcome, news, coupon, birthday...)
            $table->timestamps();
            // $table->softDeletes(); // Uncomment this line if softDeletes is needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_templates');
    }
};
