<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'vnp_txn_ref')) {
                $table->string('vnp_txn_ref')->nullable()->index()->after('vnpay_url');
            }
            if (!Schema::hasColumn('orders', 'vnp_amount_expected')) {
                // VNPay trả amount *100 => lưu BIGINT để an toàn
                $table->unsignedBigInteger('vnp_amount_expected')->nullable()->after('vnp_txn_ref');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'vnp_txn_ref')) {
                $table->dropColumn('vnp_txn_ref');
            }
            if (Schema::hasColumn('orders', 'vnp_amount_expected')) {
                $table->dropColumn('vnp_amount_expected');
            }
        });
    }
};
