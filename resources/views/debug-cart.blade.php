@extends('client.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Debug Cart</h1>
    
    <!-- Add Test Product -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-xl font-semibold mb-4">Add Test Product</h2>
        <button onclick="addTestProduct()" class="bg-blue-500 text-white px-4 py-2 rounded">
            Add Product ID 1 to Cart
        </button>
    </div>

    <!-- Session Debug -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-xl font-semibold mb-4">Session Cart Debug</h2>
        <div id="session-debug">
            @if(!Auth::check())
                <p><strong>Session Cart:</strong></p>
                <pre>{{ json_encode(session()->get('cart', []), JSON_PRETTY_PRINT) }}</pre>
                <p><strong>Session ID:</strong> {{ session()->getId() }}</p>
            @else
                <p>User is authenticated, using database cart</p>
            @endif
        </div>
        <button onclick="refreshDebug()" class="bg-green-500 text-white px-4 py-2 rounded mt-4">
            Refresh Debug Info
        </button>
    </div>

    <!-- Cart Items -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-xl font-semibold mb-4">Cart Items from API</h2>
        <div id="cart-items-debug"></div>
        <button onclick="loadCartItemsDebug()" class="bg-purple-500 text-white px-4 py-2 rounded mt-4">
            Load Cart Items
        </button>
    </div>

    <!-- Test Controls -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Test Controls</h2>
        <div id="test-controls"></div>
    </div>
</div>

<script>
function log(message) {
    console.log(message);
}

function addTestProduct() {
    fetch('/client/carts/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: 1,
            quantity: 1,
            variant_id: null
        })
    })
    .then(response => response.json())
    .then(data => {
        log('Add response: ' + JSON.stringify(data));
        if (data.success) {
            alert('Added successfully!');
            refreshDebug();
            loadCartItemsDebug();
        } else {
            alert('Failed: ' + data.message);
        }
    })
    .catch(error => {
        log('Add error: ' + error);
        alert('Error: ' + error.message);
    });
}

function refreshDebug() {
    fetch('/debug-cart-data')
    .then(response => response.json())
    .then(data => {
        document.getElementById('session-debug').innerHTML = 
            '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
    })
    .catch(error => {
        log('Debug refresh error: ' + error);
    });
}

function loadCartItemsDebug() {
    fetch('/client/carts', {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        log('Cart items response: ' + JSON.stringify(data));
        
        if (data.success && data.items && data.items.length > 0) {
            let html = '<h3>Items:</h3>';
            data.items.forEach(item => {
                html += `
                    <div class="border p-4 rounded mb-2">
                        <p><strong>ID:</strong> ${item.id}</p>
                        <p><strong>Name:</strong> ${item.name}</p>
                        <p><strong>Quantity:</strong> ${item.quantity}</p>
                        <p><strong>Price:</strong> ${item.price}</p>
                        <div class="mt-2">
                            <button onclick="testUpdate('${item.id}', ${item.quantity + 1})" 
                                    class="bg-green-500 text-white px-2 py-1 rounded text-sm">+</button>
                            <button onclick="testUpdate('${item.id}', ${item.quantity - 1})" 
                                    class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">-</button>
                            <button onclick="testRemove('${item.id}')" 
                                    class="bg-red-500 text-white px-2 py-1 rounded text-sm">Remove</button>
                        </div>
                    </div>
                `;
            });
            document.getElementById('cart-items-debug').innerHTML = html;
        } else {
            document.getElementById('cart-items-debug').innerHTML = '<p>No items in cart</p>';
        }
    })
    .catch(error => {
        log('Cart items error: ' + error);
        document.getElementById('cart-items-debug').innerHTML = '<p>Error loading items</p>';
    });
}

function testUpdate(itemId, newQuantity) {
    log('Testing update for item: ' + itemId + ' to quantity: ' + newQuantity);
    
    if (newQuantity < 1) {
        testRemove(itemId);
        return;
    }
    
    fetch(`/client/carts/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
        log('Update response: ' + JSON.stringify(data));
        if (data.success) {
            alert('Updated successfully!');
            refreshDebug();
            loadCartItemsDebug();
        } else {
            alert('Update failed: ' + data.message);
        }
    })
    .catch(error => {
        log('Update error: ' + error);
        alert('Update error: ' + error.message);
    });
}

function testRemove(itemId) {
    log('Testing remove for item: ' + itemId);
    
    fetch(`/client/carts/${itemId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        log('Remove response status: ' + response.status);
        return response.json();
    })
    .then(data => {
        log('Remove response: ' + JSON.stringify(data));
        if (data.success) {
            alert('Removed successfully!');
            refreshDebug();
            loadCartItemsDebug();
        } else {
            alert('Remove failed: ' + data.message);
        }
    })
    .catch(error => {
        log('Remove error: ' + error);
        alert('Remove error: ' + error.message);
    });
}

// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    refreshDebug();
    loadCartItemsDebug();
});
</script>
@endsection
