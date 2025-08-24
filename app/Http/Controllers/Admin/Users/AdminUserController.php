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
        // Nếu user chỉ có vai trò 'user' thì chỉ cho phép cập nhật trạng thái
        $userRoleNames = $user->roles->pluck('name')->toArray();
        if (count($userRoleNames) === 1 && in_array('user', $userRoleNames)) {
            $user->update(['is_active' => $request->is_active]);
            return redirect()->route('admin.users.index')->with('success', 'Chỉ trạng thái tài khoản được cập nhật.');
        } {
            $userData = $request->only(['name', 'email', 'phone_number', 'birthday', 'gender', 'is_active']);


            if ($request->password) {
                $userData['password'] = Hash::make($request->password);
            }


            if ($request->hasFile('image_profile') && $request->file('image_profile')->isValid()) {
                if ($user->image_profile && Storage::disk('public')->exists($user->image_profile)) {
                    Storage::disk('public')->delete($user->image_profile);
                }
                $userData['image_profile'] = $request->file('image_profile')->store('profiles', 'public');
            } else {
                // Nếu không upload ảnh mới, giữ nguyên ảnh cũ
                $userData['image_profile'] = $user->image_profile;
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

        // Chỉ cho phép xóa nếu tất cả đơn hàng của user đã giao thành công (delivered hoặc received)
        // Nếu còn bất kỳ đơn hàng nào chưa giao thành công, không cho phép xóa
        if ($user->orders()->whereNotIn('status', ['delivered', 'received'])->exists()) {
            // Giải thích: Nếu user còn đơn hàng chưa giao thành công, không cho phép xóa để đảm bảo dữ liệu đơn hàng không bị mất liên kết
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
