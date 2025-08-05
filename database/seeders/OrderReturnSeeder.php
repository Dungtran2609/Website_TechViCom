<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\OrderReturn;
use App\Models\Order;


class OrderReturnSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::inRandomOrder()->take(20)->get();


        foreach ($orders as $order) {
            OrderReturn::create([
                'order_id' => $order->id,
                'reason' => fake()->randomElement([
                    'Khách thay đổi ý định',
                    'Sản phẩm lỗi',
                    'Giao nhầm hàng',
                    'Không đúng mô tả',
                    'Không cần nữa'
                ]),
                'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
                'type' => fake()->randomElement(['return', 'cancel']),
                'requested_at' => now()->subDays(rand(0, 10)),
                'processed_at' => rand(0, 1) ? now() : null,
                'admin_note' => fake()->boolean(50) ? fake()->sentence() : null,
                'client_note' => fake()->boolean(70) ? fake()->sentence() : null,
            ]);
        }
    }
}
