<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\ShippingMethod;


class ShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            'Giao hàng tận nơi',
            'Nhận hàng tại cửa hàng',
        ];


        foreach ($methods as $method) {
            ShippingMethod::create([
                'name' => $method,
                'description' => fake()->boolean() ? fake()->sentence() : null,
            ]);
        }


        // Tạo thêm 18 phương thức phụ nếu cần test thêm
        for ($i = 3; $i <= 20; $i++) {
            ShippingMethod::create([
                'name' => 'Phương thức giao hàng #' . $i,
                'description' => fake()->boolean() ? fake()->sentence() : null,
            ]);
        }
    }
}





