<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponUserSeeder extends Seeder
{
    public function run()
    {
        // Giả sử có coupon_id = 1,2 và user_id = 1,2,3
        DB::table('coupon_user')->insert([
            ['coupon_id' => 1, 'user_id' => 1],
            ['coupon_id' => 1, 'user_id' => 2],
            ['coupon_id' => 2, 'user_id' => 3],
        ]);
    }
}
