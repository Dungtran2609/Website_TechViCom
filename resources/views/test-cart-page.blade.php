<!DOCTYPE html>
<html>
<head>
    <title>Test Cart</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Add to Cart</h1>
    <button onclick="testAddCart()">Test Add Cart</button>
    <button onclick="checkCart()">Check Cart</button>
    <div id="result"></div>

    <script>
        function testAddCart() {
            fetch('/test-add-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    test: true
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Add cart result:', data);
                document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('result').innerHTML = 'Error: ' + error;
            });
        }

        function checkCart() {
            fetch('/test-cart')
                .then(response => response.json())
                .then(data => {
                    console.log('Current cart:', data);
                    document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                });
        }
    </script>
</body>
</html>
