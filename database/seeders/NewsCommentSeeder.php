<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsCommentSeeder extends Seeder
{
    public function run(): void
    {
        $newsIds = DB::table('news')->pluck('id')->toArray();
        $userIds = DB::table('users')->pluck('id')->take(5)->toArray();

        $sampleComments = [
            'Bài viết rất hữu ích, cảm ơn bạn!',
            'Tôi đã áp dụng và thấy hiệu quả ngay.',
            'Có thể giải thích thêm phần này được không?',
            'Sản phẩm này mình đã dùng, rất ok.',
            'Rất mong có thêm bài viết tương tự.',
            'Thông tin chi tiết và rõ ràng.',
            'Bài viết hay nhưng nên bổ sung thêm ví dụ.',
            'Cảm ơn bạn đã chia sẻ!',
            'Mình sẽ giới thiệu bài viết này cho bạn bè.',
            'Rất thích nội dung kiểu này.',
        ];

        foreach ($newsIds as $newsId) {
            // Tạo 3–5 bình luận gốc cho mỗi bài viết
            $parentComments = [];
            $parentCount = rand(3, 5);

            for ($i = 0; $i < $parentCount; $i++) {
                $parentId = DB::table('news_comments')->insertGetId([
                    'user_id' => $userIds[array_rand($userIds)] ?? null,
                    'news_id' => $newsId,
                    'parent_id' => null,
                    'content' => $sampleComments[array_rand($sampleComments)],
                    'is_hidden' => rand(0, 1),
                    'likes_count' => rand(0, 10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $parentComments[] = $parentId;

                // Tạo 0–2 bình luận trả lời cho mỗi bình luận gốc
                $replyCount = rand(0, 2);
                for ($j = 0; $j < $replyCount; $j++) {
                    DB::table('news_comments')->insert([
                        'user_id' => $userIds[array_rand($userIds)] ?? null,
                        'news_id' => $newsId,
                        'parent_id' => $parentId,
                        'content' => '↪ ' . $sampleComments[array_rand($sampleComments)],
                        'is_hidden' => rand(0, 1),
                        'likes_count' => rand(0, 5),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
