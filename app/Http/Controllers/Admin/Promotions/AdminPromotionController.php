<?php
namespace App\Http\Controllers\Admin\Promotions;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Promotions\PromotionRequest;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminPromotionController extends Controller
{
    public function index()
    {
        // Tự động cập nhật trạng thái trước khi hiển thị
        $this->updatePromotionStatuses();
        
        $query = Promotion::with(['coupons']);
        
        // Filter by keyword
        if (request('q')) {
            $q = request('q');
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%")
                    ->orWhere('id', $q);
            });
        }

        // Filter by flash type
        if (request('flash_type')) {
            $query->where('flash_type', request('flash_type'));
        }

        // Filter by status
        if (request('status') !== null && request('status') !== '') {
            $query->where('status', request('status'));
        }

        // Filter by date from
        if (request('date_from')) {
            $query->where('start_date', '>=', request('date_from'));
        }

        $promotions = $query->latest()->paginate(15)->appends(request()->query());
        
        // Thêm thông tin trạng thái thời gian cho mỗi promotion
        $now = \Carbon\Carbon::now(config('app.timezone'));
        $promotions->getCollection()->transform(function ($promotion) use ($now) {
            $start = $promotion->start_date ? \Carbon\Carbon::parse($promotion->start_date)->timezone(config('app.timezone')) : null;
            $end = $promotion->end_date ? \Carbon\Carbon::parse($promotion->end_date)->timezone(config('app.timezone')) : null;
            if ($start && $end) {
                if ($now->betweenIncluded($start, $end)) {
                    $promotion->time_status = 'active'; // Đang diễn ra
                } elseif ($now->lessThan($start)) {
                    $promotion->time_status = 'upcoming'; // Sắp diễn ra
                } elseif ($now->greaterThan($end)) {
                    $promotion->time_status = 'expired'; // Đã kết thúc
                } else {
                    $promotion->time_status = 'unknown';
                }
            } else {
                $promotion->time_status = 'unknown';
            }
            return $promotion;
        });
        
        return view('admin.promotions.index', compact('promotions'));
    }
    
    /**
     * Tự động cập nhật trạng thái chương trình khuyến mãi
     */
    private function updatePromotionStatuses()
    {
        $now = now();
        
        // Ẩn tất cả chương trình đã kết thúc
        $expiredPromotions = Promotion::where('status', 1)
            ->where('end_date', '<', $now)
            ->get();
        foreach ($expiredPromotions as $promotion) {
            $promotion->status = 0;
            $promotion->save();
        }
        // Không tự động tắt các promotion đang kích hoạt khác, không tự động kích hoạt promotion mới ở đây
    }

    public function create()
    {
    $categories = Category::all();
    $products = Product::all();
    $coupons = \App\Models\Coupon::whereNull('promotion_id')->orWhere('promotion_id', 0)->get();
    return view('admin.promotions.create', compact('categories', 'products', 'coupons'));
    }

    public function store(PromotionRequest $request)
    {
        // Thêm validation logic tùy chỉnh
        $validationErrors = $this->validatePromotionData($request);
        if (!empty($validationErrors)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validationErrors
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validationErrors)
                ->withInput();
        }

        $data = $request->validated();
        
        // Kiểm tra nếu muốn kích hoạt chương trình mới
        if ($data['status'] == 1) {
            // Tìm chương trình đang kích hoạt
            $activePromotion = Promotion::where('status', 1)->first();
            if ($activePromotion) {
                // Tự động ẩn chương trình cũ
                $activePromotion->status = 0;
                $activePromotion->save();
                
                // Revert giá sản phẩm của chương trình cũ
                $this->revertPromotionPrices($activePromotion);
            }
        }
        
        // Tạo slug duy nhất (kiểm tra cả soft deleted records)
        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $i = 1;
        while (\Illuminate\Support\Facades\DB::table('promotions')->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i;
            $i++;
            // Tránh vòng lặp vô hạn
            if ($i > 100) {
                $slug = $baseSlug . '-' . time();
                break;
            }
        }
        $data['slug'] = $slug;
        // Nếu là kiểu category thì lưu discount_value và cập nhật sale_price cho các sản phẩm thuộc danh mục
        if ($data['flash_type'] === 'category') {
            $data['discount_type'] = 'percent';
            $data['discount_value'] = $request->category_discount_value ?? 10;
        }
        $promotion = Promotion::create($data);
        // Gán sản phẩm/danh mục nếu có
        if ($data['flash_type'] === 'category' && !empty($request->categories)) {
            $promotion->categories()->sync($request->categories);
            // Tự động cập nhật sale_price cho các sản phẩm thuộc danh mục
            $categoryIds = $request->categories;
            $productIds = \App\Models\Product::whereIn('category_id', $categoryIds)->pluck('id')->toArray();
            $discountPercent = $data['discount_value'];
            foreach ($productIds as $pid) {
                $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                foreach ($variants as $variant) {
                    if (is_null($variant->old_sale_price)) {
                        $variant->old_sale_price = $variant->sale_price;
                    }
                    $variant->sale_price = $this->calculateSalePrice($variant->price, $discountPercent);
                    $variant->save();
                }
            }
        }
        if ($data['flash_type'] === 'flash_sale' && !empty($request->products) && $data['status'] == 1) {
            $syncData = [];
            foreach ($request->products as $pid) {
                $syncData[$pid] = [
                    'sale_price' => isset($request->sale_prices[$pid]) ? $request->sale_prices[$pid] : null,
                    'discount_percent' => isset($request->discount_percents[$pid]) ? $request->discount_percents[$pid] : null
                ];
                
                // Lưu giá giảm cũ trước khi cập nhật giá flash sale
                \App\Models\ProductVariant::where('product_id', $pid)
                    ->whereNull('old_sale_price')
                    ->update(['old_sale_price' => DB::raw('sale_price')]);
                
                // Cập nhật sale_price cho tất cả variant của sản phẩm này
                if (isset($request->sale_prices[$pid]) && $request->sale_prices[$pid] > 0) {
                    // Nếu có giá cố định
                    \App\Models\ProductVariant::where('product_id', $pid)->update(['sale_price' => $request->sale_prices[$pid]]);
                } elseif (isset($request->discount_percents[$pid]) && $request->discount_percents[$pid] > 0) {
                    // Nếu có phần trăm giảm giá
                    $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                    foreach ($variants as $variant) {
                        $discountedPrice = $this->calculateSalePrice($variant->price, $request->discount_percents[$pid]);
                        $variant->sale_price = $discountedPrice;
                        $variant->save();
                    }
                }
            }
            $promotion->products()->sync($syncData);
        } else {
            // Nếu không phải flash_sale hoặc không kích hoạt thì không cập nhật sale_price, chỉ sync products
            $promotion->products()->sync($request->products ?? []);
        }
        // Gán coupon cho promotion
        if ($request->coupons) {
            \App\Models\Coupon::whereIn('id', $request->coupons)->update(['promotion_id' => $promotion->id]);
        }
        
        // Trả về JSON nếu request là AJAX
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tạo chương trình thành công!']);
        }
        
        return redirect()->route('admin.promotions.index')->with('success', 'Tạo chương trình thành công!');
    }

    public function edit($id)
    {
        $promotion = Promotion::with(['categories', 'products', 'coupons'])->findOrFail($id);
        $categories = Category::all();
        // Sắp xếp sản phẩm: sản phẩm đã chọn lên đầu
        $allProducts = Product::all();
        $selectedProductIds = $promotion->products->pluck('id')->toArray();
        $selectedProducts = $allProducts->whereIn('id', $selectedProductIds);
        $unselectedProducts = $allProducts->whereNotIn('id', $selectedProductIds);
        $products = $selectedProducts->merge($unselectedProducts);
        $coupons = \App\Models\Coupon::where(function($q) use ($promotion) {
            $q->whereNull('promotion_id')
                ->orWhere('promotion_id', 0)
                ->orWhere('promotion_id', $promotion->id);
        })->get();
        // Đảm bảo biến $selectedType luôn có giá trị
        $selectedType = old('flash_type', $promotion->flash_type);
        return view('admin.promotions.edit', compact('promotion', 'categories', 'products', 'coupons', 'selectedType'));
    }

        public function update(PromotionRequest $request, $id)
    {
        $promotion = Promotion::findOrFail($id);
        
        // Tự động cập nhật ngày bắt đầu nếu đã qua thời gian thực
        $startDate = $request->input('start_date');
        $currentTime = now();
        $autoUpdated = false;
        
        if ($startDate && $startDate < $currentTime) {
            // Nếu ngày bắt đầu đã qua, tự động lấy thời gian hiện tại
            $request->merge(['start_date' => $currentTime->format('Y-m-d H:i:s')]);
            $autoUpdated = true;
        }
        
        // Thêm validation logic tùy chỉnh
        $validationErrors = $this->validatePromotionData($request);
        if (!empty($validationErrors)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validationErrors
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validationErrors)
                ->withInput();
        }
        
        $data = $request->validated();
        
        // Kiểm tra nếu muốn kích hoạt chương trình mới
        if ($data['status'] == 1 && $promotion->status == 0) {
            // Tìm chương trình đang kích hoạt (khác chương trình hiện tại)
            $activePromotion = Promotion::where('status', 1)->where('id', '!=', $id)->first();
            if ($activePromotion) {
                // Tự động ẩn chương trình cũ
                $activePromotion->status = 0;
                $activePromotion->save();
                
                // Revert giá sản phẩm của chương trình cũ
                $this->revertPromotionPrices($activePromotion);
            }
        }
        
        // Tạo slug duy nhất cho update (kiểm tra cả soft deleted records)
        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $i = 1;
        while (\Illuminate\Support\Facades\DB::table('promotions')->where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $baseSlug . '-' . $i;
            $i++;
            // Tránh vòng lặp vô hạn
            if ($i > 100) {
                $slug = $baseSlug . '-' . time();
                break;
            }
        }
        $data['slug'] = $slug;
        if ($data['flash_type'] === 'category') {
            $data['discount_type'] = 'percent';
            $data['discount_value'] = $request->category_discount_value ?? 10;
            // Cập nhật trực tiếp discount_value nếu đã có promotion
            $promotion->discount_type = 'percent';
            $promotion->discount_value = $data['discount_value'];
            $promotion->save();
            // Cập nhật các trường khác
            unset($data['discount_type'], $data['discount_value']);
            $promotion->update($data);
            // Cập nhật sale_price cho các sản phẩm thuộc danh mục
            $categoryIds = $request->categories ?? [];
            $productIds = \App\Models\Product::whereIn('category_id', $categoryIds)->pluck('id')->toArray();
            $discountPercent = $promotion->discount_value;
            foreach ($productIds as $pid) {
                $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                foreach ($variants as $variant) {
                    if (is_null($variant->old_sale_price)) {
                        $variant->old_sale_price = $variant->sale_price;
                    }
                    $variant->sale_price = $this->calculateSalePrice($variant->price, $discountPercent);
                    $variant->save();
                }
            }
        } else {
            $promotion->update($data);
        }
        if ($data['flash_type'] === 'category' && !empty($request->categories)) {
            $promotion->categories()->sync($request->categories);
        } else {
            // Nếu không còn là category hoặc bị ẩn/hết hạn thì revert sale_price về old_sale_price cho các sản phẩm từng thuộc promotion này
            $oldCategoryIds = $promotion->categories()->pluck('categories.id')->toArray();
            $oldProductIds = \App\Models\Product::whereIn('category_id', $oldCategoryIds)->pluck('id')->toArray();
            foreach ($oldProductIds as $pid) {
                $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                foreach ($variants as $variant) {
                    if (!is_null($variant->old_sale_price)) {
                        $variant->sale_price = $variant->old_sale_price;
                        $variant->old_sale_price = null;
                        $variant->save();
                    }
                }
            }
            // Chỉ detach khi thay đổi kiểu chương trình, không detach khi chỉ cập nhật trạng thái
            if ($promotion->flash_type !== $data['flash_type']) {
                $promotion->categories()->detach();
            }
        }
        if ($data['flash_type'] === 'flash_sale' && !empty($request->products) && $data['status'] == 1) {
            $syncData = [];
            foreach ($request->products as $pid) {
                $syncData[$pid] = [
                    'sale_price' => isset($request->sale_prices[$pid]) ? $request->sale_prices[$pid] : null,
                    'discount_percent' => isset($request->discount_percents[$pid]) ? $request->discount_percents[$pid] : null
                ];
                
                // Lưu giá giảm cũ trước khi cập nhật giá flash sale
                \App\Models\ProductVariant::where('product_id', $pid)
                    ->whereNull('old_sale_price')
                    ->update(['old_sale_price' => DB::raw('sale_price')]);
                
                // Cập nhật sale_price cho tất cả variant của sản phẩm này
                if (isset($request->sale_prices[$pid]) && $request->sale_prices[$pid] > 0) {
                    // Nếu có giá cố định
                    \App\Models\ProductVariant::where('product_id', $pid)->update(['sale_price' => $request->sale_prices[$pid]]);
                } elseif (isset($request->discount_percents[$pid]) && $request->discount_percents[$pid] > 0) {
                    // Nếu có phần trăm giảm giá
                    $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                    foreach ($variants as $variant) {
                        $discountedPrice = $this->calculateSalePrice($variant->price, $request->discount_percents[$pid]);
                        $variant->sale_price = $discountedPrice;
                        $variant->save();
                    }
                }
            }
            $promotion->products()->sync($syncData);
        } else {
            // Nếu không còn là flash_sale hoặc bị ẩn/hết hạn thì revert sale_price về old_sale_price cho các sản phẩm từng thuộc promotion này
            $oldProductIds = $promotion->products()->pluck('products.id')->toArray();
            \App\Models\ProductVariant::whereIn('product_id', $oldProductIds)
                ->whereNotNull('old_sale_price')
                ->update(['sale_price' => DB::raw('old_sale_price'), 'old_sale_price' => null]);
            // Chỉ detach khi thay đổi kiểu chương trình, không detach khi chỉ cập nhật trạng thái
            if ($promotion->flash_type !== $data['flash_type']) {
                $promotion->products()->detach();
            }
        }
        // Gán coupon cho promotion
        // Xóa liên kết cũ
        \App\Models\Coupon::where('promotion_id', $promotion->id)->update(['promotion_id' => null]);
        // Gán lại các coupon mới
        if ($request->coupons) {
            \App\Models\Coupon::whereIn('id', $request->coupons)->update(['promotion_id' => $promotion->id]);
        }
        
        // Trả về JSON nếu request là AJAX
        if ($request->ajax()) {
            $message = 'Cập nhật chương trình thành công!';
            if ($autoUpdated) {
                $message .= ' Ngày bắt đầu đã được tự động cập nhật thành thời gian hiện tại.';
            }
            return response()->json(['success' => true, 'message' => $message]);
        }
        
        $message = 'Cập nhật chương trình thành công!';
        if ($autoUpdated) {
            $message .= ' Ngày bắt đầu đã được tự động cập nhật thành thời gian hiện tại.';
        }
        return redirect()->route('admin.promotions.index')->with('success', $message);
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Đã xóa chương trình!');
    }

    /**
     * Tính toán sale_price với validation giới hạn
     */
    private function calculateSalePrice($originalPrice, $discountPercent)
    {
        $discountedPrice = round($originalPrice * (1 - $discountPercent / 100));
        
        // Kiểm tra giới hạn của bigInteger (9223372036854775807)
        if ($discountedPrice > 9223372036854775807) {
            $discountedPrice = 9223372036854775807;
        }
        if ($discountedPrice < 0) {
            $discountedPrice = 0;
        }
        
        // Kiểm tra giá khuyến mãi phải nhỏ hơn giá gốc
        if ($discountedPrice >= $originalPrice) {
            $discountedPrice = $originalPrice - 1; // Giảm ít nhất 1 đồng
        }
        
        return $discountedPrice;
    }

    /**
     * Validate và xử lý sale price cố định
     */
    private function validateAndSetSalePrice($productId, $salePrice)
    {
        // Kiểm tra giới hạn 7 số
        if ($salePrice > 9999999) {
            $salePrice = 9999999;
        }
        
        // Kiểm tra giá khuyến mãi phải nhỏ hơn giá gốc
        $variants = \App\Models\ProductVariant::where('product_id', $productId)->get();
        foreach ($variants as $variant) {
            $finalSalePrice = $salePrice;
            if ($finalSalePrice >= $variant->price) {
                $finalSalePrice = $variant->price - 1; // Giảm ít nhất 1 đồng
            }
            $variant->sale_price = $finalSalePrice;
            $variant->save();
        }
    }

    /**
     * Validation logic tùy chỉnh cho promotion
     */
    private function validatePromotionData($request)
    {
        $errors = [];
        
        // Validation cho tên chương trình
        $name = trim($request->input('name', ''));
        if (empty($name)) {
            $errors['name'] = ['Tên chương trình là bắt buộc!'];
        } elseif (strlen($name) < 3) {
            $errors['name'] = ['Tên chương trình phải có ít nhất 3 ký tự!'];
        } elseif (strlen($name) > 255) {
            $errors['name'] = ['Tên chương trình không được vượt quá 255 ký tự!'];
        }
        
        // Validation cho kiểu áp dụng
        $flashType = $request->input('flash_type');
        if (empty($flashType)) {
            $errors['flash_type'] = ['Vui lòng chọn kiểu áp dụng!'];
        } elseif (!in_array($flashType, ['all', 'category', 'flash_sale'])) {
            $errors['flash_type'] = ['Kiểu áp dụng không hợp lệ!'];
        }
        
        // Validation cho ngày tháng
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $today = now()->format('Y-m-d H:i:s');
        
        if (empty($startDate)) {
            $errors['start_date'] = ['Vui lòng chọn ngày bắt đầu!'];
        }
        // Bỏ validation ngày bắt đầu < today vì đã được tự động cập nhật trong update method
        
        if (empty($endDate)) {
            $errors['end_date'] = ['Vui lòng chọn ngày kết thúc!'];
        } elseif ($startDate && $endDate <= $startDate) {
            $errors['end_date'] = ['Ngày kết thúc phải lớn hơn ngày bắt đầu!'];
        }
        
        // Validation cho trạng thái
        $status = $request->input('status');
        if ($status === null || $status === '') {
            $errors['status'] = ['Vui lòng chọn trạng thái!'];
        }
        
        // Validation cho flash_sale
        if ($flashType === 'flash_sale') {
            $products = $request->input('products', []);
            $discountPercents = $request->input('discount_percents', []);
            $salePrices = $request->input('sale_prices', []);
            
            if (empty($products)) {
                $errors['products'] = ['Vui lòng chọn ít nhất 1 sản phẩm!'];
            } else {
                $hasValidDiscount = false;
                foreach ($products as $productId) {
                    $discountPercent = $discountPercents[$productId] ?? null;
                    $salePrice = $salePrices[$productId] ?? null;
                    
                    // Validation cho discount percent
                    if ($discountPercent !== null && $discountPercent !== '') {
                        if ($discountPercent < 0 || $discountPercent > 100) {
                            $errors['discount_percents.' . $productId] = ['Phần trăm giảm giá phải từ 0-100%!'];
                        }
                    }
                    
                    // Validation cho sale price
                    if ($salePrice !== null && $salePrice !== '') {
                        if ($salePrice < 0) {
                            $errors['sale_prices.' . $productId] = ['Giá cố định phải lớn hơn 0!'];
                        } elseif ($salePrice > 9999999) {
                            $errors['sale_prices.' . $productId] = ['Giá cố định tối đa 9,999,999₫!'];
                        }
                    }
                    
                    if (($discountPercent && $discountPercent > 0 && $discountPercent <= 100) ||
                        ($salePrice && $salePrice > 0 && $salePrice <= 9999999)) {
                        $hasValidDiscount = true;
                    }
                }
                
                if (!$hasValidDiscount) {
                    $errors['products'] = ['Vui lòng nhập phần trăm giảm giá hoặc giá cố định cho ít nhất 1 sản phẩm!'];
                }
            }
        }
        
        // Validation cho category
        if ($flashType === 'category') {
            $categories = $request->input('categories', []);
            $categoryDiscountValue = $request->input('category_discount_value');
            
            if (empty($categories)) {
                $errors['categories'] = ['Vui lòng chọn ít nhất 1 danh mục!'];
            }
            
            if (!$categoryDiscountValue || $categoryDiscountValue < 1 || $categoryDiscountValue > 100) {
                $errors['category_discount_value'] = ['Giá trị giảm giá phải từ 1-100%!'];
            }
        }
        
        return $errors;
    }

    /**
     * Revert giá sản phẩm về giá cũ khi ẩn promotion
     */
    private function revertPromotionPrices($promotion)
    {
        if ($promotion->flash_type === 'category') {
            // Revert giá sản phẩm theo danh mục
            $categoryIds = $promotion->categories()->pluck('categories.id')->toArray();
            $productIds = \App\Models\Product::whereIn('category_id', $categoryIds)->pluck('id')->toArray();
            foreach ($productIds as $pid) {
                $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                foreach ($variants as $variant) {
                    if (!is_null($variant->old_sale_price)) {
                        $variant->sale_price = $variant->old_sale_price;
                        $variant->old_sale_price = null;
                        $variant->save();
                    }
                }
            }
        } elseif ($promotion->flash_type === 'flash_sale') {
            // Revert giá sản phẩm flash sale
            $oldProductIds = $promotion->products()->pluck('products.id')->toArray();
            \App\Models\ProductVariant::whereIn('product_id', $oldProductIds)
                ->whereNotNull('old_sale_price')
                ->update(['sale_price' => DB::raw('old_sale_price'), 'old_sale_price' => null]);
        }
    }
}
