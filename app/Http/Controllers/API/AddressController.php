<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function getProvinces()
    {
        $provinces = [
            ['code' => 'hanoi', 'name' => 'Hà Nội'],
            ['code' => 'hcm', 'name' => 'TP. Hồ Chí Minh'],
            ['code' => 'danang', 'name' => 'Đà Nẵng'],
            ['code' => 'cantho', 'name' => 'Cần Thơ'],
            ['code' => 'haiphong', 'name' => 'Hải Phòng'],
        ];
        
        return response()->json($provinces);
    }
    
    public function getDistricts($provinceCode)
    {
        $districts = [];
        
        switch ($provinceCode) {
            case 'hanoi':
                $districts = [
                    ['code' => 'ba-dinh', 'name' => 'Ba Đình'],
                    ['code' => 'hoan-kiem', 'name' => 'Hoàn Kiếm'],
                    ['code' => 'tay-ho', 'name' => 'Tây Hồ'],
                    ['code' => 'long-bien', 'name' => 'Long Biên'],
                    ['code' => 'cau-giay', 'name' => 'Cầu Giấy'],
                    ['code' => 'dong-da', 'name' => 'Đống Đa'],
                    ['code' => 'hai-ba-trung', 'name' => 'Hai Bà Trưng'],
                    ['code' => 'hoang-mai', 'name' => 'Hoàng Mai'],
                    ['code' => 'thanh-xuan', 'name' => 'Thanh Xuân'],
                ];
                break;
            case 'hcm':
                $districts = [
                    ['code' => 'quan-1', 'name' => 'Quận 1'],
                    ['code' => 'quan-3', 'name' => 'Quận 3'],
                    ['code' => 'quan-5', 'name' => 'Quận 5'],
                    ['code' => 'quan-7', 'name' => 'Quận 7'],
                    ['code' => 'quan-10', 'name' => 'Quận 10'],
                    ['code' => 'binh-thanh', 'name' => 'Bình Thạnh'],
                    ['code' => 'tan-binh', 'name' => 'Tân Bình'],
                    ['code' => 'phu-nhuan', 'name' => 'Phú Nhuận'],
                ];
                break;
            case 'danang':
                $districts = [
                    ['code' => 'hai-chau', 'name' => 'Hải Châu'],
                    ['code' => 'thanh-khe', 'name' => 'Thanh Khê'],
                    ['code' => 'son-tra', 'name' => 'Sơn Trà'],
                    ['code' => 'ngu-hanh-son', 'name' => 'Ngũ Hành Sơn'],
                    ['code' => 'lien-chieu', 'name' => 'Liên Chiểu'],
                    ['code' => 'cam-le', 'name' => 'Cẩm Lệ'],
                ];
                break;
            default:
                $districts = [
                    ['code' => 'district-1', 'name' => 'Quận/Huyện 1'],
                    ['code' => 'district-2', 'name' => 'Quận/Huyện 2'],
                    ['code' => 'district-3', 'name' => 'Quận/Huyện 3'],
                ];
        }
        
        return response()->json($districts);
    }
    
    public function getWards($districtCode)
    {
        $wards = [
            ['code' => 'phuc-xa', 'name' => 'Phúc Xá'],
            ['code' => 'truc-bach', 'name' => 'Trúc Bạch'],
            ['code' => 'vinh-phuc', 'name' => 'Vĩnh Phúc'],
            ['code' => 'dien-bien', 'name' => 'Điện Biên'],
            ['code' => 'doi-can', 'name' => 'Đội Cấn'],
            ['code' => 'hang-bai', 'name' => 'Hàng Bài'],
            ['code' => 'hang-trong', 'name' => 'Hàng Trống'],
            ['code' => 'phuc-tan', 'name' => 'Phúc Tán'],
            ['code' => 'chuong-duong', 'name' => 'Chương Dương'],
            ['code' => 'dich-vong', 'name' => 'Dịch Vọng'],
            ['code' => 'nghia-tan', 'name' => 'Nghĩa Tân'],
            ['code' => 'quan-hoa', 'name' => 'Quan Hoa'],
            ['code' => 'yen-hoa', 'name' => 'Yên Hòa'],
            ['code' => 'o-cho-dua', 'name' => 'Ô Chợ Dừa'],
            ['code' => 'lang-ha', 'name' => 'Láng Hạ'],
            ['code' => 'kim-lien', 'name' => 'Kim Liên'],
            ['code' => 'tho-quan', 'name' => 'Thổ Quan'],
            ['code' => 'tan-mai', 'name' => 'Tân Mai'],
            ['code' => 'yen-so', 'name' => 'Yên Sở'],
            ['code' => 'hoang-van-thu', 'name' => 'Hoàng Văn Thụ'],
            ['code' => 'giap-bat', 'name' => 'Giáp Bát'],
        ];
        
        return response()->json($wards);
    }
}
