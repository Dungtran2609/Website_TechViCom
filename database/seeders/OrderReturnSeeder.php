<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class OrderReturnSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $statuses = ['pending', 'approved', 'rejected'];
        $types = ['cancel', 'return'];
        $reasons = [
            'Khách thay đổi ý định',
            'Sản phẩm lỗi',
            'Giao nhầm hàng',
            'Không đúng mô tả',
            'Không cần nữa'
        ];

        for ($i = 0; $i < 20; $i++) {
            // Lấy ngẫu nhiên order_id
            $orderId = DB::table('orders')->inRandomOrder()->value('id') ?? 1;

            // Sinh thời gian requested và processed
            $requestedAt = $faker->dateTimeBetween('-30 days', 'now');
            $processedAt = $faker->boolean(50)
                ? $faker->dateTimeBetween($requestedAt, 'now')
                : null;

            DB::table('order_returns')->insert([
                'order_id' => $orderId,
                'reason' => $faker->boolean(80)
                    ? $faker->randomElement($reasons)
                    : null,
                'status' => $faker->randomElement($statuses),
                'type' => $faker->randomElement($types),
                'requested_at' => $requestedAt,
                'processed_at' => $processedAt,
                'admin_note' => $faker->boolean(50)
                    ? $faker->sentence()
                    : null,
                'client_note' => $faker->boolean(70)
                    ? $faker->sentence()
                    : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
