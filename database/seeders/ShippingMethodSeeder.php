<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\ShippingMethod;


class ShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Giao hàng tận nơi',
                'description' => 'Giao hàng tận nơi trong vòng 1-3 ngày làm việc'
            ],
            [
                'name' => 'Nhận hàng tại cửa hàng',
                'description' => 'Nhận hàng trực tiếp tại cửa hàng của chúng tôi'
            ],
        ];


        foreach ($methods as $method) {
            ShippingMethod::create([
                'name' => $method['name'],
                'description' => $method['description'],
            ]);
        }


        // Tạo thêm 18 phương thức phụ nếu cần test thêm
        $additionalMethods = [
            'Giao hàng nhanh',
            'Giao hàng tiết kiệm',
            'Giao hàng đặc biệt',
            'Giao hàng trong ngày',
            'Giao hàng cuối tuần',
            'Giao hàng sáng sớm',
            'Giao hàng buổi tối',
            'Giao hàng theo giờ',
            'Giao hàng ưu tiên',
            'Giao hàng bảo mật',
            'Giao hàng có bảo hiểm',
            'Giao hàng quốc tế',
            'Giao hàng nội thành',
            'Giao hàng ngoại thành',
            'Giao hàng miễn phí',
            'Giao hàng có thu phí',
            'Giao hàng theo địa chỉ',
            'Giao hàng theo yêu cầu'
        ];

        foreach ($additionalMethods as $index => $methodName) {
            ShippingMethod::create([
                'name' => $methodName,
                'description' => 'Phương thức giao hàng ' . strtolower($methodName) . ' với dịch vụ chất lượng cao',
            ]);
        }
    }
}





