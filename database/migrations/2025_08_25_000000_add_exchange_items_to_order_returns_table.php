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
        Schema::table('order_returns', function (Blueprint $table) {
            // Thêm trường exchange_items để lưu danh sách sản phẩm cần đổi
            $table->json('exchange_items')->nullable()->after('client_note');
            
            // Cập nhật enum type để hỗ trợ các loại mới
            $table->enum('type', ['cancel', 'return', 'exchange', 'modify'])->default('return')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_returns', function (Blueprint $table) {
            $table->dropColumn('exchange_items');
            $table->enum('type', ['cancel', 'return'])->default('return')->change();
        });
    }
};
