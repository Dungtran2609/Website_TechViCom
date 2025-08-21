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
            if (!Schema::hasColumn('orders', 'vnpay_discount')) {
                $table->unsignedBigInteger('vnpay_discount')->default(0)->after('vnpay_card_type')->comment('Số tiền giảm từ voucher VNPay (x100)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'vnpay_discount')) {
                $table->dropColumn('vnpay_discount');
            }
        });
    }
};
