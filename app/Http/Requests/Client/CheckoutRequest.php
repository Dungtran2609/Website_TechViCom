<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\Coupon;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'payment_method' => 'required|in:cod,bank_transfer',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'order_notes' => 'nullable|string|max:1000',
        ];

        // Nếu chọn địa chỉ đã lưu
        if ($this->has('selected_address') && $this->selected_address !== 'new') {
            $rules['selected_address'] = [
                'required',
                Rule::exists('user_addresses', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                })
            ];
        } else {
            // Validation cho địa chỉ mới
            $rules = array_merge($rules, [
                'recipient_name' => 'required|string|max:255|min:2',
                'recipient_phone' => [
                    'required',
                    'string',
                    'regex:/^(0|\+84)(3[2-9]|5[689]|7[06-9]|8[1-689]|9[0-46-9])[0-9]{7}$/'
                ],
                'recipient_email' => 'required|email|max:255',
                'recipient_address' => 'required|string|max:500|min:10',
                'province_code' => 'required|in:01',
                'district_code' => 'required|string|max:10',
                'ward_code' => 'required|string|max:10',
            ]);
        }

        // Validation cho coupon nếu có
        if ($this->has('coupon_code') && !empty($this->coupon_code)) {
            $rules['coupon_code'] = 'required|string|max:50|exists:coupons,code';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.',
            'shipping_method_id.required' => 'Vui lòng chọn phương thức vận chuyển.',
            'shipping_method_id.exists' => 'Phương thức vận chuyển không tồn tại.',
            
            'selected_address.required' => 'Vui lòng chọn địa chỉ giao hàng.',
            'selected_address.exists' => 'Địa chỉ không tồn tại hoặc không thuộc về bạn.',
            
            'recipient_name.required' => 'Vui lòng nhập họ và tên người nhận.',
            'recipient_name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'recipient_name.max' => 'Họ và tên không được quá 255 ký tự.',
            'recipient_name.min' => 'Họ và tên phải có ít nhất 2 ký tự.',
            
            'recipient_phone.required' => 'Vui lòng nhập số điện thoại.',
            'recipient_phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'recipient_phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam.',
            
            'recipient_email.required' => 'Vui lòng nhập email.',
            'recipient_email.email' => 'Email không hợp lệ.',
            'recipient_email.max' => 'Email không được quá 255 ký tự.',
            
            'recipient_address.required' => 'Vui lòng nhập địa chỉ giao hàng.',
            'recipient_address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'recipient_address.max' => 'Địa chỉ không được quá 500 ký tự.',
            'recipient_address.min' => 'Địa chỉ phải có ít nhất 10 ký tự.',
            
            'province_code.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'province_code.in' => 'Tỉnh/thành phố không hợp lệ.',
            
            'district_code.required' => 'Vui lòng chọn quận/huyện.',
            'district_code.string' => 'Quận/huyện không hợp lệ.',
            'district_code.max' => 'Mã quận/huyện không hợp lệ.',
            
            'ward_code.required' => 'Vui lòng chọn phường/xã.',
            'ward_code.string' => 'Phường/xã không hợp lệ.',
            'ward_code.max' => 'Mã phường/xã không hợp lệ.',
            
            'order_notes.string' => 'Ghi chú phải là chuỗi ký tự.',
            'order_notes.max' => 'Ghi chú không được quá 1000 ký tự.',
            
            'coupon_code.required' => 'Vui lòng nhập mã giảm giá.',
            'coupon_code.string' => 'Mã giảm giá không hợp lệ.',
            'coupon_code.max' => 'Mã giảm giá không được quá 50 ký tự.',
            'coupon_code.exists' => 'Mã giảm giá không tồn tại hoặc đã hết hạn.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'recipient_name' => 'họ và tên',
            'recipient_phone' => 'số điện thoại',
            'recipient_email' => 'email',
            'recipient_address' => 'địa chỉ',
            'province_code' => 'tỉnh/thành phố',
            'district_code' => 'quận/huyện',
            'ward_code' => 'phường/xã',
            'payment_method' => 'phương thức thanh toán',
            'shipping_method_id' => 'phương thức vận chuyển',
            'selected_address' => 'địa chỉ giao hàng',
            'order_notes' => 'ghi chú đơn hàng',
            'coupon_code' => 'mã giảm giá',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Kiểm tra thêm logic nghiệp vụ
            $this->validateBusinessLogic($validator);
        });
    }

    /**
     * Validate business logic
     */
    private function validateBusinessLogic($validator)
    {
        // Kiểm tra nếu user chưa đăng nhập
        if (!Auth::check()) {
            $validator->errors()->add('auth', 'Bạn cần đăng nhập để thực hiện thanh toán.');
            return;
        }

        // Kiểm tra có sản phẩm để thanh toán không
        $hasValidItems = false;
        
        // Kiểm tra giỏ hàng
        $cartItems = Cart::where('user_id', Auth::id())->get();
        if (!$cartItems->isEmpty()) {
            $hasValidItems = true;
            
            // Kiểm tra tồn kho cho giỏ hàng
            foreach ($cartItems as $item) {
                $product = $item->product;
                if (!$product) {
                    $validator->errors()->add('cart', 'Sản phẩm không tồn tại.');
                    return;
                }

                if ($product->stock < $item->quantity) {
                    $validator->errors()->add('cart', "Sản phẩm {$product->name} chỉ còn {$product->stock} sản phẩm trong kho.");
                    return;
                }
            }
        }
        
        // Kiểm tra buy now hoặc selected items
        $selectedParam = $this->input('selected');
        if (!empty($selectedParam)) {
            $hasValidItems = true;
            
            // Kiểm tra tồn kho cho selected items
            $selectedIds = array_filter(explode(',', $selectedParam));
            foreach ($selectedIds as $selectedId) {
                if (strpos($selectedId, ':') !== false) {
                    // Format: product_id:variant_id
                    list($productId, $variantId) = explode(':', $selectedId);
                    $product = Product::find($productId);
                    if (!$product) {
                        $validator->errors()->add('cart', 'Sản phẩm không tồn tại.');
                        return;
                    }
                    
                    // Kiểm tra tồn kho cho variant
                    if ($variantId) {
                        $variant = ProductVariant::find($variantId);
                        if (!$variant || $variant->stock < 1) {
                            $validator->errors()->add('cart', "Sản phẩm {$product->name} không còn trong kho.");
                            return;
                        }
                    } else {
                        if ($product->stock < 1) {
                            $validator->errors()->add('cart', "Sản phẩm {$product->name} chỉ còn {$product->stock} sản phẩm trong kho.");
                            return;
                        }
                    }
                } else {
                    // Format: cart_id
                    $cartItem = Cart::where('id', $selectedId)
                        ->where('user_id', Auth::id())
                        ->first();
                    if ($cartItem && $cartItem->product) {
                        if ($cartItem->product->stock < $cartItem->quantity) {
                            $validator->errors()->add('cart', "Sản phẩm {$cartItem->product->name} chỉ còn {$cartItem->product->stock} sản phẩm trong kho.");
                            return;
                        }
                    }
                }
            }
        }
        
        // Kiểm tra repayment order
        $orderId = $this->input('order_id') ?: session('repayment_order_id');
        if ($orderId) {
            $existingOrder = Order::where('user_id', Auth::id())
                ->where('id', $orderId)
                ->where('status', 'pending')
                ->whereIn('payment_status', ['pending', 'processing', 'failed'])
                ->first();
                
            if ($existingOrder && $existingOrder->orderItems->count() > 0) {
                $hasValidItems = true;
                
                // Kiểm tra tồn kho cho order items
                foreach ($existingOrder->orderItems as $item) {
                    $product = Product::find($item->product_id);
                    if (!$product) {
                        $validator->errors()->add('cart', 'Sản phẩm không tồn tại.');
                        return;
                    }
                    
                    if ($product->stock < $item->quantity) {
                        $validator->errors()->add('cart', "Sản phẩm {$product->name} chỉ còn {$product->stock} sản phẩm trong kho.");
                        return;
                    }
                }
            }
        }
        
        if (!$hasValidItems) {
            $validator->errors()->add('cart', 'Không có sản phẩm nào để thanh toán.');
            return;
        }

        // Kiểm tra coupon nếu có
        if ($this->has('coupon_code') && !empty($this->coupon_code)) {
            $coupon = Coupon::where('code', $this->coupon_code)
                ->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if (!$coupon) {
                $validator->errors()->add('coupon_code', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
                return;
            }

            // Kiểm tra số lần sử dụng coupon
            $usedCount = Order::where('user_id', Auth::id())
                ->where('coupon_code', $this->coupon_code)
                ->count();

            if ($usedCount >= $coupon->usage_limit_per_user) {
                $validator->errors()->add('coupon_code', 'Bạn đã sử dụng mã giảm giá này tối đa số lần cho phép.');
                return;
            }
        }
    }
}
