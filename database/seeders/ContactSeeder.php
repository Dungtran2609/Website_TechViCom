<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy danh sách ID của users hiện có
        $userIds = DB::table('users')->pluck('id')->toArray();

        // Danh sách liên hệ
        $contacts = collect([
            ['Nguyễn Văn A', 'vana@example.com', '0909123456', 'Hỏi về sản phẩm', 'Cho tôi hỏi sản phẩm này còn hàng không?', false, 0],
            ['Trần Thị B', 'thib@example.com', '0911222333', 'Thắc mắc giao hàng', 'Tôi muốn biết khi nào đơn hàng được giao.', true, 1],
            ['Lê Văn C', 'vanc@example.com', '0922333444', 'Hủy đơn hàng', 'Tôi muốn hủy đơn hàng vừa đặt.', false, null],
            ['Phạm Thị D', 'thid@example.com', '0933444555', 'Phản hồi dịch vụ', 'Dịch vụ chăm sóc khách hàng rất tốt.', true, null],
            ['Đỗ Minh E', 'minhe@example.com', '0944555666', 'Đổi hàng', 'Tôi muốn đổi sản phẩm vì bị lỗi.', false, 2],
            ['Hoàng Thị F', 'thif@example.com', '0955666777', 'Cần tư vấn', 'Bạn có thể tư vấn giúp tôi sản phẩm phù hợp?', true, null],
            ['Ngô Văn G', 'vang@example.com', '0966777888', 'Góp ý', 'Website của bạn rất dễ sử dụng.', false, null],
            ['Vũ Thị H', 'thih@example.com', '0977888999', 'Thanh toán', 'Tôi muốn đổi phương thức thanh toán.', true, 3],
            ['Bùi Văn I', 'vani@example.com', '0988999000', 'Khuyến mãi', 'Cửa hàng hiện có chương trình khuyến mãi nào?', false, null],
            ['Lý Thị K', 'thik@example.com', '0999000111', 'Đặt hàng lỗi', 'Tôi không thể đặt hàng trên website.', true, 4],
        ]);

        // Duyệt và chèn từng contact
        $data = $contacts->map(function ($item) use ($userIds) {
            return [
                'name'       => $item[0],
                'email'      => $item[1],
                'phone'      => $item[2],
                'subject'    => $item[3],
                'message'    => $item[4],
                'is_read'    => $item[5],
                'user_id'    => isset($item[6]) && isset($userIds[$item[6]]) ? $userIds[$item[6]] : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        })->toArray();

        DB::table('contacts')->insert($data);
    }
}
