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
                     ->firstOrFail();

        $order->update(['status' => 'cancelled']);

        return response()->json(['success' => true, 'message' => 'Đơn hàng đã được hủy thành công']);
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
            'phone' => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other'
        ]);

        $user = Auth::user();
        
        User::where('id', $user->id)->update($request->only(['name', 'email', 'phone', 'birthday', 'gender']));

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

        return view('client.accounts.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'is_default' => 'boolean'
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
            'address' => $request->address,
            'is_default' => $request->is_default ?? false
        ]);

        return redirect()->back()->with('success', 'Thêm địa chỉ thành công');
    }

    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'is_default' => 'boolean'
        ]);

        $address = UserAddress::where('user_id', Auth::id())
                              ->where('id', $id)
                              ->firstOrFail();

        if ($request->is_default) {
            // Bỏ default của địa chỉ khác
            UserAddress::where('user_id', Auth::id())
                       ->where('id', '!=', $id)
                       ->update(['is_default' => false]);
        }

        $address->update($request->only(['recipient_name', 'phone', 'address', 'is_default']));

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
}
