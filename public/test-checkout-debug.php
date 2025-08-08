<?php
session_start();

echo "<h1>Checkout Debug</h1>";

if (isset($_SESSION['cart'])) {
    echo "<h2>Session Cart Data:</h2>";
    echo "<pre>";
    print_r($_SESSION['cart']);
    echo "</pre>";
    
    $subtotal = 0;
    echo "<h2>Price Calculation:</h2>";
    
    foreach ($_SESSION['cart'] as $key => $item) {
        echo "<div style='border:1px solid #ccc; padding:10px; margin:10px 0;'>";
        echo "<strong>Item Key:</strong> " . $key . "<br>";
        echo "<strong>Product ID:</strong> " . ($item['product_id'] ?? 'N/A') . "<br>";
        echo "<strong>Variant ID:</strong> " . ($item['variant_id'] ?? 'N/A') . "<br>";
        echo "<strong>Quantity:</strong> " . ($item['quantity'] ?? 'N/A') . "<br>";
        echo "<strong>Price from session:</strong> " . ($item['price'] ?? 'N/A') . "<br>";
        
        if (isset($item['price']) && isset($item['quantity'])) {
            $itemTotal = $item['price'] * $item['quantity'];
            $subtotal += $itemTotal;
            echo "<strong>Item Total:</strong> " . number_format($itemTotal) . "<br>";
        }
        
        echo "</div>";
    }
    
    echo "<h2>Final Calculation:</h2>";
    echo "<strong>Subtotal:</strong> " . number_format($subtotal) . " đ<br>";
    echo "<strong>Shipping:</strong> 30,000 đ<br>";
    echo "<strong>Total:</strong> " . number_format($subtotal + 30000) . " đ<br>";
    
} else {
    echo "<p>No cart data in session</p>";
}
?>
