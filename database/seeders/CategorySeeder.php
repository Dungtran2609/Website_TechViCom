<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;


class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Tạo danh mục gốc (không có parent)
        $laptop = Category::create([
            'name' => 'Laptop',
            'slug' => Str::slug('Laptop'),
            'parent_id' => null,
            'image' => null,
            'status' => true,
        ]);

        $phone = Category::create([
            'name' => 'Điện thoại',
            'slug' => Str::slug('Điện thoại'),
            'parent_id' => null,
            'image' => null,
            'status' => true,
        ]);

        $tablet = Category::create([
            'name' => 'Tablet',
            'slug' => Str::slug('Tablet'),
            'parent_id' => null,
            'image' => null,
            'status' => true,
        ]);

        $accessory = Category::create([
            'name' => 'Phụ kiện',
            'slug' => Str::slug('Phụ kiện'),
            'parent_id' => null,
            'image' => null,
            'status' => true,
        ]);

        // Tạo danh mục con cho Laptop
        Category::create([
            'name' => 'Laptop Gaming',
            'slug' => Str::slug('Laptop Gaming'),
            'parent_id' => $laptop->id,
            'image' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Laptop Văn phòng',
            'slug' => Str::slug('Laptop Văn phòng'),
            'parent_id' => $laptop->id,
            'image' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'MacBook',
            'slug' => Str::slug('MacBook'),
            'parent_id' => $laptop->id,
            'image' => null,
            'status' => true,
        ]);

        // Tạo danh mục con cho Điện thoại
        Category::create([
            'name' => 'iPhone',
            'slug' => Str::slug('iPhone'),
            'parent_id' => $phone->id,
            'image' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Samsung Galaxy',
            'slug' => Str::slug('Samsung Galaxy'),
            'parent_id' => $phone->id,
            'image' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Xiaomi',
            'slug' => Str::slug('Xiaomi'),
            'parent_id' => $phone->id,
            'image' => null,
            'status' => true,
        ]);

        // Tạo danh mục con cho Tablet
        Category::create([
            'name' => 'iPad',
            'slug' => Str::slug('iPad'),
            'parent_id' => $tablet->id,
            'image' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Samsung Tab',
            'slug' => Str::slug('Samsung Tab'),
            'parent_id' => $tablet->id,
            'image' => null,
            'status' => true,
        ]);

        // Tạo danh mục con cho Phụ kiện
        Category::create([
            'name' => 'Tai nghe',
            'slug' => Str::slug('Tai nghe'),
            'parent_id' => $accessory->id,
            'image' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Sạc và cáp',
            'slug' => Str::slug('Sạc và cáp'),
            'parent_id' => $accessory->id,
            'image' => null,
            'status' => true,
        ]);
    }
}



