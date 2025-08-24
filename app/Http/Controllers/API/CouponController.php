<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function list(Request $request)
    {
        // Kiểm tra đăng nhập
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xem mã giảm giá',
                'require_login' => true
            ]);
        }

        $subtotal = (float)$request->query('subtotal', 0);
        $now = Carbon::now();
        $coupons = Coupon::where('status', true)
            ->where(function($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now->toDateString());
            })
            ->where(function($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now->toDateString());
            })
            ->get()
            ->map(function($c) use ($subtotal) {
                $eligible = true;
                $reason = null;
                if ($c->min_order_value && $subtotal < $c->min_order_value) { $eligible = false; $reason = 'Tối thiểu ' . number_format($c->min_order_value) . '₫'; }
                if ($eligible && $c->max_order_value && $subtotal > $c->max_order_value) { $eligible = false; $reason = 'Tối đa ' . number_format($c->max_order_value) . '₫'; }
                return [
                    'code' => $c->code,
                    'discount_type' => $c->discount_type,
                    'value' => $c->value,
                    'min_order_value' => $c->min_order_value,
                    'max_order_value' => $c->max_order_value,
                    'eligible' => $eligible,
                    'ineligible_reason' => $reason,
                ];
            });
        return response()->json(['success' => true, 'coupons' => $coupons]);
    }
    public function validateCoupon(Request $request)
    {
        try {
            $couponCode = $request->input('coupon_code');
            $subtotal = $request->input('subtotal', 0);
            $user = \Illuminate\Support\Facades\Auth::user();
            
            // Khách vãng lai không thể áp dụng coupon
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để nhận khuyến mãi',
                    'require_login' => true
                ]);
            }
            
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
            
            // Check max usage per user
            if ($coupon->max_usage_per_user > 0) {
                $user = \Illuminate\Support\Facades\Auth::user();
                $usedCount = 0;
                
                if ($user) {
                    // User đã đăng nhập - kiểm tra theo user_id
                    $usedCount = \App\Models\Order::where('user_id', $user->id)
                        ->where('coupon_code', $coupon->code)
                        ->whereNull('deleted_at')
                        ->count();
                } else {
                    // Khách vãng lai - kiểm tra theo email hoặc phone từ request
                    $guestEmail = $request->input('guest_email');
                    $guestPhone = $request->input('guest_phone');
                    
                    if ($guestEmail) {
                        $usedCount = \App\Models\Order::where('guest_email', $guestEmail)
                            ->where('coupon_code', $coupon->code)
                            ->whereNull('deleted_at')
                            ->count();
                    } elseif ($guestPhone) {
                        $usedCount = \App\Models\Order::where('guest_phone', $guestPhone)
                            ->where('coupon_code', $coupon->code)
                            ->whereNull('deleted_at')
                            ->count();
                    }
                }
                
                if ($usedCount >= $coupon->max_usage_per_user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bạn đã sử dụng hết số lần cho phép cho mã giảm giá này.'
                    ]);
                }
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
            return "Giảm {$coupon->value}% (tối đa " . number_format($discountAmount) . "₫)";
        } else {
            return "Giảm " . number_format($coupon->value) . "₫";
        }
    }
}
