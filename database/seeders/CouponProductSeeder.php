<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponProductSeeder extends Seeder
{
    public function run()
    {
        // Giả sử có coupon_id = 1,2 và product_id = 1,2,3
        DB::table('coupon_product')->insert([
            ['coupon_id' => 1, 'product_id' => 1],
            ['coupon_id' => 1, 'product_id' => 2],
            ['coupon_id' => 2, 'product_id' => 3],
        ]);
    }
}
