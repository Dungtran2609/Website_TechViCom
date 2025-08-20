<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ (nếu cần)
        DB::table('promotions')->delete();

        // Thêm một số chương trình khuyến mãi mẫu
        Promotion::create([
            'name' => 'Back to School',
            'slug' => Str::slug('Back to School') . '-' . uniqid(),
            'description' => 'Khuyến mãi mùa tựu trường cho học sinh, sinh viên.',
            'flash_type' => 'all',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(10),
            'status' => 1,
        ]);
        Promotion::create([
            'name' => 'Black Friday',
            'slug' => Str::slug('Black Friday') . '-' . uniqid(),
            'description' => 'Giảm giá sốc dịp Black Friday cho toàn bộ sản phẩm.',
            'flash_type' => 'flash_sale',
            'discount_type' => 'amount',
            'discount_value' => 50000,
            'start_date' => now()->addDays(30),
            'end_date' => now()->addDays(35),
            'status' => 1,
        ]);
        Promotion::create([
            'name' => 'Tết Sale',
            'slug' => Str::slug('Tết Sale') . '-' . uniqid(),
            'description' => 'Chương trình khuyến mãi lớn dịp Tết Nguyên Đán.',
            'flash_type' => 'category',
            'discount_type' => 'percent',
            'discount_value' => 15,
            'start_date' => now()->addMonths(3),
            'end_date' => now()->addMonths(3)->addDays(10),
            'status' => 0,
        ]);
    }
}
