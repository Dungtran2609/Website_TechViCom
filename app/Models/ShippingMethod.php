<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    // Chỉ định tên bảng trong cơ sở dữ liệu (nếu không sử dụng tên mặc định 'shipping_methods')
    protected $table = 'shipping_methods';

    // Các thuộc tính có thể gán đại trà (mass assignable)
    protected $fillable = [
        'name',
        'description',
        'estimated_days',
        'max_weight',
        'regions',
    ];

    // Để xử lý trường 'regions' dưới dạng JSON nếu cần
    protected $casts = [
        'regions' => 'array', // Laravel sẽ tự động chuyển đổi vùng thành một mảng khi lấy từ CSDL
    ];

    // Các trường không cần ghi vào (tự động gán) khi tạo hoặc cập nhật
    protected $guarded = [];

    // Nếu bạn có nhu cầu thêm các phương thức quan hệ hoặc logic bổ sung, bạn có thể thêm chúng ở đây
}
