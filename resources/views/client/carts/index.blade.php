@extends('client.layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gray-50 py-8">
  <div class="container mx-auto px-4">
    <nav class="text-sm text-gray-500 mb-6">
      <ol class="list-none p-0 inline-flex">
        <li class="flex items-center">
          <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#ff6c2f]">Trang chủ</a>
          <i class="fas fa-chevron-right mx-2"></i>
        </li>
        <li class="text-gray-700">Giỏ hàng</li>
      </ol>
    </nav>

    <div class="max-w-6xl mx-auto">
      <h1 class="text-3xl font-bold text-gray-800 mb-8">Giỏ hàng của bạn</h1>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow-md">
            @if(count($cartItems) > 0)
              <div class="flex items-center justify-between px-6 pt-6 pb-3 border-b border-gray-100">
                <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                  <input type="checkbox" id="select-all-items" class="w-4 h-4 text-[#ff6c2f] border-gray-300 rounded focus:ring-[#ff6c2f]">
                  <span>Chọn tất cả ({{ count($cartItems) }})</span>
                </label>
                <div class="flex items-center space-x-3">
                  <button type="button" id="delete-selected-btn" class="text-sm px-3 py-2 rounded bg-red-50 text-red-600 hover:bg-red-100 disabled:opacity-40" disabled>
                    Xóa đã chọn
                  </button>
                </div>
              </div>

              <div class="p-6" id="cart-items-wrapper">
                @foreach($cartItems as $item)
                  @php
                    // === TÍNH GIÁ ĐÚNG TRƯỜNG HỢP ===
                    $displayPrice = 0;
                    $stock = $item->productVariant->stock ?? $item->product->stock ?? 0;
                    if (!empty($item->productVariant)) {
                      $displayPrice = $item->productVariant->sale_price ?? $item->productVariant->price ?? 0;
                    } elseif (isset($item->price) && $item->price !== null) {
                      $displayPrice = $item->price;
                    } elseif (!$item->product->variants || $item->product->variants->count() === 0) {
                      $displayPrice = $item->product->sale_price ?? $item->product->price ?? 0;
                    } elseif (isset($item->variant_id) && $item->variant_id) {
                      $v = $item->product->variants?->firstWhere('id', $item->variant_id);
                      if ($v) $displayPrice = $v->sale_price ?? $v->price ?? 0;
                    }
                    // ẢNH: ưu tiên ảnh biến thể, fallback về ảnh sản phẩm chính
                    $imagePath = null;
                    $imageSource = 'none'; // Debug: để biết ảnh lấy từ đâu
                    
                    if (!empty($item->productVariant?->image)) {
                      $imagePath = asset('storage/' . ltrim($item->productVariant->image, '/'));
                      $imageSource = 'variant';
                    } elseif (!empty($item->product->image)) {
                      $imagePath = asset('uploads/products/' . ltrim($item->product->image, '/'));
                      $imageSource = 'product_main';
                    } elseif (!empty($item->product->productAllImages) && $item->product->productAllImages->count() > 0) {
                      $imgObj   = $item->product->productAllImages->first();
                      $imgField = $imgObj->image_path ?? $imgObj->image_url ?? $imgObj->image ?? null;
                      if ($imgField) {
                        $imagePath = asset('uploads/products/' . ltrim($imgField, '/'));
                        $imageSource = 'product_all_images';
                      }
                    } elseif (!empty($item->product->thumbnail)) {
                      $imagePath = asset('uploads/products/' . ltrim($item->product->thumbnail, '/'));
                      $imageSource = 'product_thumbnail';
                    }
                    $isOutOfStock = ($stock <= 0);
                  @endphp

                  <div
                    class="flex items-center justify-between border-b border-gray-200 py-4 {{ $loop->last ? 'border-b-0' : '' }} cart-item"
                    data-price="{{ is_numeric($displayPrice) ? $displayPrice : 0 }}"
                    data-quantity="{{ (int) $item->quantity }}"
                    data-id="{{ $item->id }}"
                  >
                    <div class="flex items-center space-x-4">
                      <input type="checkbox" class="item-checkbox w-4 h-4 text-[#ff6c2f] border-gray-300 rounded focus:ring-[#ff6c2f]" value="{{ $item->id }}" @if($isOutOfStock) disabled @endif>
                      <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 relative" 
                           title="Ảnh: {{ $imageSource }} - {{ $imagePath ? 'Có' : 'Không có' }}">
                        @if($imagePath)
                          <img src="{{ $imagePath }}"
                               alt="{{ $item->product->name }}"
                               class="w-full h-full object-cover transition-opacity duration-200"
                               loading="lazy"
                               onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}';this.classList.add('opacity-50');">
                        @else
                          <img src="{{ asset('client_css/images/placeholder.svg') }}" 
                               alt="No image" 
                               class="w-full h-full object-cover opacity-50">
                        @endif
                        @if($isOutOfStock)
                          <div class="absolute inset-0 bg-red-500 bg-opacity-20 flex items-center justify-center">
                            <span class="text-red-600 text-xs font-bold">Hết hàng</span>
                          </div>
                        @endif
                      </div>
                      <div>
                        <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                        @if($isOutOfStock)
                          <div class="text-sm text-red-600 font-semibold">Hết hàng</div>
                        @elseif(!empty($item->productVariant))
                          <div class="text-sm text-gray-500">
                            @foreach($item->productVariant->attributeValues as $attrValue)
                              {{ $attrValue->attribute->name }}: {{ $attrValue->value }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                          </div>
                        @elseif($item->product->variants && $item->product->variants->count() > 0 && $displayPrice == 0)
                          <div class="text-sm text-amber-600">Vui lòng chọn phân loại</div>
                        @endif
                        <div class="text-sm text-gray-500">
                          @if($displayPrice > 0)
                            {{ number_format($displayPrice, 0, ',', '.') }}₫
                          @else
                            Liên hệ
                          @endif
                        </div>
                      </div>
                    </div>

                    <div class="flex items-center space-x-4">
                                              @if($isOutOfStock)
                          <div class="text-red-600 font-bold text-base">Hết hàng</div>
                          <div class="text-xs text-gray-500 mt-1">Không thể thanh toán</div>
                        @else
                        <div class="flex items-center space-x-2">
                          <button
                            type="button"
                            onclick="updateQuantity('{{ $item->id }}', {{ max(1, (int) $item->quantity - 1) }})"
                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 {{ (int)$item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                            @if((int)$item->quantity <= 1) disabled @endif
                          >
                            <i class="fas fa-minus text-xs"></i>
                          </button>
                          <span class="w-8 text-center">{{ $item->quantity }}</span>
                          <button
                            type="button"
                            onclick="handleIncreaseQuantity('{{ $item->id }}', {{ (int) $item->quantity }}, {{ (int)($item->productVariant?->stock ?? 9999) }})"
                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 {{ (int)$item->quantity >= (int)($item->productVariant?->stock ?? 9999) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            @if((int)$item->quantity >= (int)($item->productVariant?->stock ?? 9999)) disabled @endif
                          >
                            <i class="fas fa-plus text-xs"></i>
                          </button>
                        </div>
                      @endif
                      <button type="button" onclick="removeFromCart('{{ $item->id }}')" class="text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="p-8 text-center">
                <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Giỏ hàng trống</h3>
                <p class="text-gray-500 mb-6">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                <a href="{{ route('home') }}" class="bg-[#ff6c2f] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#ff6c2f] transition">
                  Tiếp tục mua sắm
                </a>
              </div>
            @endif
          </div>
        </div>

        @if(count($cartItems) > 0)
          <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
              <h3 class="text-xl font-semibold mb-6">Tóm tắt đơn hàng</h3>

              <div class="space-y-4 mb-6">
                <div class="flex justify-between">
                  <span class="text-gray-600">Tạm tính:</span>
                  <span class="font-medium" id="subtotal">0₫</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Phí vận chuyển:</span>
                  <span class="font-medium" id="shipping-fee">Miễn phí</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Giảm giá:</span>
                  <span class="font-medium text-green-600" id="discount">-0₫</span>
                </div>
                <hr class="border-gray-200">
                <div class="flex justify-between text-lg font-bold">
                  <span>Tổng cộng:</span>
                  <span class="text-[#ff6c2f]" id="total">0₫</span>
                </div>
              </div>

              <div class="mb-6">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Mã giảm giá</label>
                  <div class="flex items-center gap-2 flex-wrap">
                      <input type="text" id="discount-code" placeholder="Nhập mã giảm giá"
                             class="flex-1 px-2 py-1 border border-gray-300 rounded-full focus:outline-none focus:border-[#ff6c2f] text-xs shadow-sm max-w-[140px]">
                      <button type="button" id="apply-coupon-btn" class="bg-[#ff6c2f] text-white px-2 py-1 rounded-full hover:bg-orange-500 transition flex items-center gap-1 text-xs shadow disabled:opacity-50" style="min-width: 60px; height: 28px;" disabled>
                          <i class="fas fa-check text-xs"></i> Áp dụng
                      </button>
                      <button type="button" id="toggle-coupon-list" onclick="toggleCouponListCart()" class="text-xs text-orange-600 underline">Danh sách</button>
                  </div>
                  <div id="cart-coupon-message" class="mt-1 text-xs"></div>
                  <div id="cart-available-coupons" class="hidden mt-2 space-y-2 max-h-44 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50 text-xs">
                      <!-- Danh sách mã sẽ render ở đây -->
                  </div>
              </div>

              <button type="button" class="w-full bg-[#ff6c2f] text-white py-3 rounded-lg font-semibold hover:bg-[#ff6c2f] transition mb-4 disabled:opacity-50 disabled:cursor-not-allowed" id="checkout-all-btn">
                Thanh toán tất cả
              </button>
              <button type="button" class="w-full bg-gray-800 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition mb-4 hidden" id="checkout-selected-btn">
                Thanh toán sản phẩm đã chọn
              </button>
              
              <div class="text-xs text-gray-500 text-center mb-4" id="checkout-status">
                <!-- Thông báo trạng thái sẽ hiển thị ở đây -->
              </div>

              <a href="{{ route('home') }}" class="block text-center text-[#ff6c2f] hover:underline">← Tiếp tục mua sắm</a>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
// ===== Helpers =====
const VND = n => (Number(n) || 0).toLocaleString('vi-VN') + '₫';
const getCsrf = () => (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || '';
function showNotification(msg, type='success'){
  const e=document.createElement('div');let bg='bg-green-500';
  if(type==='error')bg='bg-red-500';if(type==='info')bg='bg-blue-500';
  e.className=`fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full ${bg}`;
  e.textContent=msg;document.body.appendChild(e);
  setTimeout(()=>{e.style.transform='translateX(0)';},50);
  setTimeout(()=>{e.style.transform='translateX(100%)';setTimeout(()=>e.remove(),300);},3000);
}

const getCheckedItems = () => Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.closest('.cart-item'));
const getAllItems     = () => Array.from(document.querySelectorAll('.cart-item'));

// ===== TÍNH TỔNG (chỉ tính các item đã tick) =====
function calcSubtotal() {
  const items = getCheckedItems();
  if (items.length === 0) return 0;
  return items.reduce((sum, item) => {
    const price = parseFloat(item.dataset.price) || 0;
    const qty   = parseFloat(item.dataset.quantity) || 0;
    return sum + price * qty;
  }, 0);
}

function renderSummary() {
  const subtotal = calcSubtotal();
  const subtotalEl = document.getElementById('subtotal');
  const totalEl    = document.getElementById('total');
  const discountEl = document.getElementById('discount');
  const applyBtn   = document.getElementById('apply-coupon-btn');

  let applied = null, discount = 0;
  try { applied = JSON.parse(localStorage.getItem('appliedDiscount') || 'null'); } catch(e){}
  if (applied && applied.amount && subtotal > 0) {
    discount = Math.min(Number(applied.amount)||0, subtotal);
    discountEl.textContent = `-${discount.toLocaleString('vi-VN')}₫`;
  } else {
    discountEl.textContent = '-0₫';
  }

  subtotalEl.textContent = VND(subtotal);
  totalEl.textContent    = VND(Math.max(subtotal - discount, 0));
  if (applyBtn) applyBtn.disabled = subtotal <= 0;
}

// ===== API đơn lẻ =====
function updateQuantity(id, q){
  if (q<=0){ removeFromCart(id); return; }
  fetch(`/carts/${id}`,{
    method:'PUT',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':getCsrf()},
    body:JSON.stringify({quantity:q})
  })
    .then(r=>r.json())
    .then(d=>{ if(d.success) location.reload(); else showNotification(d.message||'Có lỗi','error');})
    .catch(()=>showNotification('Có lỗi','error'));
}
function removeFromCart(id){
  if(!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;
  fetch(`/carts/${id}`,{method:'DELETE',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':getCsrf()}})
    .then(r=>r.json()).then(d=>{ if(d.success) location.reload(); else showNotification(d.message||'Có lỗi','error');})
    .catch(()=>showNotification('Có lỗi','error'));
}

// ===== Mã giảm giá (chỉ cho item đã chọn) =====
function applyDiscountCode(){
  const input=document.getElementById('discount-code'); const code=input.value.trim();
  if(!code){showNotification('Vui lòng nhập mã giảm giá','error');return;}

  const ids=Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb=>cb.value);
  if(ids.length===0){ showNotification('Vui lòng chọn sản phẩm để áp mã','error'); return; }

  const subtotal = calcSubtotal();
  if(subtotal<=0){ showNotification('Giá trị đơn hàng là 0₫','error'); return; }

  fetch('/api/apply-coupon',{
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':getCsrf()},
    body:JSON.stringify({coupon_code:code, subtotal, item_ids:ids})
  })
    .then(r=>r.json()).then(data=>{
      if(!data.success){ showNotification(data.message||'Mã không hợp lệ','error'); return; }
      const discountAmount = Number(data.discount_amount)||0;
      localStorage.setItem('appliedDiscount', JSON.stringify({code, amount:discountAmount, details:data.coupon}));
      renderSummary(); showNotification(`Giảm ${discountAmount.toLocaleString('vi-VN')}₫`,'success');
      input.disabled=true; const x=document.createElement('button'); x.innerHTML='×'; x.className='ml-2 text-red-500 hover:text-red-700 font-bold'; x.onclick=()=>clearDiscountCode(false); input.parentNode.appendChild(x);
    })
    .catch(()=>showNotification('Lỗi tải mã giảm giá','error'));
}
function clearDiscountCode(silent=false){
  const input=document.getElementById('discount-code'); input.value=''; input.disabled=false;
  document.getElementById('discount').textContent='-0₫';
  localStorage.removeItem('appliedDiscount');
  const x=input.parentNode.querySelector('button:last-child'); if(x&&x.innerHTML==='×') x.remove();
  renderSummary(); if(!silent) showNotification('Đã xóa mã giảm giá','info');
}

// ===== Đếm số lượng =====
function updateCartCount(){
  fetch('/client/carts/count').then(r=>r.json()).then(d=>{
    if(d.success){
      document.querySelectorAll('.cart-count').forEach(el=>{
        el.textContent=d.count;
        if(d.count>0){el.classList.remove('hidden'); el.style.display='flex';}
        else{el.classList.add('hidden'); el.style.display='none';}
      });
    }
  }).catch(()=>{});
}

// ===== XÓA nhiều =====
async function deleteSelected(){
  const items = getCheckedItems();
  if(items.length === 0){ showNotification('Vui lòng chọn sản phẩm để xóa','error'); return; }
  if(!confirm(`Xóa ${items.length} sản phẩm đã chọn?`)) return;

  const ids = items.map(el => el.dataset.id);

  // Thử bulk endpoint (nếu backend có)
  try{
    const res = await fetch('/carts/bulk-delete',{
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':getCsrf()},
      body:JSON.stringify({ids})
    });
    const data = await res.json();
    if(data?.success){ location.reload(); return; }
  }catch(e){ /* fallback */ }

  // Fallback: xóa từng item
  try{
    for(const id of ids){
      await fetch(`/carts/${id}`,{
        method:'DELETE',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':getCsrf()}
      });
    }
    location.reload();
  }catch(e){
    showNotification('Có lỗi khi xóa sản phẩm','error');
  }
}
// ===== Thanh toán =====
function proceedToCheckout(){ // thanh toán tất cả
  // Lấy tất cả sản phẩm trong giỏ còn hàng (không bị disabled)
  const items = Array.from(document.querySelectorAll('.cart-item'));
  const availableItems = items.filter(item => {
    const checkbox = item.querySelector('.item-checkbox');
    return checkbox && !checkbox.disabled;
  });
  
  if(availableItems.length === 0){
    showNotification('Không có sản phẩm nào còn hàng để thanh toán','error');
    return;
  }
  
  const ids = availableItems.map(item => item.dataset.id);
  localStorage.setItem('checkout_selected_items', JSON.stringify(ids));
  window.location.href = '{{ route("checkout.index") }}?selected=' + ids.join(',');
}

function proceedToCheckoutSelected(){ // thanh toán theo chọn
  const checkedBoxes = Array.from(document.querySelectorAll('.item-checkbox:checked'));
  const availableCheckedBoxes = checkedBoxes.filter(cb => !cb.disabled);
  
  if(availableCheckedBoxes.length === 0){
    showNotification('Vui lòng chọn sản phẩm còn hàng để thanh toán','error');
    return;
  }
  
  const ids = availableCheckedBoxes.map(cb => cb.value);
  localStorage.setItem('checkout_selected_items', JSON.stringify(ids));
  window.location.href = '{{ route("checkout.index") }}?selected=' + ids.join(',');
}

// ===== Chọn / trạng thái nút =====
function initSelectionFeatures(){
  const selectAll=document.getElementById('select-all-items');
  const cbs=document.querySelectorAll('.item-checkbox');
  const del=document.getElementById('delete-selected-btn');
  const btnAll=document.getElementById('checkout-all-btn');
  const btnSel=document.getElementById('checkout-selected-btn');

  if(!selectAll) return;
  
  // Nếu không có sản phẩm nào, ẩn các nút thanh toán
  if(cbs.length === 0) {
    if(btnAll) btnAll.style.display = 'none';
    if(btnSel) btnSel.style.display = 'none';
    const statusEl = document.getElementById('checkout-status');
    if(statusEl) statusEl.textContent = 'Giỏ hàng trống';
    return;
  }

  function refresh(){
    const checkedCount = Array.from(cbs).filter(cb=>cb.checked).length;
    const allChecked   = checkedCount === cbs.length && cbs.length > 0;
    const anyChecked   = checkedCount > 0;

    del.disabled = !anyChecked;

    // Nút thanh toán:
    // - Chọn TẤT CẢ → chỉ hiện "Thanh toán tất cả" & enable
    // - Chọn một phần → hiện "Thanh toán sản phẩm đã chọn"
    // - Chưa chọn gì → chỉ hiện "Thanh toán tất cả" nhưng disable
    const availableItems = Array.from(cbs).filter(cb => !cb.disabled);
    const hasAvailableItems = availableItems.length > 0;
    const statusEl = document.getElementById('checkout-status');
    
    if(allChecked && hasAvailableItems){
      btnAll.classList.remove('hidden'); btnAll.disabled = false;
      btnSel.classList.add('hidden');
      if(statusEl) {
        statusEl.textContent = `Đã chọn ${checkedCount} sản phẩm còn hàng`;
        statusEl.className = 'text-xs text-gray-500 text-center mb-4';
      }
    } else if(anyChecked){
      btnAll.classList.add('hidden');
      btnSel.classList.remove('hidden');
      if(statusEl) {
        statusEl.textContent = `Đã chọn ${checkedCount} sản phẩm để thanh toán`;
        statusEl.className = 'text-xs text-gray-500 text-center mb-4';
      }
    } else {
      btnAll.classList.remove('hidden'); 
      btnAll.disabled = !hasAvailableItems;
      btnSel.classList.add('hidden');
      if(statusEl) {
        if(hasAvailableItems) {
          statusEl.textContent = 'Chưa chọn sản phẩm nào';
          statusEl.className = 'text-xs text-gray-500 text-center mb-4';
        } else {
          statusEl.textContent = 'Tất cả sản phẩm đều hết hàng';
          statusEl.className = 'text-xs text-red-500 text-center mb-4';
        }
      }
    }

    // Mỗi lần thay đổi lựa chọn: reset mã và tính lại tổng
    clearDiscountCode(true);
    renderSummary();
  }

  selectAll.addEventListener('change', ()=>{
    cbs.forEach(cb=>{ if (!cb.disabled) cb.checked=selectAll.checked; });
    refresh();
  });
  cbs.forEach(cb=>cb.addEventListener('change', ()=>{
    selectAll.checked = Array.from(cbs).every(x=>x.checked);
    refresh();
  }));

  // Không tick mặc định
  selectAll.checked = false;
  cbs.forEach(cb => cb.checked = false);
  refresh();
}

function showToast(msg, type = 'error') {
  const toast = document.createElement('div');
  toast.className = `fixed top-6 right-6 z-50 px-5 py-3 rounded-lg text-white font-medium shadow-lg transition-all duration-300 ${type === 'error' ? 'bg-red-500' : 'bg-green-500'}`;
  toast.textContent = msg;
  document.body.appendChild(toast);
  setTimeout(() => { toast.style.opacity = 0; }, 1800);
  setTimeout(() => { toast.remove(); }, 2200);
}
function handleIncreaseQuantity(id, current, stock) {
  if (current < stock) {
    updateQuantity(id, current + 1);
  } else {
    showToast('Đã đạt tối đa tồn kho!');
  }
}

function toggleCouponListCart() {
    const box = document.getElementById('cart-available-coupons');
    const btn = document.getElementById('toggle-coupon-list');
    if (!box || !btn) return;
    if (box.classList.contains('hidden')) {
        loadAvailableCouponsCart();
        box.classList.remove('hidden');
        btn.textContent = 'Ẩn';
    } else {
        box.classList.add('hidden');
        btn.textContent = 'Danh sách';
    }
}
function loadAvailableCouponsCart() {
    const box = document.getElementById('cart-available-coupons');
    if (!box) return;
    const subtotal = calcSubtotal();
    fetch(`/api/coupons?subtotal=${subtotal}`).then(r => r.json()).then(data => {
        if (!data.success) {
            box.innerHTML = '<p class="text-red-500">Lỗi tải</p>';
            return;
        }
        if (!Array.isArray(data.coupons) || data.coupons.length === 0) {
            box.innerHTML = '<p class="text-gray-500">Không có mã phù hợp</p>';
            return;
        }
        const applied = (() => {
            try {
                const s = JSON.parse(localStorage.getItem('appliedDiscount'));
                return s && s.code ? s.code : null;
            } catch {
                return null;
            }
        })();
        box.innerHTML = data.coupons.map(c => {
            const can = c.eligible;
            const cls = can ? 'border border-green-300 bg-white hover:border-orange-500 cursor-pointer transition' :
                'border border-gray-200 bg-gray-100 opacity-60 cursor-not-allowed';
            const selectedCls = applied && applied.toUpperCase() === c.code.toUpperCase() ?
                'border-2 border-orange-500 coupon-selected shadow' : '';
            return `<div class="coupon-item flex items-center justify-between rounded-lg p-2 mb-1 ${cls} ${selectedCls}" data-code="${c.code}" data-eligible="${can}">
                <div class='flex flex-col'>
                    <span class='font-mono text-xs font-bold text-orange-600 group-hover:underline'>${c.code}</span>
                    <span class='text-[11px] text-gray-500'>${c.discount_type === 'percent' ? `Giảm ${c.value}%` : `Giảm ${Number(c.value).toLocaleString()}₫`}</span>
                    ${c.reason ? `<span class='text-[10px] text-red-500 mt-1'>${c.reason}</span>` : ''}
                </div>
                <div>
                    ${applied && applied.toUpperCase() === c.code.toUpperCase() ? '<i class="fas fa-check-circle text-green-500 text-base"></i>' : (can ? '<i class="fas fa-tag text-orange-400 text-xs"></i>' : '')}
                </div>
            </div>`;
        }).join('');
        box.querySelectorAll('.coupon-item').forEach(div => {
            div.addEventListener('click', () => {
                if (div.dataset.eligible !== 'true') return;
                box.querySelectorAll('.coupon-item.coupon-selected').forEach(el => el.classList.remove('coupon-selected', 'border-orange-500', 'border-2', 'shadow'));
                div.classList.add('coupon-selected', 'border-orange-500', 'border-2', 'shadow');
                const input = document.getElementById('discount-code');
                const msg = document.getElementById('cart-coupon-message');
                if (input) input.value = div.dataset.code;
                if (msg) {
                    msg.textContent = 'Đã chọn mã, bấm Áp dụng để xác nhận';
                    msg.className = 'mt-1 text-xs text-gray-600';
                }
            });
        });
    }).catch(() => {
        box.innerHTML = '<p class="text-red-500">Lỗi tải mã</p>';
    });
}

// ===== Init =====
document.addEventListener('DOMContentLoaded', ()=>{
  renderSummary();
  updateCartCount();
  initSelectionFeatures();

  // Gắn handler: CHẶN submit/reload
  document.getElementById('apply-coupon-btn')?.addEventListener('click', (e)=>{ e.preventDefault(); e.stopPropagation(); applyDiscountCode(); });
  document.getElementById('delete-selected-btn')?.addEventListener('click', (e)=>{ e.preventDefault(); e.stopPropagation(); deleteSelected(); });
  document.getElementById('checkout-all-btn')?.addEventListener('click', (e)=>{ e.preventDefault(); e.stopPropagation(); proceedToCheckout(); });
  document.getElementById('checkout-selected-btn')?.addEventListener('click', (e)=>{ e.preventDefault(); e.stopPropagation(); proceedToCheckoutSelected(); });
});
</script>
@endsection
