<?php
session_start();

// Test session cart functionality
echo "<h1>Session Cart Test</h1>";

// Add item to cart
if (isset($_GET['add'])) {
    $cart = $_SESSION['cart'] ?? [];
    $key = '1_default';
    
    if (isset($cart[$key])) {
        $cart[$key]['quantity'] += 1;
    } else {
        $cart[$key] = [
            'product_id' => 1,
            'variant_id' => null,
            'quantity' => 1
        ];
    }
    
    $_SESSION['cart'] = $cart;
    echo "<p>Added item to cart</p>";
}

// Update quantity
if (isset($_GET['update']) && isset($_GET['quantity'])) {
    $cart = $_SESSION['cart'] ?? [];
    $key = '1_default';
    
    if (isset($cart[$key])) {
        $cart[$key]['quantity'] = (int)$_GET['quantity'];
        $_SESSION['cart'] = $cart;
        echo "<p>Updated quantity to " . $_GET['quantity'] . "</p>";
    }
}

// Remove item
if (isset($_GET['remove'])) {
    $cart = $_SESSION['cart'] ?? [];
    $key = '1_default';
    
    unset($cart[$key]);
    $_SESSION['cart'] = $cart;
    echo "<p>Removed item from cart</p>";
}

// Display current cart
$cart = $_SESSION['cart'] ?? [];
echo "<h2>Current Cart:</h2>";
echo "<pre>" . print_r($cart, true) . "</pre>";

echo "<h2>Actions:</h2>";
echo "<a href='?add=1'>Add Item</a> | ";
echo "<a href='?update=1&quantity=5'>Update to 5</a> | ";
echo "<a href='?remove=1'>Remove Item</a>";

echo "<h2>AJAX Test:</h2>";
?>
<script>
function testUpdate() {
    fetch('/TechViCom_Website/public/client/carts/1_default', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
        },
        body: JSON.stringify({
            quantity: 3
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data);
        alert('Response: ' + JSON.stringify(data));
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    });
}
</script>
<button onclick="testUpdate()">Test AJAX Update</button>
