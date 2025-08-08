<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $order = \App\Models\Order::create([
        'user_id' => null,
        'address_id' => null,
        'guest_name' => 'Test Guest',
        'guest_email' => 'test@test.com',
        'guest_phone' => '0123456789',
        'payment_method' => 'cod',
        'coupon_id' => null,
        'coupon_code' => null,
        'discount_amount' => 0,
        'shipping_fee' => 30000,
        'total_amount' => 100000,
        'final_total' => 100000,
        'status' => 'pending',
        'payment_status' => 'pending',
        'recipient_name' => 'Test Recipient',
        'recipient_phone' => '0123456789',
        'recipient_address' => 'Test Address',
        'shipping_method_id' => 1
    ]);
    echo "✅ Order created successfully with ID: " . $order->id . "\n";
} catch(\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
