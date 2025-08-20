<?php

namespace App\Http\Controllers\Client\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientAccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Lấy đơn hàng gần đây
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Lấy địa chỉ
        $addresses = UserAddress::where('user_id', $user->id)
            ->orderBy('is_default', 'desc')
            ->get();

        return view('client.accounts.index', compact('user', 'recentOrders', 'addresses'));
    }

    public function orders()
    {
        $orders = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.accounts.orders', compact('orders'));
    }

    public function orderDetail($id)
    {
        $order = Order::with(['orderItems.product.productAllImages', 'orderItems.productVariant.attributeValues.attribute'])
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        return view('client.orders.show', compact('order'));
    }
    public function cancelOrder($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            // Trả về lỗi dưới dạng JSON
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng hoặc đơn hàng không còn ở trạng thái "Chờ xử lý".'
            ], 404); // 404 Not Found
        }

        $clientNote = request('client_note', '');
        try {
            \App\Models\OrderReturn::create([
                'order_id' => $order->id,
                'type' => 'cancel',
                'reason' => 'Khách hủy',
                'client_note' => $clientNote,
                'status' => 'pending',
                'requested_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Trả về lỗi server dưới dạng JSON
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống khi gửi yêu cầu hủy. Vui lòng thử lại.'
            ], 500); // 500 Internal Server Error
        }

        // Trả về thành công dưới dạng JSON
        return response()->json([
            'success' => true,
            'message' => 'Yêu cầu hủy đơn hàng đã được gửi thành công. Admin sẽ duyệt yêu cầu này.'
        ]);
    }




    public function profile()
    {
        $user = Auth::user();
        return view('client.accounts.profile', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('client.accounts.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone_number' => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other'
        ]);

        $user = Auth::user();

        User::where('id', $user->id)->update($request->only(['name', 'email', 'phone_number', 'birthday', 'gender']));

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công');
    }

    public function changePassword()
    {
        return view('client.accounts.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công');
    }

    public function addresses()
    {
        $addresses = UserAddress::where('user_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->get();
        $defaultAddress = $addresses->first();
        return view('client.accounts.addresses', compact('addresses', 'defaultAddress'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'is_default' => 'boolean'
        ], [
            'recipient_name.required' => 'Vui lòng nhập tên người nhận.',
            'recipient_name.max' => 'Tên người nhận tối đa 255 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.max' => 'Số điện thoại tối đa 20 ký tự.',
            'address_line.required' => 'Vui lòng nhập địa chỉ chi tiết.',
            'address_line.max' => 'Địa chỉ chi tiết tối đa 500 ký tự.',
            'city.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'district.required' => 'Vui lòng chọn quận/huyện.',
            'ward.required' => 'Vui lòng chọn phường/xã.',
        ]);

        if ($request->is_default) {
            // Bỏ default của địa chỉ khác
            UserAddress::where('user_id', Auth::id())
                ->update(['is_default' => false]);
        }

        UserAddress::create([
            'user_id' => Auth::id(),
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'address_line' => $request->address_line,
            'city' => $request->city, // lấy từ input hidden city_name
            'district' => $request->district, // lấy từ input hidden district_name
            'ward' => $request->ward, // lấy từ input hidden ward_name
            'is_default' => $request->is_default ?? false
        ]);

        return redirect()->back()->with('success', 'Thêm địa chỉ thành công');
    }

    public function updateAddress(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'is_default' => 'boolean'
        ]);
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $address = UserAddress::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        if ($request->is_default) {
            // Bỏ default của địa chỉ khác
            UserAddress::where('user_id', Auth::id())
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }
        $address->update($request->only(['recipient_name', 'phone', 'address_line', 'city', 'district', 'ward', 'is_default']));
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật địa chỉ thành công'
            ]);
        }
        return redirect()->back()->with('success', 'Cập nhật địa chỉ thành công');
    }

    public function editAddress($id)
    {
        $address = UserAddress::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'address' => $address
        ]);
    }

    public function deleteAddress($id)
    {
        $address = UserAddress::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Địa chỉ không tồn tại'
            ], 404);
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa địa chỉ thành công'
        ]);
    }

    public function setDefaultAddress($id)
    {
        $userId = Auth::id();
        $address = \App\Models\UserAddress::where('user_id', $userId)->where('id', $id)->first();
        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Địa chỉ không tồn tại.'
            ], 404);
        }
        // Bỏ mặc định các địa chỉ khác
        \App\Models\UserAddress::where('user_id', $userId)->update(['is_default' => false]);
        // Đặt địa chỉ này làm mặc định
        $address->is_default = true;
        $address->save();
        return response()->json([
            'success' => true,
            'message' => 'Đã đặt địa chỉ làm mặc định.'
        ]);
    }
}
