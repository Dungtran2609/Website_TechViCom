<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionProductSeeder extends Seeder
{
    public function run(): void
    {
        // Example: Attach products to promotions
        DB::table('promotion_product')->insert([
            [
                'promotion_id' => 1,
                'product_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'promotion_id' => 1,
                'product_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'promotion_id' => 2,
                'product_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
