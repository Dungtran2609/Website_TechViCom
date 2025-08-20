<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponCategorySeeder extends Seeder
{
    public function run()
    {
        // Giả sử có coupon_id = 1,2 và category_id = 1,2,3
        DB::table('coupon_category')->insert([
            ['coupon_id' => 1, 'category_id' => 1],
            ['coupon_id' => 1, 'category_id' => 2],
            ['coupon_id' => 2, 'category_id' => 3],
        ]);
    }
}
