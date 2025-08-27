<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LogoSeeder extends Seeder
{
    public function run()
    {
        // Copy logo mặc định vào storage nếu chưa có
        $defaultPath = 'admin_css/images/logo_techvicom.png';
        $storagePath = 'logos/logo_techvicom.png';
        if (!Storage::disk('public')->exists($storagePath)) {
            if (file_exists(public_path($defaultPath))) {
                Storage::disk('public')->put($storagePath, file_get_contents(public_path($defaultPath)));
            }
        }
        // Thêm logo client
        DB::table('logos')->insert([
            'type' => 'client',
            'path' => $storagePath,
            'alt' => 'Logo trang chủ',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Thêm logo admin
        DB::table('logos')->insert([
            'type' => 'admin',
            'path' => $storagePath,
            'alt' => 'Logo admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
