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
        $parent1 = Category::create([
            'name' => 'Laptop',
            'slug' => Str::slug('Laptop'),
            'parent_id' => null,
            'image' => null,
            'status' => true,
        ]);


        $parent2 = Category::create([
            'name' => 'Điện thoại',
            'slug' => Str::slug('Điện thoại'),
            'parent_id' => null,
            'image' => null,
            'status' => true,
        ]);


        // Tạo danh mục con
        Category::create([
            'name' => 'Laptop Gaming',
            'slug' => Str::slug('Laptop Gaming'),
            'parent_id' => $parent1->id,
            'image' => null,
            'status' => true,
        ]);


        Category::create([
            'name' => 'Laptop Văn phòng',
            'slug' => Str::slug('Laptop Văn phòng'),
            'parent_id' => $parent1->id,
            'image' => null,
            'status' => false,
        ]);


        Category::create([
            'name' => 'Điện thoại Apple',
            'slug' => Str::slug('Điện thoại Apple'),
            'parent_id' => $parent2->id,
            'image' => null,
            'status' => true,
        ]);
    }
}



