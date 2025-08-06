<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductCommentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_comments')->truncate();

        // Lấy id sản phẩm và user
        $productIds = DB::table('products')->pluck('id')->toArray();
        $userIds = DB::table('users')->pluck('id')->toArray();

        $comments = [];
        for ($i = 1; $i <= 10; $i++) {
            $comments[] = [
                'product_id' => $productIds[array_rand($productIds)],
                'user_id' => $userIds[array_rand($userIds)],
                'content' => 'Bình luận mẫu số ' . $i . ' cho sản phẩm.',
                'rating' => rand(3, 5),
                'status' => 'approved',
                'parent_id' => null,
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ];
        }
        // Thêm 3 bình luận trả lời (reply)
        for ($i = 1; $i <= 3; $i++) {
            $comments[] = [
                'product_id' => $comments[$i]['product_id'],
                'user_id' => $userIds[array_rand($userIds)],
                'content' => 'Trả lời cho bình luận ' . $i,
                'rating' => null,
                'status' => 'approved',
                'parent_id' => $i, // trả lời cho comment id $i
                'created_at' => Carbon::now()->subDays(rand(1, 10)),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ];
        }
        DB::table('product_comments')->insert($comments);
    }
}
