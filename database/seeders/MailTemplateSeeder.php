<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\MailTemplate::insert([
            [
                'name' => 'Chào mừng',
                'subject' => 'Chào mừng bạn đến với TechViCom!',
                'content' => '<p>Xin chào <b>{{ $user->name }}</b>,<br>Chào mừng bạn đã đăng ký tài khoản tại TechViCom!</p>',
                'is_active' => true,
                'auto_send' => true,
                'type' => 'welcome',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gửi mã giảm giá',
                'subject' => 'Nhận mã giảm giá đặc biệt từ TechViCom',
                'content' => '<p>Chào {{ $user->name }},<br>Bạn nhận được mã giảm giá: <b>{{ $coupon_code }}</b></p>',
                'is_active' => true,
                'auto_send' => false,
                'type' => 'coupon',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chúc mừng sinh nhật',
                'subject' => 'TechViCom chúc mừng sinh nhật bạn!',
                'content' => '<p>Chúc mừng sinh nhật {{ $user->name }}!<br>Chúc bạn một ngày thật vui vẻ và nhận nhiều ưu đãi từ TechViCom.</p>',
                'is_active' => true,
                'auto_send' => true,
                'type' => 'birthday',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
