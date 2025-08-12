<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $mauDo = AttributeValue::where('value', 'Đỏ')->firstOrFail();
            $mauXanhDuong = AttributeValue::where('value', 'Xanh dương')->firstOrFail();
            $mauDen = AttributeValue::where('value', 'Đen')->firstOrFail();
            $mauTrang = AttributeValue::where('value', 'Trắng')->firstOrFail();
            $ram8GB = AttributeValue::where('value', '8GB')->firstOrFail();
            $ram16GB = AttributeValue::where('value', '16GB')->firstOrFail();
            $ram32GB = AttributeValue::where('value', '32GB')->firstOrFail();
            $boNho128GB = AttributeValue::where('value', '128GB')->firstOrFail();
            $boNho256GB = AttributeValue::where('value', '256GB')->firstOrFail();
            $boNho512GB = AttributeValue::where('value', '512GB')->firstOrFail();

            $categoryiPhone = Category::where('slug', 'iphone')->firstOrFail();
            $categoryLaptopGaming = Category::where('slug', 'laptop-gaming')->firstOrFail();
            $categoryTablet = Category::where('slug', 'tablet')->firstOrFail();
            $categoryAccessory = Category::where('slug', 'phu-kien')->firstOrFail();
            $categoryMacbook = Category::where('slug', 'macbook')->first();
            $categorySamsungTab = Category::where('slug', 'samsung-tab')->first();
            $categoryTaiNghe = Category::where('slug', 'tai-nghe')->first();

            $brandApple = Brand::firstOrCreate(['name' => 'Apple', 'slug' => 'apple']);
            $brandAsus = Brand::firstOrCreate(['name' => 'Asus', 'slug' => 'asus']);
            $brandSamsung = Brand::firstOrCreate(['name' => 'Samsung', 'slug' => 'samsung']);
            $brandSony = Brand::firstOrCreate(['name' => 'Sony', 'slug' => 'sony']);
            $brandXiaomi = Brand::firstOrCreate(['name' => 'Xiaomi', 'slug' => 'xiaomi']);

            // Sản phẩm điện thoại variable
            $dienThoai = Product::create([
                'name' => 'Điện thoại Flagship XYZ 2025',
                'slug' => Str::slug('Điện thoại Flagship XYZ 2025'),
                'type' => 'variable',
                'short_description' => 'Siêu phẩm công nghệ với màn hình Super Retina và chip A20 Bionic.',
                'long_description' => 'Chi tiết về các công nghệ đột phá, camera siêu nét và thời lượng pin vượt trội của Điện thoại Flagship XYZ 2025.',
                'status' => 'active',
                'is_featured' => true,
                'view_count' => 1500,
                'brand_id' => $brandApple->id,
                'category_id' => $categoryiPhone->id,
            ]);
            $variant1 = ProductVariant::create(['product_id' => $dienThoai->id, 'sku' => 'DT-XYZ-DO-8G', 'price' => 25990000, 'stock' => 50, 'is_active' => true]);
            $variant1->attributeValues()->attach([$mauDo->id, $ram8GB->id]);
            $variant2 = ProductVariant::create(['product_id' => $dienThoai->id, 'sku' => 'DT-XYZ-XANH-16G', 'price' => 28990000, 'stock' => 45, 'is_active' => true]);
            $variant2->attributeValues()->attach([$mauXanhDuong->id, $ram16GB->id]);

            // Sản phẩm laptop variable
            $laptop = Product::create([
                'name' => 'Laptop Gaming ROG Zephyrus G16',
                'slug' => Str::slug('Laptop Gaming ROG Zephyrus G16'),
                'type' => 'variable',
                'short_description' => 'Mạnh mẽ trong thân hình mỏng nhẹ, màn hình Nebula HDR tuyệt đỉnh.',
                'long_description' => 'Trải nghiệm gaming và sáng tạo không giới hạn với CPU Intel Core Ultra 9 và card đồ họa NVIDIA RTX 4080.',
                'status' => 'active',
                'is_featured' => true,
                'view_count' => 950,
                'brand_id' => $brandAsus->id,
                'category_id' => $categoryLaptopGaming->id,
            ]);
            $variant3 = ProductVariant::create(['product_id' => $laptop->id, 'sku' => 'ROG-G16-8G', 'price' => 52000000, 'stock' => 25, 'is_active' => true]);
            $variant3->attributeValues()->attach($ram8GB->id);
            $variant4 = ProductVariant::create(['product_id' => $laptop->id, 'sku' => 'ROG-G16-16G', 'price' => 58500000, 'stock' => 15, 'is_active' => true]);
            $variant4->attributeValues()->attach($ram16GB->id);

            // Sản phẩm điện thoại simple
            $iphoneSE = Product::create([
                'name' => 'iPhone SE 2024',
                'slug' => Str::slug('iPhone SE 2024'),
                'type' => 'simple',
                'short_description' => 'Sức mạnh đáng kinh ngạc trong một thiết kế nhỏ gọn, quen thuộc.',
                'long_description' => 'iPhone SE 2024 trang bị chip A17 Bionic mạnh mẽ, kết nối 5G và camera tiên tiến. Một lựa chọn tuyệt vời với mức giá phải chăng.',
                'status' => 'active',
                'is_featured' => false,
                'view_count' => 12500,
                'brand_id' => $brandApple->id,
                'category_id' => $categoryiPhone->id,
            ]);
            ProductVariant::create([
                'product_id' => $iphoneSE->id,
                'sku' => 'IP-SE-2024',
                'price' => 12490000,
                'stock' => 400,
                'is_active' => true,
            ]);

            // Sản phẩm laptop simple
            $zenbook = Product::create([
                'name' => 'Laptop Asus Zenbook 14 OLED',
                'slug' => Str::slug('Laptop Asus Zenbook 14 OLED'),
                'type' => 'simple',
                'short_description' => 'Mỏng nhẹ tinh tế, màn hình OLED 2.8K rực rỡ, chuẩn Intel Evo.',
                'long_description' => 'Asus Zenbook 14 OLED là sự kết hợp hoàn hảo giữa hiệu năng và tính di động, lý tưởng cho các chuyên gia sáng tạo và doanh nhân năng động.',
                'status' => 'active',
                'is_featured' => false,
                'view_count' => 3100,
                'brand_id' => $brandAsus->id,
                'category_id' => $categoryLaptopGaming->id,
            ]);
            ProductVariant::create([
                'product_id' => $zenbook->id,
                'sku' => 'AS-ZEN14-OLED',
                'price' => 26490000,
                'stock' => 80,
                'is_active' => true,
            ]);

            // Thêm nhiều sản phẩm đa dạng
            $products = [
                [
                    'name' => 'iPad Pro M2 11inch',
                    'slug' => Str::slug('iPad Pro M2 11inch'),
                    'type' => 'variable',
                    'short_description' => 'Màn hình Liquid Retina, chip M2 mạnh mẽ.',
                    'long_description' => 'iPad Pro M2 11inch dành cho công việc sáng tạo và giải trí.',
                    'status' => 'active',
                    'is_featured' => true,
                    'view_count' => 2100,
                    'brand_id' => $brandApple->id,
                    'category_id' => $categoryTablet->id,
                    'variants' => [
                        ['sku' => 'IPAD-M2-128GB', 'price' => 21990000, 'attributeValues' => [$boNho128GB->id]],
                        ['sku' => 'IPAD-M2-256GB', 'price' => 24990000, 'attributeValues' => [$boNho256GB->id]],
                    ],
                ],
                [
                    'name' => 'MacBook Pro M3 14inch',
                    'slug' => Str::slug('MacBook Pro M3 14inch'),
                    'type' => 'variable',
                    'short_description' => 'Hiệu năng đỉnh cao, màn hình mini-LED.',
                    'long_description' => 'MacBook Pro M3 14inch dành cho lập trình viên và designer.',
                    'status' => 'active',
                    'is_featured' => true,
                    'view_count' => 1800,
                    'brand_id' => $brandApple->id,
                    'category_id' => $categoryMacbook ? $categoryMacbook->id : $categoryLaptopGaming->id,
                    'variants' => [
                        ['sku' => 'MBP-M3-256GB', 'price' => 45990000, 'attributeValues' => [$boNho256GB->id]],
                        ['sku' => 'MBP-M3-512GB', 'price' => 52990000, 'attributeValues' => [$boNho512GB->id]],
                    ],
                ],
                [
                    'name' => 'Samsung Galaxy S24 Ultra',
                    'slug' => Str::slug('Samsung Galaxy S24 Ultra'),
                    'type' => 'simple',
                    'short_description' => 'Camera 200MP, pin 5000mAh.',
                    'long_description' => 'Flagship Android mạnh mẽ nhất của Samsung.',
                    'status' => 'active',
                    'is_featured' => true,
                    'view_count' => 3200,
                    'brand_id' => $brandSamsung->id,
                    'category_id' => $categoryiPhone->id,
                    'variants' => [
                        ['sku' => 'SGS24U', 'price' => 33990000, 'attributeValues' => []],
                    ],
                ],
                [
                    'name' => 'Tai nghe Sony WH-1000XM5',
                    'slug' => Str::slug('Tai nghe Sony WH-1000XM5'),
                    'type' => 'simple',
                    'short_description' => 'Chống ồn chủ động, pin 30h.',
                    'long_description' => 'Tai nghe cao cấp dành cho audiophile và dân văn phòng.',
                    'status' => 'active',
                    'is_featured' => false,
                    'view_count' => 900,
                    'brand_id' => $brandSony->id,
                    'category_id' => $categoryTaiNghe ? $categoryTaiNghe->id : $categoryAccessory->id,
                    'variants' => [
                        ['sku' => 'SONY-XM5', 'price' => 8490000, 'attributeValues' => []],
                    ],
                ],
                [
                    'name' => 'Samsung Tab S9 Ultra',
                    'slug' => Str::slug('Samsung Tab S9 Ultra'),
                    'type' => 'variable',
                    'short_description' => 'Màn hình AMOLED 14.6 inch, S Pen đi kèm.',
                    'long_description' => 'Tablet Android mạnh mẽ nhất của Samsung.',
                    'status' => 'active',
                    'is_featured' => false,
                    'view_count' => 1100,
                    'brand_id' => $brandSamsung->id,
                    'category_id' => $categorySamsungTab ? $categorySamsungTab->id : $categoryTablet->id,
                    'variants' => [
                        ['sku' => 'TAB-S9U-256GB', 'price' => 27990000, 'attributeValues' => [$boNho256GB->id]],
                        ['sku' => 'TAB-S9U-512GB', 'price' => 31990000, 'attributeValues' => [$boNho512GB->id]],
                    ],
                ],
                [
                    'name' => 'Xiaomi Redmi Note 13 Pro',
                    'slug' => Str::slug('Xiaomi Redmi Note 13 Pro'),
                    'type' => 'simple',
                    'short_description' => 'Camera 200MP, pin 5000mAh, sạc nhanh 120W.',
                    'long_description' => 'Điện thoại tầm trung cấu hình mạnh, giá tốt.',
                    'status' => 'active',
                    'is_featured' => false,
                    'view_count' => 2100,
                    'brand_id' => $brandXiaomi->id,
                    'category_id' => $categoryiPhone->id,
                    'variants' => [
                        ['sku' => 'RN13PRO', 'price' => 8990000, 'attributeValues' => []],
                    ],
                ],
                [
                    'name' => 'Tai nghe Xiaomi Buds 4 Pro',
                    'slug' => Str::slug('Tai nghe Xiaomi Buds 4 Pro'),
                    'type' => 'simple',
                    'short_description' => 'Chống ồn chủ động, pin 38h.',
                    'long_description' => 'Tai nghe true wireless giá rẻ, chất lượng tốt.',
                    'status' => 'active',
                    'is_featured' => false,
                    'view_count' => 700,
                    'brand_id' => $brandXiaomi->id,
                    'category_id' => $categoryTaiNghe ? $categoryTaiNghe->id : $categoryAccessory->id,
                    'variants' => [
                        ['sku' => 'BUDS4PRO', 'price' => 2490000, 'attributeValues' => []],
                    ],
                ],
            ];

            foreach ($products as $data) {
                $product = Product::create([
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'type' => $data['type'],
                    'short_description' => $data['short_description'],
                    'long_description' => $data['long_description'],
                    'status' => $data['status'],
                    'is_featured' => $data['is_featured'],
                    'view_count' => $data['view_count'],
                    'brand_id' => $data['brand_id'],
                    'category_id' => $data['category_id'],
                ]);
                foreach ($data['variants'] as $variantData) {
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'stock' => 100,
                        'is_active' => true,
                    ]);
                    if (!empty($variantData['attributeValues'])) {
                        $variant->attributeValues()->attach($variantData['attributeValues']);
                    }
                }
            }
        });
    }
}
