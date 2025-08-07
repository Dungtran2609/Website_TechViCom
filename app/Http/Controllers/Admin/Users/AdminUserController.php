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

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('id')->paginate(10)->appends(['search' => $search]);

        return view('admin.users.index', compact('users'));
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

        if ($user->image_profile && Storage::disk('public')->exists($user->image_profile)) {
            Storage::disk('public')->delete($user->image_profile);
        }

        $user->forceDelete();

        return redirect()->route('admin.users.trashed')->with('success', 'Tài khoản đã được xóa vĩnh viễn.');
    }

    public function addresses(User $user)
    {
        $addresses = $user->addresses;
        return view('admin.users.addresses.index', compact('user', 'addresses'));
    }

    public function addAddress(StoreUserAddressRequest $request, $userId)
    {
        $user = User::findOrFail($userId);

        if ($request->is_default) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create($request->validated());
        return redirect()->route('admin.users.show', $userId)->with('success', 'Địa chỉ mới đã được thêm.');
    }

    public function updateAddress(UpdateUserAddressRequest $request, UserAddress $address)
    {
        if ($request->is_default) {
            UserAddress::where('user_id', $address->user_id)->update(['is_default' => false]);
        }

        $address->update($request->validated());
        return redirect()->back()->with('success', 'Địa chỉ đã được cập nhật.');
    }

    public function deleteAddress(UserAddress $address)
    {
        if ($address->is_default) {
            return redirect()->back()->with('error', 'Không thể xóa địa chỉ mặc định.');
        }

        $address->delete();
        return redirect()->back()->with('success', 'Địa chỉ đã được xóa.');
    }
}
