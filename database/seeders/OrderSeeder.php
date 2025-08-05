<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\ProductVariant;


class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $address = UserAddress::first();


        if (!$user || !$address) return;


        for ($i = 0; $i < 20; $i++) {
            $total = 0;


            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'payment_method' => 'cod',
                'coupon_id' => null,
                'coupon_code' => null,
                'discount_amount' => 0,
                'shipping_fee' => 50000,
                'total_amount' => 0, // to be updated below
                'final_total' => 0,
                'status' => 'pending',
                'recipient_name' => $address->recipient_name,
                'recipient_phone' => $address->recipient_phone,
                'recipient_address' => $address->full_address,
            ]);


            $products = Product::inRandomOrder()->take(2)->get();


            foreach ($products as $product) {
                $variant = $product->variants()->first();
                if (!$variant) continue;


                $quantity = rand(1, 3);
                $itemTotal = $variant->price * $quantity;
                $total += $itemTotal;


                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'name_product' => $product->name,
                    'image_product' => $product->thumbnail_url,
                    'quantity' => $quantity,
                    'price' => $variant->price,
                    'total_price' => $itemTotal,
                ]);
            }


            $order->update([
                'total_amount' => $total,
                'final_total' => $total + 50000,
            ]);
        }
    }
}


