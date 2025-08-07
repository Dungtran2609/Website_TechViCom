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
        Schema::table('orders', function (Blueprint $table) {
            // Làm user_id và address_id nullable để hỗ trợ khách vãng lai
            $table->dropForeign(['user_id']);
            $table->dropForeign(['address_id']);
            
            $table->foreignId('user_id')->nullable()->change();
            $table->foreignId('address_id')->nullable()->change();
            
            // Thêm các trường cho thông tin khách vãng lai
            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_phone')->nullable()->after('guest_email');
            
            // Thêm payment_status column
            $table->string('payment_status')->default('pending')->after('status');
            
            // Thêm shipping_method_id nếu chưa có
            if (!Schema::hasColumn('orders', 'shipping_method_id')) {
                $table->foreignId('shipping_method_id')->nullable()->after('shipped_at');
            }
            
            // Thêm lại foreign keys với nullable
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('address_id')->references('id')->on('user_addresses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Xóa foreign keys
            $table->dropForeign(['user_id']);
            $table->dropForeign(['address_id']);
            
            // Xóa các trường đã thêm
            $table->dropColumn(['guest_name', 'guest_email', 'guest_phone', 'payment_status']);
            
            // Xóa shipping_method_id nếu đã thêm trong migration này
            if (Schema::hasColumn('orders', 'shipping_method_id')) {
                $table->dropColumn('shipping_method_id');
            }
            
            // Đặt lại user_id và address_id thành not nullable
            $table->foreignId('user_id')->change();
            $table->foreignId('address_id')->change();
            
            // Thêm lại foreign keys ban đầu
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('user_addresses')->onDelete('cascade');
        });
    }
};
