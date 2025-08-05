<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Apple',
                'description' => 'Chuyên các sản phẩm iPhone, MacBook, iPad.',
                'image' => 'brands/apple.png',
                'status' => 1,
            ],
            [
                'name' => 'Samsung',
                'description' => 'Thương hiệu điện thoại Android và thiết bị gia dụng.',
                'image' => 'brands/samsung.png',
                'status' => 1,
            ],
            [
                'name' => 'ASUS',
                'description' => 'Chuyên laptop văn phòng, gaming, bo mạch chủ.',
                'image' => 'brands/asus.png',
                'status' => 1,
            ],
            [
                'name' => 'Xiaomi',
                'description' => 'Điện thoại thông minh và thiết bị IoT giá rẻ.',
                'image' => 'brands/xiaomi.png',
                'status' => 1,
            ],
            [
                'name' => 'Dell',
                'description' => 'Laptop doanh nhân và máy chủ hiệu suất cao.',
                'image' => 'brands/dell.png',
                'status' => 1,
            ],
            [
                'name' => 'HP',
                'description' => 'Thương hiệu máy tính và thiết bị in ấn phổ biến.',
                'image' => 'brands/hp.png',
                'status' => 1,
            ],
            [
                'name' => 'Lenovo',
                'description' => 'Máy tính văn phòng, gaming và máy trạm.',
                'image' => 'brands/lenovo.png',
                'status' => 1,
            ],
            [
                'name' => 'Sony',
                'description' => 'Thiết bị giải trí, PlayStation và âm thanh cao cấp.',
                'image' => 'brands/sony.png',
                'status' => 1,
            ],
            [
                'name' => 'MSI',
                'description' => 'Chuyên laptop và linh kiện gaming cao cấp.',
                'image' => 'brands/msi.png',
                'status' => 1,
            ],
            [
                'name' => 'Acer',
                'description' => 'Laptop học sinh, sinh viên và văn phòng giá rẻ.',
                'image' => 'brands/acer.png',
                'status' => 1,
            ],
        ];

        foreach ($brands as $data) {
            Brand::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'],
                'image' => $data['image'],
                'status' => $data['status'],
            ]);
        }
    }
}
