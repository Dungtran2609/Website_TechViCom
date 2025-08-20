<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại tới users
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Khóa ngoại tới products
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // Khóa ngoại tới product_variants (nếu có bảng này)
            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->nullOnDelete();

            // Số lượng
            $table->integer('quantity')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
