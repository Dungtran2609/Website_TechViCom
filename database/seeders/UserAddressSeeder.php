<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $districts = [
            'Ba Đình', 'Hoàn Kiếm', 'Tây Hồ', 'Long Biên', 'Cầu Giấy',
            'Đống Đa', 'Hai Bà Trưng', 'Hoàng Mai', 'Thanh Xuân'
        ];

        $wards = [
            'Phúc Xá', 'Trúc Bạch', 'Vĩnh Phúc', 'Điện Biên', 'Đội Cấn',
            'Hàng Bài', 'Hàng Trống', 'Phúc Tân', 'Chương Dương',
            'Dịch Vọng', 'Nghĩa Tân', 'Quan Hoa', 'Yên Hòa',
            'Ô Chợ Dừa', 'Láng Hạ', 'Kim Liên', 'Thổ Quan',
            'Tân Mai', 'Yên Sở', 'Hoàng Văn Thụ', 'Giáp Bát'
        ];

        $users = DB::table('users')->pluck('id')->toArray();
        $now = now();
        $insertData = [];

        foreach ($users as $userId) {
            // Địa chỉ mặc định
            $insertData[] = [
                'user_id' => $userId,
                'address_line' => $faker->streetAddress(),
                'ward' => $faker->randomElement($wards),
                'district' => $faker->randomElement($districts),
                'city' => 'Hà Nội',
                'is_default' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Thêm 2 địa chỉ phụ
            for ($i = 0; $i < 2; $i++) {
                $insertData[] = [
                    'user_id' => $userId,
                    'address_line' => $faker->streetAddress(),
                    'ward' => $faker->randomElement($wards),
                    'district' => $faker->randomElement($districts),
                    'city' => 'Hà Nội',
                    'is_default' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('user_addresses')->insert($insertData);
    }
}
