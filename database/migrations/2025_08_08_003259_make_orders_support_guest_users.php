<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // 1. Xóa các khóa ngoại cũ (Laravel tự tìm tên)
            $table->dropForeign(['user_id']);
            $table->dropForeign(['address_id']);

            // 2. Thay đổi các cột hiện có thành nullable (SỬA LỖI CÚ PHÁP)
            // Phải định nghĩa lại toàn bộ cột khi dùng change()
            $table->foreignId('user_id')->nullable()->change();
            $table->foreignId('address_id')->nullable()->change();

            // 3. Thêm các cột mới
            $table->string('guest_name')->nullable()->after('address_id');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_phone')->nullable()->after('guest_email');
            $table->string('payment_status')->default('pending')->after('status');

            if (!Schema::hasColumn('orders', 'shipping_method_id')) {
                $table->foreignId('shipping_method_id')->nullable()->constrained()->onDelete('set null');
            }

            // 4. Thêm lại các khóa ngoại với ràng buộc mới
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('address_id')->references('id')->on('user_addresses')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // 1. Xóa các khóa ngoại đã thêm ở phương thức up()
            $table->dropForeign(['user_id']);
            $table->dropForeign(['address_id']);
            if (Schema::hasColumn('orders', 'shipping_method_id')) {
                 $table->dropForeign(['shipping_method_id']);
            }

            $table->dropColumn(['guest_name', 'guest_email', 'guest_phone', 'payment_status']);
            if (Schema::hasColumn('orders', 'shipping_method_id')) {
                $table->dropColumn('shipping_method_id');
            }


            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreignId('address_id')->nullable(false)->change();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('user_addresses')->onDelete('cascade');
        });
    }
};
