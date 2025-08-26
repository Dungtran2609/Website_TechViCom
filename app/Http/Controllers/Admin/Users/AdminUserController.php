<?php
namespace App\Http\Controllers\Admin\Users;


use App\Models\Role;
use App\Models\User;
use App\Models\UserAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Requests\StoreUserAddressRequest;
use App\Http\Requests\UpdateUserAddressRequest;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index()
    {
        $query = User::with('roles');

        $search = request('search');
        $roleId = request('role');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleId) {
            $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('roles.id', $roleId);
            });
        }

        $users = $query->orderByDesc('id')->paginate(10)->appends(['search' => $search, 'role' => $roleId]);
        $roles = \App\Models\Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        $imagePath = $request->hasFile('image_profile') && $request->file('image_profile')->isValid()
            ? $request->file('image_profile')->store('profiles', 'public') : null;
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'is_active' => $request->is_active,
            'image_profile' => $imagePath,
        ]);

        $user->roles()->sync($request->roles);

        if ($request->filled(['address_line', 'ward', 'district', 'city'])) {
            $user->addresses()->create([
                'address_line' => $request->address_line,
                'ward' => $request->ward,
                'district' => $request->district,
                'city' => $request->city,
                'is_default' => true,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Tài khoản đã được tạo thành công.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $addresses = $user->addresses;
        return view('admin.users.edit', compact('user', 'roles', 'addresses'));
    }

    public function update(UserRequest $request, User $user)
    {
        // Lấy danh sách tên vai trò của người dùng
        $userRoleNames = $user->roles->pluck('name')->toArray();

        // ---- LOGIC ĐẶC BIỆT CHO VAI TRÒ 'USER' HOẶC 'CUSTOMER' ----
        if (count($userRoleNames) === 1 && (in_array('user', $userRoleNames) || in_array('customer', $userRoleNames))) {

            // Bước 1: Kiểm tra xem có bất kỳ nỗ lực thay đổi thông tin bị cấm hay không.
            $forbiddenChangeAttempted = false;

            // Danh sách các trường thông tin bị cấm chỉnh sửa
            $forbiddenFields = ['name', 'email', 'phone_number', 'birthday', 'gender', 'address_line', 'ward', 'district', 'city'];

            foreach ($forbiddenFields as $field) {
                // So sánh giá trị từ request với giá trị hiện tại trong DB
                // Dùng (string) để xử lý trường hợp một bên là null và một bên là chuỗi rỗng
                if ((string)$request->input($field) !== (string)$user->{$field}) {
                    $forbiddenChangeAttempted = true;
                    break; // Tìm thấy một thay đổi bị cấm, không cần kiểm tra thêm
                }
            }

            // Kiểm tra riêng cho mật khẩu và ảnh đại diện
            if (!$forbiddenChangeAttempted && ($request->filled('password') || $request->hasFile('image_profile'))) {
                $forbiddenChangeAttempted = true;
            }

            // Nếu phát hiện nỗ lực thay đổi thông tin bị cấm, trả về lỗi ngay lập tức.
            if ($forbiddenChangeAttempted) {
                return redirect()->back()->with('error', 'Không thể sửa tài khoản có vai trò là khách hàng.');
            }

            // Bước 2: Nếu không có thay đổi nào bị cấm, hãy kiểm tra xem có thay đổi nào được phép không.

            // So sánh trạng thái
            // Dùng boolean() để chuyển đổi các giá trị '0', '1', 'true', 'false' thành boolean
            $statusChanged = $request->boolean('is_active') !== $user->is_active;

            // So sánh vai trò (đây là cách so sánh mảng chính xác nhất)
            $currentRoles = $user->roles->pluck('id')->sort()->values()->all();
            $newRoles = collect($request->input('roles', []))->map(fn($id) => (int)$id)->sort()->values()->all();
            $rolesChanged = $currentRoles !== $newRoles;

            // Nếu có ít nhất một thay đổi được phép (trạng thái hoặc vai trò)
            if ($statusChanged || $rolesChanged) {
                // Thực hiện cập nhật
                $user->update(['is_active' => $request->is_active]);
                $user->roles()->sync($request->roles);

                return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật trạng thái và vai trò của tài khoản thành công.');
            } else {
                // Nếu không có thay đổi nào được thực hiện (cả cấm và cho phép), vẫn báo lỗi theo yêu cầu.
                return redirect()->back()->with('error', 'Không có thông tin nào được thay đổi.');
            }
        } else {
            // ---- LOGIC CŨ CHO CÁC VAI TRÒ KHÁC (ADMIN, MANAGER...) ----
            $userData = $request->only(['name', 'email', 'phone_number', 'birthday', 'gender', 'is_active']);

            if ($request->password) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('image_profile') && $request->file('image_profile')->isValid()) {
                if ($user->image_profile && Storage::disk('public')->exists($user->image_profile)) {
                    Storage::disk('public')->delete($user->image_profile);
                }
                $userData['image_profile'] = $request->file('image_profile')->store('profiles', 'public');
            }

            $user->update($userData);
            $user->roles()->sync($request->roles);

            if ($request->filled(['address_line', 'ward', 'district', 'city'])) {
                $defaultAddress = $user->addresses->where('is_default', 1)->first() ?? $user->addresses->first();

                if ($defaultAddress) {
                    $defaultAddress->update([
                        'address_line' => $request->address_line,
                        'ward' => $request->ward,
                        'district' => $request->district,
                        'city' => $request->city,
                        'is_default' => $request->is_default == 1,
                    ]);
                } else {
                    $user->addresses()->create([
                        'address_line' => $request->address_line,
                        'ward' => $request->ward,
                        'district' => $request->district,
                        'city' => $request->city,
                        'is_default' => true,
                    ]);
                }
            }

            return redirect()->route('admin.users.index')->with('success', 'Tài khoản đã được cập nhật thành công.');
        }
    }


    public function show(User $user)
    {
        $user->load('addresses');
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể xóa chính mình.');
        }

        // Chỉ cho phép xóa nếu tài khoản đang ở trạng thái không hoạt động
        if ($user->is_active) {
            return redirect()->route('admin.users.index')->withErrors([
                'delete' => 'Chỉ có thể xóa tài khoản ở trạng thái không hoạt động!'
            ]);
        }

        // Chỉ cho phép xóa nếu tất cả đơn hàng của user đã giao thành công (delivered hoặc received)
        if ($user->orders()->whereNotIn('status', ['delivered', 'received'])->exists()) {
            return redirect()->route('admin.users.index')->withErrors([
                'delete' => 'Không thể xóa tài khoản này vì đang có đơn hàng chưa giao thành công hoặc đang đặt hàng!'
            ]);
        }

        if ($user->image_profile && Storage::disk('public')->exists($user->image_profile)) {
            Storage::disk('public')->delete($user->image_profile);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Tài khoản đã được ẩn (soft delete).');
    }

    public function trashed()
    {
        $users = User::onlyTrashed()->with(['roles:id,name'])->paginate(10);
        return view('admin.users.trashed', compact('users'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->whereKey($id)->firstOrFail();
        $user->restore();
        return redirect()->route('admin.users.trashed')->with('success', 'Khôi phục tài khoản thành công.');
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.trashed')->with('error', 'Bạn không thể xóa chính mình vĩnh viễn.');
        }

        // Chỉ cho phép xóa vĩnh viễn nếu tất cả đơn hàng của user đã giao thành công (delivered hoặc received)
        // Nếu còn bất kỳ đơn hàng nào chưa giao thành công, không cho phép xóa
        if ($user->orders()->whereNotIn('status', ['delivered', 'received'])->exists()) {
            // Giải thích: Nếu user còn đơn hàng chưa giao thành công, không cho phép xóa để đảm bảo dữ liệu đơn hàng không bị mất liên kết
            return redirect()->route('admin.users.trashed')->withErrors([
                'delete' => 'Không thể xóa tài khoản này vì đang có đơn hàng chưa giao thành công hoặc đang đặt hàng!'
            ]);
        }

        if ($user->image_profile && Storage::disk('public')->exists($user->image_profile)) {
            Storage::disk('public')->delete($user->image_profile);
        }

        $user->forceDelete();

        return redirect()->route('admin.users.trashed')->with('success', 'Tài khoản đã được xóa vĩnh viễn.');
    }

    // public function addresses(User $user)
    // {
    //     $addresses = $user->addresses;
    //     return view('admin.users.addresses.index', compact('user', 'addresses'));
    // }

    public function addresses(User $user)
    {
        $addresses = $user->addresses()->orderByDesc('is_default')->get();
        return view('admin.users.addresses.index', compact('user', 'addresses'));
    }

    /**
     * Thêm địa chỉ mới cho người dùng từ trang admin.
     */
    public function addAddress(StoreUserAddressRequest $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Dữ liệu đã được validate bởi StoreUserAddressRequest
        // Chúng ta lấy tên đầy đủ từ các input ẩn mà JavaScript đã điền vào.
        $data = $request->only([
            'recipient_name',
            'phone',
            'address_line',
            'ward',         // Lấy từ input hidden name="ward"
            'district',     // Lấy từ input hidden name="district"
            'city',         // Lấy từ input hidden name="city"
            'is_default'
        ]);

        // Xử lý địa chỉ mặc định
        if (!empty($data['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
            $data['is_default'] = true;
        } else {
            // Đảm bảo rằng is_default luôn là false nếu không được chọn
            $data['is_default'] = false;
        }

        $user->addresses()->create($data);

        return redirect()->route('admin.users.show', $userId)->with('success', 'Địa chỉ mới đã được thêm thành công.');
    }

    /**
     * Cập nhật địa chỉ cho người dùng từ trang admin.
     */
    public function updateAddress(UpdateUserAddressRequest $request, UserAddress $address)
    {
        // Xử lý địa chỉ mặc định
        if ($request->is_default) {
            // Bỏ mặc định tất cả các địa chỉ khác của người dùng này
            UserAddress::where('user_id', $address->user_id)->update(['is_default' => false]);
        }

        $address->update($request->validated());

        return redirect()->back()->with('success', 'Địa chỉ đã được cập nhật thành công.');
    }

    /**
     * Xóa địa chỉ của người dùng.
     */
    public function deleteAddress(UserAddress $address)
    {
        if ($address->is_default) {
            return redirect()->back()->with('error', 'Không thể xóa địa chỉ mặc định.');
        }

        $address->delete();

        return redirect()->back()->with('success', 'Địa chỉ đã được xóa thành công.');
    }
}
