<?php

namespace App\Http\Controllers\Client\Address;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClientAddressController extends Controller
{
    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        try {
            // Sample provinces data - you can replace with your database or API call
            $provinces = [
                ['code' => '01', 'name' => 'Hà Nội'],
                ['code' => '79', 'name' => 'TP Hồ Chí Minh'],
                ['code' => '48', 'name' => 'Đà Nẵng'],
                ['code' => '31', 'name' => 'Hải Phòng'],
                ['code' => '92', 'name' => 'Cần Thơ'],
                ['code' => '02', 'name' => 'Hà Giang'],
                ['code' => '04', 'name' => 'Cao Bằng'],
                ['code' => '06', 'name' => 'Bắc Kạn'],
                ['code' => '08', 'name' => 'Tuyên Quang'],
                ['code' => '10', 'name' => 'Lào Cai'],
                ['code' => '11', 'name' => 'Điện Biên'],
                ['code' => '12', 'name' => 'Lai Châu'],
                ['code' => '14', 'name' => 'Sơn La'],
                ['code' => '15', 'name' => 'Yên Bái'],
                ['code' => '17', 'name' => 'Hoà Bình'],
                ['code' => '19', 'name' => 'Thái Nguyên'],
                ['code' => '20', 'name' => 'Lạng Sơn'],
                ['code' => '22', 'name' => 'Quảng Ninh'],
                ['code' => '24', 'name' => 'Bắc Giang'],
                ['code' => '25', 'name' => 'Phú Thọ'],
                ['code' => '26', 'name' => 'Vĩnh Phúc'],
                ['code' => '27', 'name' => 'Bắc Ninh'],
                ['code' => '30', 'name' => 'Hải Dương'],
                ['code' => '33', 'name' => 'Hưng Yên'],
                ['code' => '34', 'name' => 'Thái Bình'],
                ['code' => '35', 'name' => 'Hà Nam'],
                ['code' => '36', 'name' => 'Nam Định'],
                ['code' => '37', 'name' => 'Ninh Bình'],
                ['code' => '38', 'name' => 'Thanh Hóa'],
                ['code' => '40', 'name' => 'Nghệ An'],
                ['code' => '42', 'name' => 'Hà Tĩnh'],
                ['code' => '44', 'name' => 'Quảng Bình'],
                ['code' => '45', 'name' => 'Quảng Trị'],
                ['code' => '46', 'name' => 'Thừa Thiên Huế'],
                ['code' => '49', 'name' => 'Quảng Nam'],
                ['code' => '51', 'name' => 'Quảng Ngãi'],
                ['code' => '52', 'name' => 'Bình Định'],
                ['code' => '54', 'name' => 'Phú Yên'],
                ['code' => '56', 'name' => 'Khánh Hòa'],
                ['code' => '58', 'name' => 'Ninh Thuận'],
                ['code' => '60', 'name' => 'Bình Thuận'],
                ['code' => '62', 'name' => 'Kon Tum'],
                ['code' => '64', 'name' => 'Gia Lai'],
                ['code' => '66', 'name' => 'Đắk Lắk'],
                ['code' => '67', 'name' => 'Đắk Nông'],
                ['code' => '68', 'name' => 'Lâm Đồng'],
                ['code' => '70', 'name' => 'Bình Phước'],
                ['code' => '72', 'name' => 'Tây Ninh'],
                ['code' => '74', 'name' => 'Bình Dương'],
                ['code' => '75', 'name' => 'Đồng Nai'],
                ['code' => '77', 'name' => 'Bà Rịa - Vũng Tàu'],
                ['code' => '80', 'name' => 'Long An'],
                ['code' => '82', 'name' => 'Tiền Giang'],
                ['code' => '83', 'name' => 'Bến Tre'],
                ['code' => '84', 'name' => 'Trà Vinh'],
                ['code' => '86', 'name' => 'Vĩnh Long'],
                ['code' => '87', 'name' => 'Đồng Tháp'],
                ['code' => '89', 'name' => 'An Giang'],
                ['code' => '91', 'name' => 'Kiên Giang'],
                ['code' => '93', 'name' => 'Hậu Giang'],
                ['code' => '94', 'name' => 'Sóc Trăng'],
                ['code' => '95', 'name' => 'Bạc Liêu'],
                ['code' => '96', 'name' => 'Cà Mau']
            ];

            // Return data directly as array - JavaScript expects this format
            return response()->json($provinces);

        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * Get districts by province code
     */
    public function getDistricts($provinceCode)
    {
        try {
            // Sample districts data based on province code
            $districts = $this->getDistrictsByProvince($provinceCode);

            // Return data directly as array - JavaScript expects this format
            return response()->json($districts);

        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * Get wards by district code
     */
    public function getWards($districtCode)
    {
        try {
            // Sample wards data based on district code
            $wards = $this->getWardsByDistrict($districtCode);

            // Return data directly as array - JavaScript expects this format
            return response()->json($wards);

        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * Get districts by province code
     */
    private function getDistrictsByProvince($provinceCode)
    {
        // Sample data - you can replace with database queries
        $districtData = [
            '01' => [ // Hà Nội
                ['code' => '001', 'name' => 'Quận Ba Đình'],
                ['code' => '002', 'name' => 'Quận Hoàn Kiếm'],
                ['code' => '003', 'name' => 'Quận Tây Hồ'],
                ['code' => '004', 'name' => 'Quận Long Biên'],
                ['code' => '005', 'name' => 'Quận Cầu Giấy'],
                ['code' => '006', 'name' => 'Quận Đống Đa'],
                ['code' => '007', 'name' => 'Quận Hai Bà Trưng'],
                ['code' => '008', 'name' => 'Quận Hoàng Mai'],
                ['code' => '009', 'name' => 'Quận Thanh Xuân'],
                ['code' => '016', 'name' => 'Huyện Sóc Sơn'],
                ['code' => '017', 'name' => 'Huyện Đông Anh'],
                ['code' => '018', 'name' => 'Huyện Gia Lâm'],
                ['code' => '019', 'name' => 'Quận Nam Từ Liêm'],
                ['code' => '020', 'name' => 'Huyện Thanh Trì'],
                ['code' => '021', 'name' => 'Quận Bắc Từ Liêm']
            ],
            '79' => [ // TP HCM
                ['code' => '760', 'name' => 'Quận 1'],
                ['code' => '761', 'name' => 'Quận 12'],
                ['code' => '764', 'name' => 'Quận Gò Vấp'],
                ['code' => '765', 'name' => 'Quận Bình Thạnh'],
                ['code' => '766', 'name' => 'Quận Tân Bình'],
                ['code' => '767', 'name' => 'Quận Tân Phú'],
                ['code' => '768', 'name' => 'Quận Phú Nhuận'],
                ['code' => '769', 'name' => 'Thành phố Thủ Đức'],
                ['code' => '770', 'name' => 'Quận 3'],
                ['code' => '771', 'name' => 'Quận 10'],
                ['code' => '772', 'name' => 'Quận 11'],
                ['code' => '773', 'name' => 'Quận 4'],
                ['code' => '774', 'name' => 'Quận 5'],
                ['code' => '775', 'name' => 'Quận 6'],
                ['code' => '776', 'name' => 'Quận 8'],
                ['code' => '777', 'name' => 'Quận Bình Tân'],
                ['code' => '778', 'name' => 'Quận 7'],
                ['code' => '783', 'name' => 'Huyện Củ Chi'],
                ['code' => '784', 'name' => 'Huyện Hóc Môn'],
                ['code' => '785', 'name' => 'Huyện Bình Chánh'],
                ['code' => '786', 'name' => 'Huyện Nhà Bè'],
                ['code' => '787', 'name' => 'Huyện Cần Giờ']
            ],
            '48' => [ // Đà Nẵng
                ['code' => '490', 'name' => 'Quận Liên Chiểu'],
                ['code' => '491', 'name' => 'Quận Thanh Khê'],
                ['code' => '492', 'name' => 'Quận Hải Châu'],
                ['code' => '493', 'name' => 'Quận Sơn Trà'],
                ['code' => '494', 'name' => 'Quận Ngũ Hành Sơn'],
                ['code' => '495', 'name' => 'Quận Cẩm Lệ'],
                ['code' => '497', 'name' => 'Huyện Hòa Vang'],
                ['code' => '498', 'name' => 'Huyện Hoàng Sa']
            ]
        ];

        return $districtData[$provinceCode] ?? [];
    }

    /**
     * Get wards by district code
     */
    private function getWardsByDistrict($districtCode)
    {
        // Sample data - you can replace with database queries
        $wardData = [
            '001' => [ // Quận Ba Đình
                ['code' => '00001', 'name' => 'Phường Phúc Xá'],
                ['code' => '00004', 'name' => 'Phường Trúc Bạch'],
                ['code' => '00006', 'name' => 'Phường Vĩnh Phúc'],
                ['code' => '00007', 'name' => 'Phường Cống Vị'],
                ['code' => '00008', 'name' => 'Phường Liễu Giai'],
                ['code' => '00010', 'name' => 'Phường Nguyễn Trung Trực'],
                ['code' => '00013', 'name' => 'Phường Quán Thánh'],
                ['code' => '00016', 'name' => 'Phường Ngọc Hà'],
                ['code' => '00019', 'name' => 'Phường Điện Biên'],
                ['code' => '00022', 'name' => 'Phường Đội Cấn'],
                ['code' => '00025', 'name' => 'Phường Ngọc Khánh'],
                ['code' => '00028', 'name' => 'Phường Kim Mã'],
                ['code' => '00031', 'name' => 'Phường Giảng Võ'],
                ['code' => '00034', 'name' => 'Phường Thành Công']
            ],
            '002' => [ // Quận Hoàn Kiếm
                ['code' => '00037', 'name' => 'Phường Phúc Tấn'],
                ['code' => '00040', 'name' => 'Phường Đồng Xuân'],
                ['code' => '00043', 'name' => 'Phường Hàng Mã'],
                ['code' => '00046', 'name' => 'Phường Hàng Buồm'],
                ['code' => '00049', 'name' => 'Phường Hàng Đào'],
                ['code' => '00052', 'name' => 'Phường Hàng Bồ'],
                ['code' => '00055', 'name' => 'Phường Cửa Đông'],
                ['code' => '00058', 'name' => 'Phường Lý Thái Tổ'],
                ['code' => '00061', 'name' => 'Phường Hàng Bạc'],
                ['code' => '00064', 'name' => 'Phường Hàng Gai'],
                ['code' => '00067', 'name' => 'Phường Chương Dương Độ'],
                ['code' => '00070', 'name' => 'Phường Hàng Trống'],
                ['code' => '00073', 'name' => 'Phường Cửa Nam'],
                ['code' => '00076', 'name' => 'Phường Hàng Bông'],
                ['code' => '00079', 'name' => 'Phường Tràng Tiền'],
                ['code' => '00082', 'name' => 'Phường Trần Hưng Đạo'],
                ['code' => '00085', 'name' => 'Phường Phan Chu Trinh'],
                ['code' => '00088', 'name' => 'Phường Hàng Bài']
            ],
            '760' => [ // Quận 1 - TP HCM
                ['code' => '26734', 'name' => 'Phường Tân Định'],
                ['code' => '26737', 'name' => 'Phường Đa Kao'],
                ['code' => '26740', 'name' => 'Phường Bến Nghé'],
                ['code' => '26743', 'name' => 'Phường Bến Thành'],
                ['code' => '26746', 'name' => 'Phường Nguyễn Thái Bình'],
                ['code' => '26749', 'name' => 'Phường Phạm Ngũ Lão'],
                ['code' => '26752', 'name' => 'Phường Cầu Ông Lãnh'],
                ['code' => '26755', 'name' => 'Phường Cô Giang'],
                ['code' => '26758', 'name' => 'Phường Nguyễn Cư Trinh'],
                ['code' => '26761', 'name' => 'Phường Cầu Kho']
            ],
            '490' => [ // Quận Liên Chiểu - Đà Nẵng
                ['code' => '20194', 'name' => 'Phường Hòa Hiệp Bắc'],
                ['code' => '20197', 'name' => 'Phường Hòa Hiệp Nam'],
                ['code' => '20200', 'name' => 'Phường Hòa Khánh Bắc'],
                ['code' => '20203', 'name' => 'Phường Hòa Khánh Nam'],
                ['code' => '20206', 'name' => 'Phường Hòa Minh']
            ],
            '003' => [ // Quận Tây Hồ (bổ sung để dropdown không bị trống)
                ['code' => '00091', 'name' => 'Phường Phú Thượng'],
                ['code' => '00094', 'name' => 'Phường Nhật Tân'],
                ['code' => '00097', 'name' => 'Phường Tứ Liên'],
                ['code' => '00100', 'name' => 'Phường Quảng An'],
                ['code' => '00103', 'name' => 'Phường Xuân La'],
                ['code' => '00106', 'name' => 'Phường Yên Phụ'],
                ['code' => '00109', 'name' => 'Phường Bưởi'],
                ['code' => '00112', 'name' => 'Phường Thụy Khuê']
            ],
            '016' => [ // Huyện Sóc Sơn (bổ sung cho huyện được chọn trong ảnh)
                ['code' => '00478', 'name' => 'Thị trấn Sóc Sơn'],
                ['code' => '00481', 'name' => 'Xã Bắc Sơn'],
                ['code' => '00484', 'name' => 'Xã Minh Trí'],
                ['code' => '00487', 'name' => 'Xã Hồng Kỳ'],
                ['code' => '00490', 'name' => 'Xã Nam Sơn'],
                ['code' => '00493', 'name' => 'Xã Trung Giã'],
                ['code' => '00496', 'name' => 'Xã Tân Hưng'],
                ['code' => '00499', 'name' => 'Xã Minh Phú'],
                ['code' => '00502', 'name' => 'Xã Phù Linh'],
                ['code' => '00505', 'name' => 'Xã Bắc Phú'],
                ['code' => '00508', 'name' => 'Xã Tân Minh'],
                ['code' => '00511', 'name' => 'Xã Quang Tiến'],
                ['code' => '00514', 'name' => 'Xã Hiền Ninh'],
                ['code' => '00517', 'name' => 'Xã Tân Dân'],
                ['code' => '00520', 'name' => 'Xã Tiên Dược'],
                ['code' => '00523', 'name' => 'Xã Kim Lũ'],
                ['code' => '00526', 'name' => 'Xã Phú Cường'],
                ['code' => '00529', 'name' => 'Xã Phú Minh'],
                ['code' => '00532', 'name' => 'Xã Phù Lỗ'],
                ['code' => '00535', 'name' => 'Xã Xuân Giang'],
                ['code' => '00538', 'name' => 'Xã Mai Đình'],
                ['code' => '00541', 'name' => 'Xã Đức Hoà'],
                ['code' => '00544', 'name' => 'Xã Thanh Xuân'],
                ['code' => '00547', 'name' => 'Xã Đông Xuân'],
                ['code' => '00550', 'name' => 'Xã Kim Thư'],
                ['code' => '00553', 'name' => 'Xã Tân Quang']
            ]
        ];

        return $wardData[$districtCode] ?? [];
    }
}
