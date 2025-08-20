<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Chuyển các trường số trong bảng coupons sang dạng bigInteger (bigint)
        Schema::table('coupons', function (Blueprint $table) {
            $table->bigInteger('value')->change();
            $table->bigInteger('max_discount_amount')->nullable()->change();
            $table->bigInteger('min_order_value')->nullable()->change();
            $table->bigInteger('max_order_value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nếu muốn revert về kiểu cũ (giả sử là integer)
        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('value')->change();
            $table->integer('max_discount_amount')->nullable()->change();
            $table->integer('min_order_value')->nullable()->change();
            $table->integer('max_order_value')->nullable()->change();
        });
    }
};
