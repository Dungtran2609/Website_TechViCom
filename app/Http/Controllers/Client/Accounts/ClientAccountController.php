<?php

namespace App\Http\Controllers\Client\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function orders(Request $request)
    {
        $user   = Auth::user();
        $search = trim((string) $request->input('q', ''));
        $status = $request->input('status', 'all');



        // Thứ tự trạng thái để ORDER BY FIELD (chỉ các trạng thái có thực trong DB)
        $statusOrder = ['pending', 'shipped', 'delivered', 'received', 'cancelled', 'returned'];

        $query = Order::with(['orderItems.productVariant.product', 'returns'])
            ->where('user_id', $user->id);

        // Lọc theo trạng thái (DB-side)
        if ($status !== 'all' && in_array($status, $statusOrder, true)) {
            $query->where('status', $status);
            // Log::info('Filtering by status', ['status' => $status]);
        } else {
            // Log::info('No status filter applied', ['status' => $status]);
        }

        // Tìm kiếm: DH000123 / ID / tên sản phẩm
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                // "DH000123" -> lấy 123
                if (preg_match('/^DH0*([0-9]+)$/i', $search, $m)) {
                    $q->where('id', (int) $m[1]);
                    return;
                }

                // Toàn số: id chính xác hoặc prefix id
                if (ctype_digit($search)) {
                    $q->where('id', (int) $search)
                        ->orWhereRaw('CAST(id AS CHAR) LIKE ?', [$search . '%']);
                    return;
                }

                // Tên sản phẩm: tìm theo name_product trong order_items
                $q->orWhereHas('orderItems', function ($oi) use ($search) {
                    $oi->where('name_product', 'LIKE', '%' . $search . '%');
                });
            });
        }

        // Sắp xếp theo trạng thái rồi thời gian tạo (DB-side)
        $orders = $query
            ->orderByRaw("FIELD(status, '" . implode("','", $statusOrder) . "'), created_at DESC")
            ->paginate(10)
            ->withQueryString();



        // (Tuỳ view cần) số lượng theo từng trạng thái cho badge/tabs
        $counts = Order::where('user_id', $user->id)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();
        $counts['all'] = Order::where('user_id', $user->id)->count();
        


        return view('client.accounts.orders', [
            'orders' => $orders,
            'status' => $status,
            'search' => $search,
            'counts' => $counts, // nếu muốn hiển thị badge số lượng ở tab
        ]);
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
            
            // Cộng lại tồn kho khi hủy đơn hàng (ngay cả khi cần phê duyệt)
            \App\Http\Controllers\Client\Checkouts\ClientCheckoutController::releaseStockStatic($order);
            
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
            'gender' => 'nullable|in:male,female,other',
            'image_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $user = Auth::user();
        $userData = $request->only(['name', 'email', 'phone_number', 'birthday', 'gender']);

        // Xử lý upload ảnh đại diện
        if ($request->hasFile('image_profile') && $request->file('image_profile')->isValid()) {
            // Xóa ảnh cũ nếu có
            if ($user->image_profile && Storage::disk('public')->exists($user->image_profile)) {
                Storage::disk('public')->delete($user->image_profile);
            }
            
            // Lưu ảnh mới
            $userData['image_profile'] = $request->file('image_profile')->store('profiles', 'public');
        }

        User::where('id', $user->id)->update($userData);

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
            'phone' => ['required', 'string', 'regex:/^0\d{9}$/'],
            'address_line' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'is_default' => 'boolean'
        ], [
            'recipient_name.required' => 'Vui lòng nhập tên người nhận.',
            'recipient_name.max' => 'Tên người nhận tối đa 255 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại phải bắt đầu bằng số 0 và gồm 10 số.',
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
        $validator = Validator::make($request->all(), [
            'recipient_name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^0\d{9}$/'],
            'address_line' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'is_default' => 'boolean'
        ], [
            'recipient_name.required' => 'Vui lòng nhập tên người nhận.',
            'recipient_name.max' => 'Tên người nhận tối đa 255 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại phải bắt đầu bằng số 0 và gồm 10 số.',
            'address_line.required' => 'Vui lòng nhập địa chỉ chi tiết.',
            'address_line.max' => 'Địa chỉ chi tiết tối đa 500 ký tự.',
            'city.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'district.required' => 'Vui lòng chọn quận/huyện.',
            'ward.required' => 'Vui lòng chọn phường/xã.',
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

        // Kiểm tra xem địa chỉ có phải là mặc định không
        if ($address->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa địa chỉ mặc định. Vui lòng đặt địa chỉ khác làm mặc định trước khi xóa.'
            ], 400);
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
