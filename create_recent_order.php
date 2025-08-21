<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CREATE RECENT ORDER ===\n\n";

// Get first user and product
$user = User::first();
$product = Product::first();
$variant = ProductVariant::where('product_id', $product->id)->first();

// Create order with received status (received today)
$order = Order::create([
    'user_id' => $user->id,
    'recipient_name' => $user->name,
    'recipient_phone' => '0123456789',
    'recipient_email' => $user->email,
    'recipient_address' => 'Test Address',
    'shipping_method_id' => 1,
    'payment_method' => 'cod',
    'payment_status' => 'paid',
    'shipping_fee' => 0,
    'total_amount' => $variant->price,
    'final_total' => $variant->price,
    'status' => 'received',
    'received_at' => now(), // Received today
]);

echo "Created order ID: {$order->id}\n";

// Create order item
$orderItem = OrderItem::create([
    'order_id' => $order->id,
    'product_id' => $product->id,
    'variant_id' => $variant->id,
    'quantity' => 1,
    'price' => $variant->price,
    'total_price' => $variant->price,
    'name_product' => $product->name,
    'image_product' => $product->thumbnail,
]);

echo "Created order item ID: {$orderItem->id}\n";
echo "Order received today: " . $order->received_at->format('Y-m-d H:i:s') . "\n";
echo "Days since received: " . now()->diffInDays($order->received_at) . "\n";
echo "Can review: Yes (within 15 days)\n";
