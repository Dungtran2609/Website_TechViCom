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
        Schema::table('promotion_product', function (Blueprint $table) {
            $table->decimal('discount_percent', 5, 2)->nullable()->after('sale_price')->comment('Phần trăm giảm giá (0-100)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotion_product', function (Blueprint $table) {
            $table->dropColumn('discount_percent');
        });
    }
};
