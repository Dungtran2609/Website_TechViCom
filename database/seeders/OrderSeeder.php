<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $paymentMethods = ['credit_card', 'paypal', 'bank_transfer'];
        $statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed'];
        for ($i = 0; $i < 20; $i++) {
            // Sinh thời gian tạo và shipped_at, deleted_at
            $createdAt = $faker->dateTimeBetween('-6 months', 'now');
            $shippedAt = $faker->boolean(60) ? $faker->dateTimeBetween($createdAt, 'now') : null;
            $deletedAt = $faker->boolean(10) ? Carbon::instance($faker->dateTimeBetween($createdAt, 'now')) : null;

            // Lấy ngẫu nhiên khóa ngoại
            $userId = DB::table('users')->inRandomOrder()->value('id') ?? 1;
            $addressId = DB::table('user_addresses')->inRandomOrder()->value('id') ?? 1;
            $couponId = $faker->boolean(30)
                ? DB::table('coupons')->inRandomOrder()->value('id')
                : null;
            $shippingMethodId = $faker->boolean(50)
                ? DB::table('shipping_methods')->inRandomOrder()->value('id')
                : null;

            // Tính toán giá
            $subtotal = $faker->randomFloat(2, 50, 500);
            $discount = $faker->randomFloat(2, 0, min(50, $subtotal));
            $shippingFee = $faker->randomFloat(2, 5, 20);
            $totalAmount = $subtotal;
            $finalTotal = $subtotal - $discount + $shippingFee;

            DB::table('orders')->insert([
                'user_id' => $userId,
                'address_id' => $addressId,
                'payment_method' => $faker->randomElement($paymentMethods),
                'coupon_id' => $couponId,
                'coupon_code' => $couponId ? strtoupper(Str::random(8)) : null,
                'discount_amount' => $discount,
                'shipping_fee' => $shippingFee,
                'total_amount' => $totalAmount,
                'final_total' => $finalTotal,
                'status' => 'pending',
                'payment_status' => 'pending',
                'recipient_name' => $faker->name,
                'recipient_phone' => $faker->phoneNumber,
                'recipient_address' => $faker->address,
                'shipped_at' => $shippedAt,
                'shipping_method_id' => $shippingMethodId,
                'created_at' => $createdAt,
                'updated_at' => now(),
                'deleted_at' => $deletedAt,
            ]);
        }
    }
}
