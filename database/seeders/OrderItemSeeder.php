<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $orderId = DB::table('orders')->inRandomOrder()->value('id') ?? 1;
            $variantId = DB::table('product_variants')->inRandomOrder()->value('id') ?? 1;
            $productId = DB::table('products')->inRandomOrder()->value('id') ?? 1;

            $quantity = $faker->numberBetween(1, 5);
            $unitPrice = $faker->randomFloat(2, 10, 200);
            $totalPrice = round($quantity * $unitPrice, 2);

            // Query tên từ products, ảnh từ variants
            $product = DB::table('products')
                ->select('name')
                ->where('id', $productId)
                ->first();

            $variant = DB::table('product_variants')
                ->select('image') // đảm bảo cột này tồn tại
                ->where('id', $variantId)
                ->first();

            DB::table('order_items')->insert([
                'order_id' => $orderId,
                'variant_id' => $variantId,
                'product_id' => $productId,
                'name_product' => $product->name ?? $faker->word,
                'image_product' => $variant->image ?? null,
                'quantity' => $quantity,
                'price' => $unitPrice,
                'total_price' => $totalPrice,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
