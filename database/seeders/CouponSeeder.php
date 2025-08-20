<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // Dữ liệu mặc định
        DB::table('coupons')->insert([
            [
                'code'                => 'DISCOUNT10',
                'discount_type'       => 'percent',
                'apply_type'          => 'all',
                'value'               => 10.00,
                'max_discount_amount' => 100000.00,
                'min_order_value'     => 500000.00,
                'max_order_value'     => 5000000.00,
                'max_usage_per_user'  => 5,
                'start_date'          => now()->subDays(10),
                'end_date'            => now()->addMonths(1),
                'status'              => true,
            ],
            // Mã cho đơn hàng lớn
            [
                'code' => 'BIGSALE10',
                'discount_type' => 'percent',
                'apply_type'    => 'all',
                'value' => 10, // 10%
                'max_discount_amount' => 20000000, // 20 triệu
                'min_order_value' => 50000000, // 50 triệu
                'max_order_value' => 1000000000, // 1 tỷ
                'max_usage_per_user' => 1,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addMonths(1),
                'status' => true,
            ],
            [
                'code' => 'VIPFIXED',
                'discount_type' => 'fixed',
                'apply_type'    => 'all',
                'value' => 50000000, // 50 triệu
                'max_discount_amount' => null,
                'min_order_value' => 200000000, // 200 triệu
                'max_order_value' => 2000000000, // 2 tỷ
                'max_usage_per_user' => 1,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addMonths(1),
                'status' => true,
            ],
            [
                'code' => 'MEGAVIP',
                'discount_type' => 'percent',
                'apply_type'    => 'all',
                'value' => 50, // 50%
                'max_discount_amount' => 100000000, // 100 triệu
                'min_order_value' => 500000000, // 500 triệu
                'max_order_value' => 5000000000, // 5 tỷ
                'max_usage_per_user' => 1,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addMonths(1),
                'status' => true,
            ],
            // Các mã nhỏ hơn
            [
                'code' => 'SALE50',
                'discount_type' => 'percent',
                'apply_type'    => 'all',
                'value' => 50,
                'max_discount_amount' => 100000,
                'min_order_value' => 200000,
                'max_order_value' => 1000000,
                'max_usage_per_user' => 2,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(30),
                'status' => 1,
            ],
            [
                'code' => 'SALE100',
                'discount_type' => 'percent',
                'apply_type'    => 'all',
                'value' => 50,
                'max_discount_amount' => 100000,
                'min_order_value' => 200000,
                'max_order_value' => 1000000,
                'max_usage_per_user' => 2,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(30),
                'status' => 1,
            ],
        ]);
    }
}