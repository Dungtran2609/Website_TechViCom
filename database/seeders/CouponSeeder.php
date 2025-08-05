<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // Dữ liệu từ nhánh main
        DB::table('coupons')->insert([
            [
                'code'                => 'DISCOUNT10',
                'discount_type'       => 'percent',
                'value'               => 10.00,
                'max_discount_amount' => 100000.00,
                'min_order_value'     => 500000.00,
                'max_order_value'     => 5000000.00,
                'max_usage_per_user'  => 5,
                'start_date'          => now()->subDays(10),
                'end_date'            => now()->addMonths(1),
                'status'              => true,
            ],
        ]);

        // Dữ liệu từ nhánh nhung
        Coupon::insert([
            [
                'code' => 'SALE50',
                'discount_type' => 'percent',
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