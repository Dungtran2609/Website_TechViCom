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
            $table->string('vnpay_url')->nullable()->after('payment_status');
            $table->string('vnpay_transaction_id')->nullable()->after('vnpay_url');
            $table->string('vnpay_bank_code')->nullable()->after('vnpay_transaction_id');
            $table->string('vnpay_card_type')->nullable()->after('vnpay_bank_code');
            $table->timestamp('paid_at')->nullable()->after('vnpay_card_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'vnpay_url',
                'vnpay_transaction_id', 
                'vnpay_bank_code',
                'vnpay_card_type',
                'paid_at'
            ]);
        });
    }
};
