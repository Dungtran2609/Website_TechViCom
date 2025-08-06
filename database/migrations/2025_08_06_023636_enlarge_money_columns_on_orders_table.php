<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnlargeMoneyColumnsOnOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Chuyển sang DECIMAL(15,0) để lưu tối đa 15 chữ số, không có phần thập phân
            $table->decimal('total_amount', 15, 0)->change();
            $table->decimal('final_total', 15, 0)->change();
            $table->decimal('shipping_fee', 15, 0)->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Quay trở lại DECIMAL(10,0) hoặc integer tùy trước đó
            $table->decimal('total_amount', 10, 0)->change();
            $table->decimal('final_total', 10, 0)->change();
            $table->decimal('shipping_fee', 10, 0)->change();
        });
    }
}