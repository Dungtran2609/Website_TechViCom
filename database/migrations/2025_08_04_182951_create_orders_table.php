<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained('user_addresses')->onDelete('cascade');
            $table->string('payment_method');
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
            $table->string('coupon_code')->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable()->default(0);
            $table->decimal('shipping_fee', 10, 2)->nullable()->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('final_total', 10, 2);
            $table->string('status')->default('pending');
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('recipient_address');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
