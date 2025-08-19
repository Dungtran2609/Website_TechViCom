<?php

namespace App\Http\Controllers\Client\Coupon;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ClientCouponController extends Controller
{
    public function listAvailableCoupons(Request $request)
    {
        $subtotal = $request->input('subtotal', 0);
        $now = \Carbon\Carbon::now();
        $user = \Illuminate\Support\Facades\Auth::user();
        $usedCodes = [];
        if ($user) {
            $usedCodes = \App\Models\Order::where('user_id', $user->id)
                ->whereNotNull('coupon_code')
                ->pluck('coupon_code')
                ->unique()
                ->toArray();
        }
        if (empty($usedCodes)) {
            return response()->json(['success' => true, 'coupons' => []]);
        }
        $coupons = \App\Models\Coupon::where('status', true)
            ->whereNull('deleted_at')
            ->whereIn('code', $usedCodes)
            ->where(function($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->get();
        // Sắp xếp theo thứ tự usedCodes (mã đã dùng lên đầu)
        $coupons = $coupons->sortBy(function($c) use ($usedCodes) {
            return array_search($c->code, $usedCodes);
        })->values();
        $result = [];
        foreach ($coupons as $coupon) {
            $eligible = true;
            $reason = '';
            if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
                $eligible = false;
                $reason = 'Chưa đạt giá trị tối thiểu ' . number_format($coupon->min_order_value) . '₫';
            }
            if ($coupon->max_order_value && $subtotal > $coupon->max_order_value) {
                $eligible = false;
                $reason = 'Vượt quá giá trị tối đa ' . number_format($coupon->max_order_value) . '₫';
            }
            $discountAmount = 0;
            if ($coupon->discount_type === 'percent') {
                $discountAmount = $subtotal * ($coupon->value / 100);
                if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                    $discountAmount = $coupon->max_discount_amount;
                }
            } else {
                $discountAmount = $coupon->value;
            }
            $discountAmount = min($discountAmount, $subtotal);
            $result[] = [
                'code' => $coupon->code,
                'discount_type' => $coupon->discount_type,
                'value' => $coupon->value,
                'max_discount_amount' => $coupon->max_discount_amount,
                'min_order_value' => $coupon->min_order_value,
                'max_order_value' => $coupon->max_order_value,
                'eligible' => $eligible,
                'reason' => $reason,
                'discount_amount' => $discountAmount,
                'message' => $eligible ? $this->getDiscountMessage($coupon, $discountAmount) : $reason
            ];
        }
        return response()->json(['success' => true, 'coupons' => $result]);
    }
    public function validateCoupon(Request $request)
    {
        try {
            $couponCode = $request->input('coupon_code');
            $subtotal = $request->input('subtotal', 0);
            $user = Auth::user();
            
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
            
            // Check max usage per user
            if ($user && $coupon->max_usage_per_user > 0) {
                $usedCount = \App\Models\Order::where('user_id', $user->id)
                    ->where('coupon_code', $coupon->code)
                    ->whereNull('deleted_at')
                    ->count();
                if ($usedCount >= $coupon->max_usage_per_user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bạn đã sử dụng hết số lần cho phép cho mã giảm giá này.'
                    ]);
                }
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
                    'min_order_value' => $coupon->min_order_value,
                    'max_order_value' => $coupon->max_order_value,
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
