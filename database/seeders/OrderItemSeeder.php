<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;


class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::inRandomOrder()->take(20)->get();


        foreach ($orders as $order) {
            $products = Product::inRandomOrder()->take(2)->get();


            foreach ($products as $product) {
                $variant = $product->variants()->inRandomOrder()->first();


                if (!$variant) continue;


                $quantity = rand(1, 3);
                $price = $variant->price;
                $total = $price * $quantity;


                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'name_product' => $product->name,
                    'image_product' => $product->thumbnail_url ?? null,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $total,
                ]);
            }
        }
    }
}







