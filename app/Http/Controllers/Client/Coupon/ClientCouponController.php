<?php

namespace App\Http\Controllers\Client\Coupon;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClientCouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        try {
            $couponCode = $request->input('coupon_code');
            $subtotal = $request->input('subtotal', 0);
            
            // Find coupon in database
            $coupon = Coupon::where('code', $couponCode)
                            ->where('status', true)
                            ->whereNull('deleted_at')
                            ->first();
            
            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không tồn tại hoặc đã bị vô hiệu hóa'
                ]);
            }
            
            // Check if coupon is within valid date range
            $now = Carbon::now();
            if ($coupon->start_date && $now->lt(Carbon::parse($coupon->start_date))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá chưa có hiệu lực'
                ]);
            }
            
            if ($coupon->end_date && $now->gt(Carbon::parse($coupon->end_date))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá đã hết hạn'
                ]);
            }
            
            // Check minimum order value
            if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($coupon->min_order_value) . '₫'
                ]);
            }
            
            // Check maximum order value
            if ($coupon->max_order_value && $subtotal > $coupon->max_order_value) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng vượt quá giá trị tối đa ' . number_format($coupon->max_order_value) . '₫'
                ]);
            }
            
            // Calculate discount amount
            $discountAmount = 0;
            if ($coupon->discount_type === 'percent') {
                $discountAmount = $subtotal * ($coupon->value / 100);
                
                // Apply max discount limit for percentage type
                if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                    $discountAmount = $coupon->max_discount_amount;
                }
            } else {
                // Fixed amount discount
                $discountAmount = $coupon->value;
            }
            
            // Make sure discount doesn't exceed subtotal
            $discountAmount = min($discountAmount, $subtotal);
            
            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá hợp lệ',
                'discount_amount' => $discountAmount,
                'coupon' => [
                    'code' => $coupon->code,
                    'discount_type' => $coupon->discount_type,
                    'value' => $coupon->value,
                    'max_discount_amount' => $coupon->max_discount_amount,
                    'message' => $this->getDiscountMessage($coupon, $discountAmount)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi kiểm tra mã giảm giá'
            ]);
        }
    }
    
    private function getDiscountMessage($coupon, $discountAmount)
    {
        if ($coupon->discount_type === 'percent') {
            // Hiển thị rõ % và số tiền được giảm thực tế
            $formattedDiscount = number_format($discountAmount) . '₫';
            if ($coupon->max_discount_amount) {
                $maxFormatted = number_format($coupon->max_discount_amount) . '₫';
                return "Giảm {$coupon->value}% - Tiết kiệm {$formattedDiscount} (tối đa {$maxFormatted})";
            }
            return "Giảm {$coupon->value}% - Tiết kiệm {$formattedDiscount}";
        } else {
            return "Giảm " . number_format($coupon->value) . "₫";
        }
    }
}
