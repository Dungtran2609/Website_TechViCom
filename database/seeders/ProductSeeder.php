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
            $ram8GB = AttributeValue::where('value', '8GB')->firstOrFail();
            $ram16GB = AttributeValue::where('value', '16GB')->firstOrFail();

            $categoryiPhone = Category::where('slug', 'iphone')->firstOrFail();
            $categoryLaptopGaming = Category::where('slug', 'laptop-gaming')->firstOrFail();

            $brandApple = Brand::firstOrCreate(['name' => 'Apple', 'slug' => 'apple']);
            $brandAsus = Brand::firstOrCreate(['name' => 'Asus', 'slug' => 'asus']);

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
        });
    }
}