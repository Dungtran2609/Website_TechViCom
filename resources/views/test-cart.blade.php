<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Test Cart Functionality</h1>
        
        <!-- Add to Cart Test -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Add to Cart Test</h2>
            <button onclick="testAddToCart()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add Test Product to Cart
            </button>
        </div>

        <!-- Cart Items -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Current Cart Items</h2>
            <button onclick="loadCartItems()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mb-4">
                Refresh Cart
            </button>
            <div id="cart-items" class="space-y-4">
                <!-- Cart items will be loaded here -->
            </div>
        </div>

        <!-- Debug Info -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Debug Info</h2>
            <div id="debug-info" class="bg-gray-100 p-4 rounded text-sm font-mono">
                <!-- Debug info will appear here -->
            </div>
        </div>
    </div>

    <script>
        function log(message) {
            const debugDiv = document.getElementById('debug-info');
            debugDiv.innerHTML += new Date().toLocaleTimeString() + ': ' + JSON.stringify(message) + '\n';
            console.log(message);
        }

        function testAddToCart() {
            log('Testing add to cart...');
            
            fetch('/client/carts/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: 1,
                    quantity: 1,
                    variant_id: null
                })
            })
            .then(response => {
                log('Add response status: ' + response.status);
                return response.json();
            })
            .then(data => {
                log('Add response data: ' + JSON.stringify(data));
                if (data.success) {
                    loadCartItems();
                }
            })
            .catch(error => {
                log('Add error: ' + error.message);
            });
        }

        function loadCartItems() {
            log('Loading cart items...');
            
            fetch('/client/carts', {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                log('Load response status: ' + response.status);
                return response.json();
            })
            .then(data => {
                log('Load response data: ' + JSON.stringify(data));
                displayCartItems(data.items || []);
            })
            .catch(error => {
                log('Load error: ' + error.message);
            });
        }

        function displayCartItems(items) {
            const cartDiv = document.getElementById('cart-items');
            
            if (items.length === 0) {
                cartDiv.innerHTML = '<p class="text-gray-500">No items in cart</p>';
                return;
            }

            cartDiv.innerHTML = items.map(item => `
                <div class="border p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-medium">${item.name}</h3>
                            <p class="text-gray-600">ID: ${item.id}</p>
                            <p class="text-green-600">Price: ${formatPrice(item.price)}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="updateQuantity('${item.id}', ${item.quantity - 1})" 
                                    class="bg-red-500 text-white px-2 py-1 rounded">-</button>
                            <span class="font-medium">${item.quantity}</span>
                            <button onclick="updateQuantity('${item.id}', ${item.quantity + 1})" 
                                    class="bg-green-500 text-white px-2 py-1 rounded">+</button>
                            <button onclick="removeItem('${item.id}')" 
                                    class="bg-red-600 text-white px-3 py-1 rounded ml-2">Remove</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function updateQuantity(itemId, newQuantity) {
            log('Updating quantity for item ' + itemId + ' to ' + newQuantity);
            
            if (newQuantity < 1) {
                removeItem(itemId);
                return;
            }

            fetch(`/client/carts/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    quantity: newQuantity
                })
            })
            .then(response => {
                log('Update response status: ' + response.status);
                return response.json();
            })
            .then(data => {
                log('Update response data: ' + JSON.stringify(data));
                if (data.success) {
                    loadCartItems();
                }
            })
            .catch(error => {
                log('Update error: ' + error.message);
            });
        }

        function removeItem(itemId) {
            log('Removing item ' + itemId);
            
            fetch(`/client/carts/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                log('Remove response status: ' + response.status);
                return response.json();
            })
            .then(data => {
                log('Remove response data: ' + JSON.stringify(data));
                if (data.success) {
                    loadCartItems();
                }
            })
            .catch(error => {
                log('Remove error: ' + error.message);
            });
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(price);
        }

        // Load cart items on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCartItems();
        });
    </script>
</body>
</html>
