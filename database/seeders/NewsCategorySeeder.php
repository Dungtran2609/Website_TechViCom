<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Khuyến mãi',
                'slug' => 'khuyen-mai',
            ],
            [
                'name' => 'Tin tức công nghệ',
                'slug' => 'tin-tuc-cong-nghe',
            ],
            [
                'name' => 'Hướng dẫn sử dụng sản phẩm',
                'slug' => 'huong-dan-su-dung-san-pham',
            ],
            [
                'name' => 'Đánh giá sản phẩm',
                'slug' => 'danh-gia-san-pham',
            ],
            [
                'name' => 'Mẹo vặt công nghệ',
                'slug' => 'meo-vat-cong-nghe',
            ],
            [
                'name' => 'Sự kiện và ra mắt sản phẩm',
                'slug' => 'su-kien-ra-mat-san-pham',
            ],
            [
                'name' => 'Review cửa hàng',
                'slug' => 'review-cua-hang',
            ],
            [
                'name' => 'Chăm sóc khách hàng',
                'slug' => 'cham-soc-khach-hang',
            ],
            [
                'name' => 'Mua sắm trực tuyến',
                'slug' => 'mua-sam-truc-tuyen',
            ],
            [
                'name' => 'Sản phẩm mới',
                'slug' => 'san-pham-moi',
            ],
        ];


        foreach ($categories as $category) {
            DB::table('news_categories')->insert([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
