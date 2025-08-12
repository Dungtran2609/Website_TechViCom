<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        // Danh mục và các bài viết mẫu tương ứng
        $categories = [
            'Khuyến mãi' => [
                ['Giảm giá 50% cho đơn hàng đầu tiên', 'Hãy nhanh tay nhận ưu đãi 50% khi mua hàng lần đầu tiên tại cửa hàng chúng tôi.'],
                ['Mua 1 tặng 1 cuối tuần', 'Chương trình mua 1 tặng 1 áp dụng từ thứ 6 đến chủ nhật hàng tuần.'],
            ],
            'Tin tức công nghệ' => [
                ['iPhone 15 chính thức ra mắt', 'Apple đã giới thiệu iPhone 15 với nhiều cải tiến về hiệu năng và camera.'],
                ['Samsung trình làng Galaxy Z Flip6', 'Samsung tiếp tục đẩy mạnh phân khúc điện thoại gập với Galaxy Z Flip6.'],
            ],
            'Hướng dẫn sử dụng sản phẩm' => [
                ['Hướng dẫn sử dụng máy ép chậm', 'Bài viết sẽ giúp bạn hiểu rõ cách sử dụng máy ép chậm để giữ nguyên dưỡng chất.'],
                ['Cách bảo quản tai nghe không dây', 'Giữ gìn tai nghe đúng cách giúp kéo dài tuổi thọ và giữ âm thanh tốt.'],
            ],
            'Đánh giá sản phẩm' => [
                ['Đánh giá laptop Asus Zenbook 14', 'Asus Zenbook 14 nổi bật với thiết kế mỏng nhẹ, pin trâu và hiệu năng ổn định.'],
                ['So sánh Xiaomi Redmi Note 12 và Realme 11', 'Cùng so sánh hai sản phẩm tầm trung hot nhất hiện nay.'],
            ],
            // Bạn có thể thêm dữ liệu tương tự cho các danh mục còn lại...
        ];

        $authorIds = DB::table('users')->pluck('id')->take(5)->toArray(); // lấy tối đa 5 tác giả

        foreach ($categories as $categoryName => $newsItems) {
            $categoryId = DB::table('news_categories')->where('name', $categoryName)->value('id');

            if (!$categoryId) {
                continue; // Bỏ qua nếu không tìm thấy danh mục
            }

            foreach ($newsItems as $item) {
                DB::table('news')->insert([
                    'category_id' => $categoryId,
                    'title' => $item[0],
                    'content' => $item[1],
                    'image' => 'uploads/news/default.jpg', // Bạn có thể dùng ảnh mặc định
                    'author_id' => $authorIds[array_rand($authorIds)] ?? null,
                    'status' => 'published',
                    'published_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
