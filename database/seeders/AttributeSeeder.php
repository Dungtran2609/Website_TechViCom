<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Màu sắc',
                'type' => 'color',
            ],
            [
                'name' => 'RAM',
                'type' => 'text',
            ],
            [
                'name' => 'Bộ nhớ trong',
                'type' => 'text',
            ],
        ];

        foreach ($attributes as $attr) {
            Attribute::create([
                'name' => $attr['name'],
                'type' => $attr['type'],
                'slug' => Str::slug($attr['name']),
            ]);
        }
    }
}
