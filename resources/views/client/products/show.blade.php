@extends('client.layouts.app')


@section('title', isset($product) ? $product->name . ' - Techvicom' : 'Chi tiết sản phẩm - Techvicom')


@section('content')
    @if (isset($product))
        @php
            $activeVariants = $product->variants->where('is_active', true);
        @endphp


        <!-- Breadcrumb -->
        <nav class="bg-white border-b border-gray-200 py-3">
            <div class="container mx-auto px-4">
                <div class="flex items-center space-x-2 text-sm">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#ff6c2f]">Trang chủ</a>
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-[#ff6c2f]">Sản phẩm</a>
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <span class="text-gray-900 font-medium">{{ $product->name }}</span>
                </div>
            </div>
        </nav>


        <!-- Product Detail -->
        <section class="py-10">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- Cột hình ảnh -->
                    <div class="space-y-4">
                        <div class="aspect-square bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm p-4">
                            <img id="main-image" src="{{ $product->productAllImages->first() ? asset('storage/' . $product->productAllImages->first()->image_path) : asset('admin_css/images/placeholder.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-contain" onerror="this.onerror=null;this.src='{{ asset('admin_css/images/placeholder.jpg') }}'">
                        </div>


                        @if ($product->productAllImages->count() > 1)
                            <div class="grid grid-cols-5 gap-3">
                                @foreach ($product->productAllImages as $image)
                                    <div class="aspect-square bg-white rounded-lg border border-gray-200 overflow-hidden cursor-pointer hover:border-[#ff6c2f] transition p-2" onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }} thumbnail" class="w-full h-full object-contain" onerror="this.onerror=null;this.src='{{ asset('admin_css/images/placeholder.jpg') }}'">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>


                    <!-- Cột thông tin sản phẩm -->
                    <div class="flex flex-col space-y-6">
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">{{ $product->name }}</h1>


                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                            @if ($product->brand)
                                <span class="flex items-center text-gray-600"><i class="fas fa-tag mr-2 text-gray-400"></i> Thương hiệu: <a href="#" class="ml-1 font-semibold text-blue-600 hover:underline">{{ $product->brand->name }}</a></span>
                            @endif
                            @if ($product->category)
                                <span class="flex items-center text-gray-600"><i class="fas fa-folder-open mr-2 text-gray-400"></i> Danh mục: <span class="ml-1 font-semibold">{{ $product->category->name }}</span></span>
                            @endif
                        </div>


                        @if ($product->short_description)
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                                <p class="text-gray-700 italic">{{ $product->short_description }}</p>
                            </div>
                        @endif


                        <div class="price-display-area text-4xl font-bold text-[#ff6c2f]">
                             @if ($activeVariants->isNotEmpty())
                                @php
                                    $minPrice = $activeVariants->min('price');
                                    $maxPrice = $activeVariants->max('price');
                                @endphp
                                @if ($minPrice === $maxPrice)
                                    <span>{{ number_format($minPrice, 0, ',', '.') }}₫</span>
                                @else
                                    <span>{{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }}₫</span>
                                @endif
                            @else
                                <span class="text-3xl text-gray-500">Tạm hết hàng</span>
                            @endif
                        </div>


                        <div id="info-container" class="flex flex-wrap items-center gap-3 text-sm h-8"></div>


                        @if ($product->type === 'variable' && $activeVariants->isNotEmpty())
                            <form id="variant-form" class="space-y-5">
                                @php
                                    $attributesData = $activeVariants->flatMap(fn($v) => $v->attributeValues)->groupBy('attribute.name')->map(fn($vals) => $vals->unique('value')->sortBy('value')->values());
                                @endphp
                                @foreach ($attributesData as $name => $attributeValues)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-800 mb-2">{{ $name }}: <span class="text-gray-500 font-normal" data-variant-name-display="{{$name}}"></span></label>
                                        <div class="flex flex-wrap gap-3 items-center attribute-options" data-attribute-name="{{ $name }}">
                                            @foreach ($attributeValues as $attrValue)
                                                @if ((str_contains(strtolower($name), 'màu') || str_contains(strtolower($name), 'color')) && !empty($attrValue->color_code))
                                                    <button type="button" title="{{ $attrValue->value }}" class="variant-option-button color-swatch w-8 h-8 rounded-full border-2 border-transparent focus:outline-none transition-all duration-200" style="background-color: {{ $attrValue->color_code }}" data-attribute-name="{{ $name }}" data-attribute-value="{{ $attrValue->value }}"></button>
                                                @else
                                                    <button type="button" class="variant-option-button px-4 py-2 border border-gray-300 rounded-lg text-sm hover:border-[#ff6c2f] focus:outline-none transition" data-attribute-name="{{ $name }}" data-attribute-value="{{ $attrValue->value }}">{{ $attrValue->value }}</button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </form>
                        @endif


                        <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
                            <label class="text-sm font-medium">Số lượng:</label>
                            <div class="flex items-center">
                                <button type="button" onclick="updateQuantity(-1)" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-l-lg hover:bg-gray-100 transition"><i class="fas fa-minus text-xs"></i></button>
                                <input type="number" id="quantity" value="1" min="1" class="w-12 h-8 text-center border-t border-b border-gray-300 focus:outline-none" readonly>
                                <button type="button" onclick="updateQuantity(1)" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-r-lg hover:bg-gray-100 transition"><i class="fas fa-plus text-xs"></i></button>
                            </div>
                        </div>


                        <div class="space-y-3">
                             <button type="button" onclick="addProductToCart()" class="w-full bg-[#ff6c2f] text-white py-3 px-4 rounded-lg hover:bg-[#e55a28] transition font-bold flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-shopping-cart mr-2"></i> Thêm vào giỏ hàng
                            </button>
                            <button type="button" onclick="buyNow()" class="w-full bg-gray-800 text-white py-3 px-4 rounded-lg hover:bg-black transition font-bold disabled:opacity-50 disabled:cursor-not-allowed">
                                Mua ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    @else
        <section class="py-16 text-center">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Sản phẩm không tìm thấy</h1>
            <a href="{{ route('client.products.index') }}" class="text-white bg-red-600 hover:bg-red-700 px-6 py-3 rounded-lg transition">Quay lại trang sản phẩm</a>
        </section>
    @endif
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @php
        $jsProductData = [
            'type' => $product->type,
            'variants' => $activeVariants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'price' => $variant->price,
                    'sale_price' => $variant->sale_price,
                    'stock' => $variant->stock,
                    'sku' => $variant->sku,
                    'image' => $variant->image ? asset('storage/' . $variant->image) : null,
                    'attributes' => $variant->attributeValues->pluck('value', 'attribute.name'),
                ];
            })->values(),
            'is_featured' => $product->is_featured,
            'default_image' => $product->productAllImages->first() ? asset('storage/' . $product->productAllImages->first()->image_path) : asset('admin_css/images/placeholder.jpg'),
        ];
    @endphp


    const productData = @json($jsProductData);


    const mainImage = document.getElementById('main-image');
    const priceDisplay = document.querySelector('.price-display-area');
    const infoContainer = document.getElementById('info-container');
    const actionButtons = document.querySelectorAll('button[onclick="addProductToCart()"], button[onclick="buyNow()"]');
    const variantForm = document.getElementById('variant-form');
    const quantityInput = document.getElementById('quantity');


    let state = {
        selectedOptions: {},
        activeVariant: null,
        quantity: 1,
    };


    function render() {
        state.activeVariant = getActiveVariant();
       
        updatePriceDisplay();
        updateInfoDisplay();
        updateActionButtons();
        updateVariantOptionsUI();
        updateMainImage();
    }


    function getActiveVariant() {
        if (productData.type !== 'variable') {
            return productData.variants.length > 0 ? productData.variants[0] : null;
        }
        const attributeGroups = variantForm ? variantForm.querySelectorAll('.attribute-options') : [];
        if (Object.keys(state.selectedOptions).length !== attributeGroups.length) {
            return null;
        }
        return productData.variants.find(variant =>
            Object.entries(state.selectedOptions).every(([key, val]) => variant.attributes[key] === val)
        ) || null;
    }


    function updatePriceDisplay() {
        if (!priceDisplay) return;
        const formatter = new Intl.NumberFormat('vi-VN');
        let html = '';
        if (state.activeVariant) {
            const price = state.activeVariant.price;
            const salePrice = state.activeVariant.sale_price;
            if (salePrice && parseFloat(salePrice) < parseFloat(price)) {
                html = `<div class="flex items-end gap-3">
                            <span class="text-4xl font-bold text-[#ff6c2f]">${formatter.format(salePrice)}₫</span>
                            <span class="text-xl text-gray-500 line-through">${formatter.format(price)}₫</span>
                        </div>`;
            } else {
                html = `<span class="text-4xl font-bold text-[#ff6c2f]">${formatter.format(price)}₫</span>`;
            }
        }
        else if (priceDisplay.innerHTML.trim() === '') {
             priceDisplay.innerHTML = `<span></span>`;
        }
        if(html) priceDisplay.innerHTML = html;
    }


    function updateInfoDisplay() {
        if (!infoContainer) return;
        infoContainer.innerHTML = '';
        if (state.activeVariant) {
            let html = '';
            if(state.activeVariant.sku) html += `<span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full"><i class="fas fa-barcode mr-1"></i>SKU: ${state.activeVariant.sku}</span>`;
            if(productData.is_featured) html += `<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full"><i class="fas fa-star mr-1"></i>Sản phẩm nổi bật</span>`;
           
            if(state.activeVariant.stock > 0) {
                html += `<span class="text-green-600 font-semibold">Còn hàng: ${state.activeVariant.stock}</span>`;
            } else {
                html += `<span class="text-red-500 font-semibold">Phiên bản này tạm hết hàng</span>`;
            }
            infoContainer.innerHTML = html;
        } else if (productData.variants.length === 0) {
             infoContainer.innerHTML = '<span class="text-red-500 font-semibold">Sản phẩm hiện không có sẵn</span>';
        }
    }


    function updateActionButtons() {
        const isAvailable = state.activeVariant && state.activeVariant.stock > 0;
        actionButtons.forEach(btn => btn.disabled = !isAvailable);
    }
   
    function updateMainImage() {
        if (!mainImage) return;
        mainImage.src = state.activeVariant?.image || productData.default_image;
    }
   
    function updateVariantOptionsUI() {
        if (!variantForm) return;


        const swatchSelectedClasses = ['ring-2', 'ring-offset-2', 'ring-[#ff6c2f]'];
        const buttonSelectedClasses = ['border-[#ff6c2f]', 'bg-[#ff6c2f]', 'text-white'];
        const buttonNormalClasses = ['border-gray-300'];


        variantForm.querySelectorAll('.variant-option-button').forEach(button => {
            const attributeName = button.dataset.attributeName;
            const attributeValue = button.dataset.attributeValue;
            const isSelected = state.selectedOptions[attributeName] === attributeValue;


            if (button.classList.contains('color-swatch')) {
                button.classList.toggle(swatchSelectedClasses[0], isSelected);
                button.classList.toggle(swatchSelectedClasses[1], isSelected);
                button.classList.toggle(swatchSelectedClasses[2], isSelected);
            } else {
                button.classList.toggle(buttonSelectedClasses[0], isSelected);
                button.classList.toggle(buttonSelectedClasses[1], isSelected);
                button.classList.toggle(buttonSelectedClasses[2], isSelected);
                button.classList.toggle(buttonNormalClasses[0], !isSelected);
            }


            const tempSelection = { ...state.selectedOptions };
            if(!Object.keys(tempSelection).includes(attributeName)){
                tempSelection[attributeName] = attributeValue;
            }


            const isPossible = productData.variants.some(variant =>
                Object.entries(tempSelection).every(([key, val]) => {
                    if (key === attributeName) return true;
                    return variant.attributes[key] === val;
                }) && variant.attributes[attributeName] === attributeValue
            );


            button.disabled = !isPossible;
            button.classList.toggle('disabled:opacity-25', !isPossible);
            button.classList.toggle('disabled:cursor-not-allowed', !isPossible);
        });
    }


    function handleVariantClick(button) {
        if (!button || button.disabled) return;
        const attributeName = button.dataset.attributeName;
        const attributeValue = button.dataset.attributeValue;


        if (state.selectedOptions[attributeName] === attributeValue) {
            delete state.selectedOptions[attributeName];
            document.querySelector(`[data-variant-name-display="${attributeName}"]`).textContent = '';
        } else {
            state.selectedOptions[attributeName] = attributeValue;
            document.querySelector(`[data-variant-name-display="${attributeName}"]`).textContent = attributeValue;
        }
        render();
    }
   
    function setDefaultVariant() {
        if (productData.type !== 'variable' || productData.variants.length === 0) {
            render();
            return;
        }
       
        let defaultVariant = productData.variants.find(v => v.stock > 0);
        if (!defaultVariant) {
            defaultVariant = productData.variants[0];
        }


        if (defaultVariant) {
            state.selectedOptions = { ...defaultVariant.attributes };
            Object.entries(state.selectedOptions).forEach(([name, value]) => {
                 document.querySelector(`[data-variant-name-display="${name}"]`).textContent = value;
            });
        }
        render();
    }


    window.updateQuantity = (change) => {
        const maxStock = state.activeVariant ? state.activeVariant.stock : 0;
        let newQuantity = state.quantity + change;
        if (newQuantity >= 1 && (maxStock === 0 || newQuantity <= maxStock)) {
            state.quantity = newQuantity;
            quantityInput.value = state.quantity;
        }
    }


    window.addProductToCart = () => {
        if (!state.activeVariant) { showNotification('Vui lòng chọn đầy đủ thuộc tính sản phẩm.', 'error'); return; }
        if (state.activeVariant.stock <= 0) { showNotification('Phiên bản này đã hết hàng.', 'error'); return; }
       
        fetch('{{ route('carts.add') }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
            body: JSON.stringify({ product_id: {{ $product->id ?? 'null' }}, quantity: state.quantity, variant_id: state.activeVariant.id })
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            showNotification(data.message || (ok ? 'Đã thêm vào giỏ hàng' : 'Có lỗi xảy ra'), ok ? 'success' : 'error');
            if(ok) updateCartCount();
        })
        .catch(() => showNotification('Lỗi kết nối, vui lòng thử lại.', 'error'));
    }


    window.buyNow = () => {
        if (!state.activeVariant) { showNotification('Vui lòng chọn đầy đủ thuộc tính sản phẩm.', 'error'); return; }
        if (state.activeVariant.stock <= 0) { showNotification('Phiên bản này đã hết hàng.', 'error'); return; }
       
        fetch('{{ route('carts.add') }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
            body: JSON.stringify({ product_id: {{ $product->id ?? 'null' }}, quantity: state.quantity, variant_id: state.activeVariant.id })
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (ok) window.location.href = '{{ route('checkout.index') }}';
            else showNotification(data.message || 'Có lỗi xảy ra, không thể mua ngay.', 'error');
        })
        .catch(() => showNotification('Lỗi kết nối, vui lòng thử lại.', 'error'));
    }


    if (variantForm) {
        variantForm.addEventListener('click', (e) => handleVariantClick(e.target.closest('.variant-option-button')));
    }
    setDefaultVariant();


    window.changeMainImage = (src) => { if (mainImage) mainImage.src = src; }


    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        const colors = { info: 'bg-blue-500', success: 'bg-green-500', error: 'bg-red-500' };
        notification.className = `fixed top-5 right-5 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.classList.remove('translate-x-full'), 10);
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }


    function updateCartCount() {
        fetch('{{ route('carts.count') }}')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const countEl = document.querySelector('#cart-count');
                    if (countEl) {
                        countEl.textContent = data.count;
                        countEl.style.display = data.count > 0 ? 'flex' : 'none';
                    }
                }
            });
    }
});
</script>
@endpush

