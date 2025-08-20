<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Example: Attach categories to promotions
        DB::table('promotion_category')->insert([
            [
                'promotion_id' => 1,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'promotion_id' => 2,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
