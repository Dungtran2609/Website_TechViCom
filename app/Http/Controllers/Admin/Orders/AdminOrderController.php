<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\ShippingMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdminOrderController extends Controller
{
    /**
     * Hiển thị danh sách các đơn hàng.
     */
    public function index(Request $request)
    {
        $orders = Order::with(['user:id,name'])
            ->when($request->search, function ($query, $search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(15);

        // Truyền trực tiếp collection $orders sang view, view sẽ tự xử lý
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Hiển thị chi tiết một đơn hàng.
     */
    public function show(Order $order) // Sử dụng Route Model Binding
    {
        // Eager load các mối quan hệ cần thiết để tối ưu truy vấn
        $order->load('user', 'orderItems.productVariant.product', 'shippingMethod', 'coupon');

        // Truyền trực tiếp đối tượng $order, accessor trong Model sẽ tự động xử lý
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Hiển thị form để chỉnh sửa đơn hàng.
     */
    public function edit(Order $order) // Sử dụng Route Model Binding
    {
        $order->load('user', 'orderItems.productVariant.product', 'shippingMethod', 'coupon');

        // Lấy dữ liệu phụ trợ cho form
        $shippingMethods = ShippingMethod::all();
        $coupons = Coupon::where('status', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        // Lấy danh sách trạng thái từ hằng số trong Model để truyền sang view
        $orderStatuses = Order::ORDER_STATUSES;
        $paymentStatuses = Order::PAYMENT_STATUSES;

        return view('admin.orders.edit', compact(
            'order', 'shippingMethods', 'coupons', 'orderStatuses', 'paymentStatuses'
        ));
    }

    /**
     * Cập nhật thông tin đơn hàng từ form của admin.
     * Đổi tên từ updateOrders -> update cho chuẩn RESTful.
     */
    public function update(Request $request, Order $order) // Sử dụng Route Model Binding
    {
        // Sử dụng hằng số từ Model để validation, giúp code nhất quán và dễ bảo trì
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', array_keys(Order::ORDER_STATUSES)),
            'payment_status' => 'nullable|in:' . implode(',', array_keys(Order::PAYMENT_STATUSES)),
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        // --- LOGIC NGHIỆP VỤ QUAN TRỌNG ---

        // 1. Ngăn chặn admin thay đổi trạng thái thanh toán của đơn hàng online (vietqr)
        if ($order->payment_method === 'vietqr') {
            unset($validatedData['payment_status']); // Xóa trường này khỏi dữ liệu cập nhật
        }

        // 2. Ngăn chặn admin xử lý đơn hàng online khi chưa được xác nhận thanh toán
        if ($order->payment_method === 'vietqr' && $order->payment_status !== 'paid') {
            if (in_array($validatedData['status'], ['processing', 'shipped'])) {
                return back()->with('error', 'Không thể xử lý đơn hàng thanh toán online khi chưa được xác nhận thanh toán.');
            }
        }

        // --- CẬP NHẬT DỮ LIỆU ---

        // Cập nhật trạng thái xử lý đơn hàng
        $order->status = $validatedData['status'];

        // Tự động cập nhật ngày giao hàng nếu trạng thái là "shipped" và chưa có ngày
        if ($validatedData['status'] === 'shipped' && !$order->shipped_at) {
            $order->shipped_at = now();
        }

        // Chỉ cho phép cập nhật trạng thái thanh toán nếu là đơn COD
        if (isset($validatedData['payment_status']) && $order->payment_method === 'cod') {
            $order->payment_status = $validatedData['payment_status'];

            // Tự động chuyển trạng thái đơn hàng thành 'delivered' khi admin xác nhận đã thu tiền COD
            if($validatedData['payment_status'] === 'paid' && $order->status !== 'delivered'){
                $order->status = 'delivered';
            }
        }

        $order->save();

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Đơn hàng đã được cập nhật thành công.');
    }

    /**
     * Xóa mềm một đơn hàng.
     */
    public function destroy(Order $order) // Sử dụng Route Model Binding
    {
        $allowedStatusesToDelete = ['cancelled', 'returned', 'delivered'];

        if (!in_array($order->status, $allowedStatusesToDelete)) {
            return back()->with('error', 'Chỉ có thể xóa đơn hàng đã hoàn tất (Đã nhận, Đã hủy, Đã trả hàng).');
        }

        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được chuyển vào thùng rác.');
    }

    /**
     * Hiển thị danh sách các đơn hàng đã bị xóa mềm.
     */
    public function trashed()
    {
        $trashedOrders = Order::onlyTrashed()
            ->with(['user:id,name'])
            ->latest()
            ->paginate(15);

        return view('admin.orders.trashed', compact('trashedOrders'));
    }

    /**
     * Khôi phục một đơn hàng đã bị xóa mềm.
     */
    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();
        return redirect()->route('admin.orders.trashed')->with('success', 'Đơn hàng đã được khôi phục thành công.');
    }

    /**
     * Xóa vĩnh viễn một đơn hàng.
     */
    public function forceDelete($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($order) {
            $order->orderItems()->delete();
            $order->forceDelete();
        });

        return redirect()->route('admin.orders.trashed')->with('success', 'Đơn hàng đã bị xóa vĩnh viễn.');
    }

    /**
     * Hiển thị danh sách yêu cầu trả hàng/hủy đơn.
     */
    public function returnsIndex(Request $request)
    {
        $returns = OrderReturn::with(['order.user'])
            ->latest()
            ->paginate(15);

        return view('admin.orders.returns.index', compact('returns'));
    }

    /**
     * Xử lý yêu cầu trả hàng/hủy đơn (Phê duyệt hoặc Từ chối).
     */
    public function processReturn(Request $request, OrderReturn $return) // Sử dụng Route Model Binding
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Cập nhật trạng thái yêu cầu trả hàng
        $return->status = $validated['action'] === 'approve' ? 'approved' : 'rejected';
        $return->processed_at = now();
        $return->admin_note = $validated['admin_note'];

        // Nếu phê duyệt, đồng bộ trạng thái của đơn hàng gốc
        if ($validated['action'] === 'approve') {
            $order = $return->order;

            // Xác định trạng thái mới cho đơn hàng
            $newOrderStatus = null;
            if ($return->type === 'cancel' && $order->status === 'pending') {
                $newOrderStatus = 'cancelled';
            } elseif ($return->type === 'return' && $order->status === 'delivered') {
                $newOrderStatus = 'returned';
            }

            if ($newOrderStatus) {
                $order->status = $newOrderStatus;
                $order->save();
            } else {
                return back()->with('error', 'Không thể xử lý yêu cầu dựa trên trạng thái hiện tại của đơn hàng.');
            }
        }

        $return->save();
        $message = $validated['action'] === 'approve' ? 'đã được phê duyệt.' : 'đã bị từ chối.';
        return redirect()->route('admin.orders.returns.index')->with('success', "Yêu cầu #{$return->id} $message");
    }

    /**
     * Helper function để tính toán giảm giá từ coupon.
     */
    private function calculateCouponDiscount($coupon, $orderTotal)
    {
        if (!$coupon || !$coupon->status || now()->isBefore($coupon->start_date) || now()->isAfter($coupon->end_date)) {
            return 0;
        }

        if ($coupon->min_order_value && $orderTotal < $coupon->min_order_value) {
            return 0;
        }

        if ($coupon->max_order_value && $orderTotal > $coupon->max_order_value) {
            return 0;
        }

        if ($coupon->discount_type === 'percentage') {
            $discount = ($orderTotal * $coupon->value) / 100;
            return ($coupon->max_discount_amount && $discount > $coupon->max_discount_amount)
                ? $coupon->max_discount_amount
                : $discount;
        }

        return $coupon->value;
    }
}
