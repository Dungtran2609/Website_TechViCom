<?php

require_once 'vendor/autoload.php';

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Tạo đơn hàng test với nhiều sản phẩm ===\n";

// Lấy user đầu tiên
$user = User::first();
if (!$user) {
    echo "❌ Không tìm thấy user nào!\n";
    exit;
}

// Lấy 3 sản phẩm khác nhau
$products = Product::take(3)->get();
if ($products->count() < 3) {
    echo "❌ Không đủ sản phẩm (cần 3, có {$products->count()})\n";
    exit;
}

echo "✅ Tìm thấy user: {$user->name}\n";
echo "✅ Tìm thấy {$products->count()} sản phẩm\n";

// Tạo đơn hàng
$order = Order::create([
    'user_id' => $user->id,
    'status' => 'cancelled',
    'total_price' => 0,
    'final_total' => 0,
    'payment_method' => 'cod',
    'shipping_address' => 'Test Address',
    'shipping_phone' => '0123456789',
    'shipping_name' => 'Test User',
    'shipping_fee' => 30000,
    'cancelled_at' => Carbon::now()->subDays(2),
    'created_at' => Carbon::now()->subDays(5),
    'updated_at' => Carbon::now()->subDays(2),
]);

echo "✅ Đã tạo đơn hàng ID: {$order->id}\n";

$totalPrice = 0;

// Tạo order items cho từng sản phẩm
foreach ($products as $index => $product) {
    $quantity = $index + 1; // 1, 2, 3
    $price = $product->price ?? 1000000;
    $itemTotal = $price * $quantity;
    $totalPrice += $itemTotal;
    
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'name_product' => $product->name,
        'image_product' => $product->image,
        'price' => $price,
        'quantity' => $quantity,
        'total_price' => $itemTotal,
    ]);
    
    echo "✅ Đã thêm sản phẩm: {$product->name} (SL: {$quantity})\n";
}

// Cập nhật tổng tiền đơn hàng
$order->update([
    'total_price' => $totalPrice,
    'final_total' => $totalPrice,
]);

echo "✅ Cập nhật tổng tiền: " . number_format($totalPrice, 0, ',', '.') . " VND\n";

// Kiểm tra lại
$order->refresh();
echo "\n=== Kiểm tra kết quả ===\n";
echo "Đơn hàng ID: {$order->id}\n";
echo "Trạng thái: {$order->status}\n";
echo "Số sản phẩm: {$order->orderItems->count()}\n";
echo "Tổng tiền: " . number_format($order->final_total, 0, ',', '.') . " VND\n";

foreach ($order->orderItems as $item) {
    echo "- {$item->name_product} (SL: {$item->quantity})\n";
}

echo "\n🎉 Hoàn thành! Hãy refresh trang đơn hàng và tìm đơn hàng này trong tab 'Đã hủy'!\n";
