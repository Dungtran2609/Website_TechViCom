<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('banners')->truncate();
        DB::table('banners')->insert([
            [
                'stt' => 1,
                'image' => 'uploads/banners/banner1.jpg',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(20),
                'link' => 'https://techvicom.vn/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stt' => 2,
                'image' => 'uploads/banners/banner2.jpg',
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(25),
                'link' => 'https://techvicom.vn/khuyen-mai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stt' => 3,
                'image' => 'uploads/banners/banner3.jpg',
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(30),
                'link' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
