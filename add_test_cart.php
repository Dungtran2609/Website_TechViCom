<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Start session
session_start();

// Add test product to session cart
$cart = [
    [
        'product_id' => 1, // Assuming product with ID 1 exists
        'quantity' => 1,
        'variant_id' => null
    ]
];

session(['cart' => $cart]);

echo "✅ Test cart added to session\n";
echo "Cart content: " . json_encode($cart) . "\n";
