<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponPromotionSeeder extends Seeder
{
    public function run(): void
    {
        // Example: Attach promotions to coupons (if coupons already exist)
        DB::table('coupons')->where('id', 1)->update(['promotion_id' => 1]);
        DB::table('coupons')->where('id', 2)->update(['promotion_id' => 2]);
    }
}
