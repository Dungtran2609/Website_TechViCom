<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\Order;
use App\Models\News;
use App\Models\Promotion;
use App\Models\Brand;
use Illuminate\Support\Arr;

class ChatbotService
{
    public function analyzeMessage($message, $history = [])
    {
        // Gửi prompt tới Gemini để nhận intent và entity
        $apiKey = config('services.gemini.api_key');
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
        $prompt = "Hãy phân tích câu hỏi sau, trả về JSON với các trường: intent, entities (vd: product_name, order_code, ...).\n";
        if (!empty($history)) {
            $prompt .= "Lịch sử hội thoại trước đó: " . json_encode($history) . "\n";
        }
        $prompt .= "Câu hỏi: $message";
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-goog-api-key' => $apiKey,
        ])->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);
        $data = $response->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $json = json_decode($text, true);
        // Lưu context sản phẩm hoặc chủ đề vào session nếu có
        if (is_array($json) && isset($json['entities']['product_name']) && $json['entities']['product_name']) {
            // Tìm chính xác product_id nếu có
            $product = \App\Models\Product::where('name', 'like', "%" . $json['entities']['product_name'] . "%")->first();
            if ($product) {
                Session::put('chatbot.last_product_id', $product->id);
                Session::put('chatbot.last_product_name', $product->name);
            } else {
                Session::put('chatbot.last_product_name', $json['entities']['product_name']);
            }
        }
        // Nếu không có product_name nhưng user hỏi tiếp về giá, tồn kho... thì lấy từ session (ưu tiên id)
        if (is_array($json) && (!isset($json['entities']['product_name']) || !$json['entities']['product_name'])) {
            $lastProductId = Session::get('chatbot.last_product_id');
            $lastProductName = Session::get('chatbot.last_product_name');
            if ($lastProductId) {
                $json['entities']['product_id'] = $lastProductId;
            } elseif ($lastProductName) {
                $json['entities']['product_name'] = $lastProductName;
            }
        }
        return is_array($json) ? $json : ['intent' => 'general', 'entities' => []];
    }

    public function getContextData($intent, $entities, $user = null)
    // ...existing code...
    {
        $hotline = "\nNếu bạn cần hỗ trợ nhanh chóng, vui lòng liên hệ hotline của TechViCom theo số 1800.6601 hoặc để lại thông tin để được hỗ trợ nhé!";
        // Ưu tiên lấy product_id nếu có, dùng cho intent product, product_by_price, v.v.
        if (isset($entities['product_id'])) {
            $product = Product::with(['brand', 'category', 'variants', 'allImages', 'productComments', 'orderItems'])
                ->find($entities['product_id']);
            if ($product) {
                $brand = $product->brand->name ?? '';
                $cat = $product->category->name ?? '';
                $price = $product->price;
                $stock = $product->total_stock ?? 'N/A';
                $desc = mb_substr($product->short_description ?? '', 0, 60) . '...';
                $promo = $product->orderItems->count() > 10 ? 'Sản phẩm bán chạy, có thể có ưu đãi.' : '';
                $comments = $product->productComments->map(function ($c) {
                    return 'Đánh giá: ' . ($c->rating ?? '-') . '/5 - ' . mb_substr($c->content, 0, 40) . '...';
                })->implode(' | ');
                $msg = "Tên: $product->name, Giá: $price, Tồn kho: $stock, Thương hiệu: $brand, Danh mục: $cat, Mô tả: $desc\n$promo\nĐánh giá: $comments";
                $msg .= $hotline;
                return $msg;
            }
        }
        switch ($intent) {
            case 'cheapest_product':
                // Nếu user đã hỏi về loại sản phẩm cụ thể (category)
                $cat = Arr::get($entities, 'category');
                if ($cat) {
                    $product = Product::whereHas('category', function ($q) use ($cat) {
                        $q->where('name', 'like', "%$cat%");
                    })->orderBy('price', 'asc')->first();
                } else {
                    $product = Product::orderBy('price', 'asc')->first();
                }
                if ($product) {
                    $brand = $product->brand->name ?? '';
                    $catName = $product->category->name ?? '';
                    $price = $product->price;
                    $stock = $product->total_stock > 0 ? 'Còn hàng (' . $product->total_stock . ' sp)' : 'Hết hàng';
                    $desc = mb_substr($product->short_description ?? '', 0, 60) . '...';
                    $url = method_exists($product, 'getDetailUrl') ? $product->getDetailUrl() : '';
                    $msg = "Hiện tại, sản phẩm rẻ nhất";
                    if ($cat) $msg .= " thuộc danh mục '$cat'";
                    $msg .= " của TechViCom là: \n";
                    $msg .= "Tên: $product->name\n";
                    $msg .= "Giá: $price VNĐ\n";
                    $msg .= "Tình trạng: $stock\n";
                    $msg .= $brand ? "Thương hiệu: $brand\n" : '';
                    $msg .= $catName ? "Danh mục: $catName\n" : '';
                    $msg .= "Mô tả: $desc\n";
                    if ($url) $msg .= "Xem chi tiết: $url\n";
                    $msg .= "Bạn có muốn xem thêm thông tin hoặc các sản phẩm khác phù hợp hơn không?";
                    $msg .= $hotline;
                    return $msg;
                }
                $msg = 'Hiện tại không tìm thấy sản phẩm rẻ nhất';
                if ($cat) $msg .= " cho danh mục '$cat'";
                $msg .= '.' . $hotline;
                return $msg;
            case 'product_by_price':
                $price = Arr::get($entities, 'price');
                if ($price) {
                    $products = Product::where('price', $price)->limit(5)->get();
                    if ($products->count()) {
                        $list = $products->map(function ($p) {
                            $stock = $p->total_stock > 0 ? 'Còn hàng (' . $p->total_stock . ' sp)' : 'Hết hàng';
                            return $p->name . ' [' . $stock . ']';
                        })->implode(', ');
                        return 'Sản phẩm giá ' . $price . ': ' . $list . $hotline;
                    }
                    return 'Không tìm thấy sản phẩm với mức giá này.' . $hotline;
                }
                $min = Arr::get($entities, 'min_price');
                $max = Arr::get($entities, 'max_price');
                if ($min && $max) {
                    $products = Product::whereBetween('price', [$min, $max])->limit(5)->get();
                    if ($products->count()) {
                        $list = $products->map(function ($p) {
                            $stock = $p->total_stock > 0 ? 'Còn hàng (' . $p->total_stock . ' sp)' : 'Hết hàng';
                            return $p->name . ' [' . $stock . ']';
                        })->implode(', ');
                        return 'Sản phẩm từ ' . $min . ' đến ' . $max . ': ' . $list . $hotline;
                    }
                    return 'Không tìm thấy sản phẩm trong khoảng giá này.' . $hotline;
                }
                return 'Vui lòng cung cấp mức giá hoặc khoảng giá.' . $hotline;
            case 'product_in_stock':
                $products = Product::where('total_stock', '>', 0)->limit(5)->get();
                if ($products->count()) {
                    $list = $products->map(function ($p) {
                        return $p->name . ' (Còn ' . $p->total_stock . ' sp)';
                    })->implode(', ');
                    return 'Sản phẩm còn hàng: ' . $list;
                }
                return 'Hiện tại không có sản phẩm nào còn hàng.';
            case 'product_out_of_stock':
                $products = Product::where('total_stock', '<=', 0)->limit(5)->get();
                if ($products->count()) {
                    $list = $products->map(function ($p) {
                        return $p->name . ' (Hết hàng)';
                    })->implode(', ');
                    return 'Sản phẩm hết hàng: ' . $list;
                }
                return 'Hiện tại không có sản phẩm nào hết hàng.';
            case 'latest_products':
                $products = Product::orderByDesc('created_at')->limit(5)->get();
                if ($products->count()) {
                    $list = $products->map(function ($p) {
                        $stock = $p->total_stock > 0 ? 'Còn hàng (' . $p->total_stock . ' sp)' : 'Hết hàng';
                        return $p->name . ' [' . $stock . ']';
                    })->implode(', ');
                    return 'Sản phẩm mới nhất: ' . $list;
                }
                return 'Chưa có sản phẩm mới.';
            case 'best_selling_products':
                $products = Product::withCount('orderItems')->orderByDesc('order_items_count')->limit(5)->get();
                if ($products->count()) {
                    $list = $products->map(function ($p) {
                        $stock = $p->total_stock > 0 ? 'Còn hàng (' . $p->total_stock . ' sp)' : 'Hết hàng';
                        return $p->name . ' [' . $stock . ']';
                    })->implode(', ');
                    return 'Sản phẩm bán chạy: ' . $list;
                }
                return 'Chưa có sản phẩm bán chạy.';
            case 'discount_products':
                $products = Product::where('discount', '>', 0)->orderByDesc('discount')->limit(5)->get();
                if ($products->count()) {
                    $list = $products->map(function ($p) {
                        $stock = $p->total_stock > 0 ? 'Còn hàng (' . $p->total_stock . ' sp)' : 'Hết hàng';
                        return $p->name . ' [' . $stock . ']';
                    })->implode(', ');
                    return 'Sản phẩm đang giảm giá: ' . $list;
                }
                return 'Hiện tại chưa có sản phẩm giảm giá.';
            case 'upcoming_products':
                $products = Product::where('is_upcoming', 1)->orderByDesc('created_at')->limit(5)->get();
                if ($products->count()) {
                    $list = $products->map(function ($p) {
                        $stock = $p->total_stock > 0 ? 'Còn hàng (' . $p->total_stock . ' sp)' : 'Hết hàng';
                        return $p->name . ' [' . $stock . ']';
                    })->implode(', ');
                    return 'Sản phẩm sắp ra mắt: ' . $list;
                }
                return 'Hiện tại chưa có sản phẩm sắp ra mắt.';
            case 'return_policy':
                return 'TechViCom hỗ trợ đổi trả trong 7 ngày với sản phẩm lỗi do nhà sản xuất hoặc chưa qua sử dụng. Liên hệ 1800.6601 để được hỗ trợ.';
            case 'product_by_sku':
                $sku = Arr::get($entities, 'sku');
                if ($sku) {
                    $product = Product::with(['brand', 'category', 'variants', 'allImages', 'productComments', 'orderItems'])
                        ->where('sku', $sku)
                        ->first();
                    if ($product) {
                        $brand = $product->brand->name ?? '';
                        $cat = $product->category->name ?? '';
                        $price = $product->price;
                        $stock = $product->total_stock ?? 'N/A';
                        $desc = mb_substr($product->short_description ?? '', 0, 60) . '...';
                        $promo = $product->orderItems->count() > 10 ? 'Sản phẩm bán chạy, có thể có ưu đãi.' : '';
                        $comments = $product->productComments->map(function ($c) {
                            return 'Đánh giá: ' . ($c->rating ?? '-') . '/5 - ' . mb_substr($c->content, 0, 40) . '...';
                        })->implode(' | ');
                        return "Tên: $product->name, Giá: $price, Tồn kho: $stock, Thương hiệu: $brand, Danh mục: $cat, Mô tả: $desc\n$promo\nĐánh giá: $comments";
                    }
                    return 'Không tìm thấy sản phẩm với mã này.';
                }
                return 'Vui lòng cung cấp mã sản phẩm (sku) để tra cứu.';
            case 'shipping_fee':
                return 'Phí vận chuyển được tính tự động khi đặt hàng. Mọi thắc mắc liên hệ 1800.6601 hoặc techvicom@gmail.com.';
            case 'payment_methods':
                return 'TechViCom hỗ trợ thanh toán tiền mặt, chuyển khoản, thẻ ATM, ví điện tử và thanh toán khi nhận hàng. Liên hệ: 1800.6601 - techvicom@gmail.com.';
            case 'store_location':
                return 'Địa chỉ: 13 Trịnh Văn Bô, Nam Từ Liêm, Hà Nội. Hotline: 1800.6601. Email: techvicom@gmail.com. Giờ mở cửa: 8:00 - 22:00 (T2-CN).';
            case 'contact':
                return "Địa chỉ: 13 Trịnh Văn Bô, Nam Từ Liêm, Hà Nội. Hotline: 1800.6601. Email: techvicom@gmail.com. Giờ làm việc: 8:00 - 22:00 (T2-CN).";
            case 'manual':
                $name = Arr::get($entities, 'product_name');
                if ($name) {
                    $product = Product::where('name', 'like', "%$name%")
                        ->first();
                    if ($product && $product->manual_url) {
                        return "Hướng dẫn sử dụng của $product->name: $product->manual_url";
                    }
                }
                return 'Vui lòng cung cấp tên sản phẩm để tra cứu hướng dẫn sử dụng.';
            case 'accessories':
                $name = Arr::get($entities, 'product_name');
                if ($name) {
                    $product = Product::with('accessories')->where('name', 'like', "%$name%")
                        ->first();
                    if ($product && $product->accessories && $product->accessories->count()) {
                        return "Phụ kiện kèm theo $product->name: " . $product->accessories->pluck('name')->implode(', ');
                    }
                }
                return 'Vui lòng cung cấp tên sản phẩm để tra cứu phụ kiện.';
            case 'product_reviews':
                $name = Arr::get($entities, 'product_name');
                if ($name) {
                    $product = Product::with('productComments')->where('name', 'like', "%$name%")
                        ->first();
                    if ($product && $product->productComments && $product->productComments->count()) {
                        return $product->productComments->take(3)->map(function ($c) {
                            return 'Đánh giá: ' . ($c->rating ?? '-') . '/5 - ' . mb_substr($c->content, 0, 40) . '...';
                        })->implode(' | ');
                    }
                }
                return 'Vui lòng cung cấp tên sản phẩm để xem đánh giá.';
            case 'product':
                $name = Arr::get($entities, 'product_name');
                if ($name) {
                    $product = Product::with(['brand', 'category', 'variants', 'allImages', 'productComments', 'orderItems'])
                        ->where('name', 'like', "%$name%")
                        ->first();
                    if ($product) {
                        $brand = $product->brand->name ?? '';
                        $cat = $product->category->name ?? '';
                        $price = $product->price;
                        $stock = $product->total_stock ?? 'N/A';
                        $desc = mb_substr($product->short_description ?? '', 0, 60) . '...';
                        $promo = $product->orderItems->count() > 10 ? 'Sản phẩm bán chạy, có thể có ưu đãi.' : '';
                        $comments = $product->productComments->map(function ($c) {
                            return 'Đánh giá: ' . ($c->rating ?? '-') . '/5 - ' . mb_substr($c->content, 0, 40) . '...';
                        })->implode(' | ');
                        $msg = "Tên: $product->name, Giá: $price, Tồn kho: $stock, Thương hiệu: $brand, Danh mục: $cat, Mô tả: $desc\n$promo\nĐánh giá: $comments";
                        $msg .= $hotline;
                        return $msg;
                    }
                }
                return null;
            case 'order':
                $code = Arr::get($entities, 'order_code');
                if ($code && $user) {
                    $order = Order::where('id', $code)->where('user_id', $user->id)->first();
                    if ($order) {
                        return "Đơn hàng #$order->id, trạng thái: $order->status, tổng tiền: $order->final_total";
                    }
                }
                return null;
            case 'product_comparison':
                $names = Arr::get($entities, 'product_names', []);
                if (is_array($names) && count($names) >= 2) {
                    $products = Product::with(['brand', 'category', 'variants', 'allImages', 'productComments', 'orderItems'])
                        ->whereIn('name', $names)->get();
                    if ($products->count() < 2) return 'Không đủ sản phẩm để so sánh.';
                    $rows = [];
                    foreach ($products as $p) {
                        $brand = $p->brand->name ?? '';
                        $cat = $p->category->name ?? '';
                        $price = $p->price;
                        $stock = $p->total_stock > 0 ? 'Còn hàng (' . $p->total_stock . ' sp)' : 'Hết hàng';
                        $desc = mb_substr($p->short_description ?? '', 0, 60) . '...';
                        $promo = $p->orderItems->count() > 10 ? 'Bán chạy/ưu đãi' : '';
                        $comments = $p->productComments->map(function ($c) {
                            return ($c->rating ?? '-') . '/5';
                        })->implode(', ');
                        $rows[] = "$p->name | Giá: $price | $stock | Thương hiệu: $brand | Danh mục: $cat | $promo | Đánh giá: $comments | Mô tả: $desc";
                    }
                    return "So sánh sản phẩm:\n" . implode("\n---\n", $rows);
                }
                return 'Vui lòng cung cấp ít nhất 2 sản phẩm để so sánh.';
            case 'product_filtering':
                $brand = Arr::get($entities, 'brand');
                $cat = Arr::get($entities, 'category');
                $query = Product::query();
                if ($brand) $query->whereHas('brand', function ($q) use ($brand) {
                    $q->where('name', 'like', "%$brand%");
                });
                if ($cat) $query->whereHas('category', function ($q) use ($cat) {
                    $q->where('name', 'like', "%$cat%");
                });
                $products = $query->limit(5)->get();
                $list = $products->map(function ($p) {
                    $stock = $p->total_stock > 0 ? 'Còn hàng (' . $p->total_stock . ' sp)' : 'Hết hàng';
                    return $p->name . ' [' . $stock . ']';
                })->implode(', ');
                return $list;
            case 'promotion':
                $promo = Promotion::orderByDesc('start_date')->first();
                if ($promo) {
                    return "Khuyến mãi: $promo->name, Mô tả: " . (mb_substr($promo->description ?? '', 0, 60) . '...') . ", Thời gian: $promo->start_date - $promo->end_date";
                }
                return 'Hiện tại chưa có chương trình khuyến mãi.';
            case 'news':
                $news = News::orderByDesc('published_at')->first();
                if ($news) {
                    return "Tin tức mới: $news->title, Ngày: $news->published_at, Tóm tắt: " . (mb_substr(strip_tags($news->content), 0, 60) . '...');
                }
                return 'Chưa có tin tức mới.';
            case 'warranty':
                $name = Arr::get($entities, 'product_name');
                if ($name) {
                    $product = Product::where('name', 'like', "%$name%")
                        ->first();
                    if ($product && $product->warranty) {
                        return "Chính sách bảo hành của $product->name: $product->warranty";
                    }
                }
                return 'Vui lòng cung cấp tên sản phẩm để tra cứu bảo hành.';
            default:
                return null;
        }
    }
}
