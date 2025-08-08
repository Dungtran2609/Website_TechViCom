<?php
// Simple test script to check cart functionality

// Start session to test cart
session_start();

// Simulate adding to cart
$cart = $_SESSION['cart'] ?? [];
$key = '1_default';

if (isset($cart[$key])) {
    $cart[$key]['quantity'] += 1;
    echo "Updated existing item\n";
} else {
    $cart[$key] = [
        'product_id' => 1,
        'variant_id' => null,
        'quantity' => 1,
        'product' => ['name' => 'Test Product']
    ];
    echo "Added new item\n";
}

$_SESSION['cart'] = $cart;

echo "Current cart:\n";
print_r($_SESSION['cart']);

echo "\nCart count: " . array_sum(array_column($cart, 'quantity')) . "\n";
