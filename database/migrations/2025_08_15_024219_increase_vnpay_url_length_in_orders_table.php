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
            $table->string('vnpay_url', 2000)->nullable()->change(); // Tăng từ 500 lên 2000 ký tự, cho phép NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('vnpay_url', 500)->nullable()->change(); // Quay lại 500 ký tự, cho phép NULL
        });
    }
};
