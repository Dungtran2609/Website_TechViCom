<?php

namespace App\Http\Controllers\Client\Coupon;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ClientCouponController extends Controller
{
    public function listAvailableCoupons(Request $request)
    {
        $subtotal = $request->input('subtotal', 0);
        $now = \Carbon\Carbon::now();
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Khách vãng lai không thể xem danh sách coupon
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xem danh sách khuyến mãi',
                'require_login' => true
            ]);
        }
        
        // Lấy tất cả coupon có sẵn (không chỉ những đã dùng)
        $coupons = \App\Models\Coupon::where('status', true)
            ->whereNull('deleted_at')
            ->where(function($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->get();
        $result = [];
        foreach ($coupons as $coupon) {
            $eligible = true;
            $reason = '';
            
            // Kiểm tra giá trị đơn hàng
            if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
                $eligible = false;
                $reason = 'Chưa đạt giá trị tối thiểu ' . number_format($coupon->min_order_value) . '₫';
            }
            if ($eligible && $coupon->max_order_value && $subtotal > $coupon->max_order_value) {
                $eligible = false;
                $reason = 'Vượt quá giá trị tối đa ' . number_format($coupon->max_order_value) . '₫';
            }
            
            // Kiểm tra số lần sử dụng per user
            if ($eligible && $coupon->max_usage_per_user > 0) {
                $usedCount = 0;
                
                if ($user) {
                    // User đã đăng nhập - kiểm tra theo user_id
                    $usedCount = \App\Models\Order::where('user_id', $user->id)
                        ->where('coupon_code', $coupon->code)
                        ->whereNull('deleted_at')
                        ->count();
                } else {
                    // Khách vãng lai - kiểm tra theo email hoặc phone từ request
                    $guestEmail = request()->input('guest_email');
                    $guestPhone = request()->input('guest_phone');
                    
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
                    $eligible = false;
                    $reason = 'Bạn đã sử dụng hết số lần cho phép';
                }
            }
            
            // Tính discount amount
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
            
            // Khách vãng lai không thể áp dụng coupon
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để nhận khuyến mãi',
                    'require_login' => true
                ]);
            }
            
            // Debug logging
            Log::info('Coupon validation request', [
                'coupon_code' => $couponCode,
                'subtotal' => $subtotal,
                'user_id' => $user ? $user->id : null,
                'cart_product_ids' => $request->input('cart_product_ids', []),
                'cart_product_amounts' => $request->input('cart_product_amounts', []),
                'cart_category_ids' => $request->input('cart_category_ids', []),
                'request_data' => $request->all()
            ]);
            
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
            if ($coupon->max_usage_per_user > 0) {
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
            
            // Kiểm tra điều kiện apply_type
            $cartProductIds = $request->input('cart_product_ids', []); // truyền lên từ client
            $cartProductAmounts = $request->input('cart_product_amounts', []); // [{id:..., amount:...}]
            $eligibleSubtotal = null;
            if ($coupon->apply_type === 'product') {
                $couponProductIds = $coupon->products()->pluck('products.id')->toArray();
                if (empty($couponProductIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã giảm giá này hiện không áp dụng cho sản phẩm nào.'
                    ]);
                }
                // Tính tổng giá trị các sản phẩm hợp lệ trong giỏ
                $eligibleSubtotal = 0;
                foreach ($cartProductAmounts as $item) {
                    if (in_array($item['id'], $couponProductIds)) {
                        $eligibleSubtotal += (float)$item['amount'];
                    }
                }
                if ($eligibleSubtotal <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã giảm giá này chỉ áp dụng cho một số sản phẩm nhất định.'
                    ]);
                }
            } else if ($coupon->apply_type === 'category') {
                $couponCategoryIds = $coupon->categories()->pluck('categories.id')->toArray();
                if (empty($couponCategoryIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã giảm giá này hiện không áp dụng cho danh mục nào.'
                    ]);
                }
                $cartCategoryIds = $request->input('cart_category_ids', []); // truyền lên từ client
                $valid = false;
                foreach ($cartCategoryIds as $catId) {
                    if (in_array($catId, $couponCategoryIds)) {
                        $valid = true;
                        break;
                    }
                }
                if (!$valid) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã giảm giá này chỉ áp dụng cho một số danh mục sản phẩm nhất định.'
                    ]);
                }
            } else if ($coupon->apply_type === 'user') {
                $allowedUserIds = $coupon->users()->pluck('users.id')->toArray();
                if (empty($allowedUserIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã giảm giá này không áp dụng cho tài khoản nào.'
                    ]);
                }
                if (!$user || !in_array($user->id, $allowedUserIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tài khoản của bạn không được phép sử dụng mã giảm giá này.'
                    ]);
                }
            }

            // Calculate discount amount
            if ($coupon->apply_type === 'product') {
                $baseAmount = $eligibleSubtotal;
            } else {
                $baseAmount = $subtotal;
            }
            $discountAmount = 0;
            if ($coupon->discount_type === 'percent') {
                $discountAmount = $baseAmount * ($coupon->value / 100);
                if ($coupon->max_discount_amount && $discountAmount > $coupon->max_discount_amount) {
                    $discountAmount = $coupon->max_discount_amount;
                }
            } else {
                $discountAmount = min($coupon->value, $baseAmount);
            }
            $discountAmount = min($discountAmount, $baseAmount);
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
