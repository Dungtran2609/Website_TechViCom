<?php

require_once 'vendor/autoload.php';

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG HÌNH ẢNH GIỎ HÀNG ===\n\n";

// Lấy các item trong giỏ hàng
$cartItems = Cart::with(['product.productAllImages', 'productVariant'])->get();

if ($cartItems->isEmpty()) {
    echo "❌ Không có item nào trong giỏ hàng!\n";
    exit;
}

echo "✅ Tìm thấy " . $cartItems->count() . " item trong giỏ hàng\n\n";

foreach ($cartItems as $index => $item) {
    echo "📦 ITEM " . ($index + 1) . ":\n";
    echo "   - Product ID: {$item->product_id}\n";
    echo "   - Variant ID: " . ($item->variant_id ?? 'NULL') . "\n";
    echo "   - Product Name: {$item->product->name}\n";
    
    // Debug chi tiết logic lấy hình ảnh
    echo "\n   🔍 DEBUG LOGIC HÌNH ẢNH:\n";
    
    // 1. Kiểm tra productVariant
    if ($item->productVariant) {
        echo "   ✅ Có productVariant\n";
        echo "   - Variant Image: " . ($item->productVariant->image ?? 'NULL') . "\n";
        if ($item->productVariant->image) {
            echo "   - Full Path: storage/" . ltrim($item->productVariant->image, '/') . "\n";
        }
    } else {
        echo "   ❌ Không có productVariant\n";
    }
    
    // 2. Kiểm tra product variants
    echo "   - Product Variants Count: " . ($item->product->variants->count() ?? 0) . "\n";
    if ($item->product->variants->count() > 0) {
        $firstVariant = $item->product->variants->first();
        echo "   - First Variant Image: " . ($firstVariant->image ?? 'NULL') . "\n";
    }
    
    // 3. Kiểm tra product thumbnail
    echo "   - Product Thumbnail: " . ($item->product->thumbnail ?? 'NULL') . "\n";
    
    // 4. Kiểm tra product images
    echo "   - Product Images Count: " . ($item->product->productAllImages->count() ?? 0) . "\n";
    
    // Test logic lấy hình ảnh như trong controller
    $image = 'client_css/images/placeholder.svg';
    $imageSource = 'placeholder';
    
    echo "\n   🎯 KẾT QUẢ LOGIC:\n";
    
    // 1. Ưu tiên hình ảnh của biến thể cụ thể
    if ($item->productVariant && $item->productVariant->image) {
        $image = 'storage/' . ltrim($item->productVariant->image, '/');
        $imageSource = 'variant';
        echo "   ✅ Lấy ảnh biến thể: {$image}\n";
    }
    // 2. Nếu sản phẩm có biến thể thì lấy ảnh biến thể đầu tiên
    elseif ($item->product->variants && $item->product->variants->count() > 0) {
        $variant = $item->product->variants->first();
        if ($variant && $variant->image) {
            $image = 'storage/' . ltrim($variant->image, '/');
            $imageSource = 'first_variant';
            echo "   ✅ Lấy ảnh biến thể đầu tiên: {$image}\n";
        } else {
            echo "   ❌ Biến thể đầu tiên không có ảnh\n";
        }
    }
    // 3. Fallback sang thumbnail của sản phẩm
    elseif ($item->product->thumbnail) {
        $image = 'storage/' . ltrim($item->product->thumbnail, '/');
        $imageSource = 'product_thumbnail';
        echo "   ✅ Lấy ảnh thumbnail: {$image}\n";
    }
    // 4. Fallback sang ảnh đầu tiên của sản phẩm
    elseif ($item->product->productAllImages && $item->product->productAllImages->count() > 0) {
        $imgObj = $item->product->productAllImages->first();
        $imgField = $imgObj->image_path ?? $imgObj->image_url ?? $imgObj->image ?? null;
        if ($imgField) {
            $image = 'storage/' . ltrim($imgField, '/');
            $imageSource = 'product_image';
            echo "   ✅ Lấy ảnh sản phẩm: {$image}\n";
        }
    }
    
    echo "   - Final Image: {$image}\n";
    echo "   - Image Source: {$imageSource}\n";
    
    echo "\n" . str_repeat("=", 60) . "\n\n";
}

echo "✅ Debug hoàn thành!\n";
