<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttributeValue;

class AttributeValueSeeder extends Seeder
{
    public function run(): void
    {
        AttributeValue::insert([
            // Màu sắc (attribute_id = 1)
            ['attribute_id' => 1, 'value' => 'Đỏ', 'color_code' => '#FF0000'],
            ['attribute_id' => 1, 'value' => 'Xanh dương', 'color_code' => '#0000FF'],
            ['attribute_id' => 1, 'value' => 'Đen', 'color_code' => '#000000'],
            ['attribute_id' => 1, 'value' => 'Trắng', 'color_code' => '#FFFFFF'],
            // RAM (attribute_id = 2)
            ['attribute_id' => 2, 'value' => '4GB', 'color_code' => null],
            ['attribute_id' => 2, 'value' => '8GB', 'color_code' => null],
            ['attribute_id' => 2, 'value' => '16GB', 'color_code' => null],
            ['attribute_id' => 2, 'value' => '32GB', 'color_code' => null],

            // Bộ nhớ trong (attribute_id = 3)
            ['attribute_id' => 3, 'value' => '64GB', 'color_code' => null],
            ['attribute_id' => 3, 'value' => '128GB', 'color_code' => null],
            ['attribute_id' => 3, 'value' => '256GB', 'color_code' => null],
            ['attribute_id' => 3, 'value' => '512GB', 'color_code' => null],
        ]);
    }
}
