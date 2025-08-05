<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Http\Requests\Admin\RoleRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{

    /**
     * Hiển thị danh sách vai trò + gán vai trò cho người dùng (bảng user - role).
     */
    public function index()
    {
        $query = Role::query();

        if (request()->has('search')) {
            $search = request('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        $roles = $query->with('permissions')->orderBy('id', 'desc')->get();
        $permissions = Permission::all();
        $users = User::with('roles')->get();

        return view('admin.roles.index', compact('roles', 'permissions', 'users'));
    }

    public function list(Request $request)
    {
        $query = Role::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        $roles = $query->withCount('permissions')
                    ->orderBy('id', 'desc')
                    ->paginate(10); // có thể điều chỉnh số dòng mỗi trang

        return view('admin.roles.list', compact('roles'));
    }

    /**
     * Hiển thị form tạo vai trò.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Lưu vai trò mới và gán quyền.
     */
    public function store(RoleRequest $request)
    {

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Vai trò đã được tạo thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa vai trò và quyền.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Cập nhật thông tin và quyền của vai trò.
     */
    public function update(RoleRequest $request, Role $role)
    {

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),

            'status' => $request->status,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Vai trò đã được cập nhật.');
    }

    /**
     * Xem chi tiết vai trò + danh sách quyền.
     */
    public function show(Role $role)
    {
        // $permissions = $role->permissions;
        return view('admin.roles.show', compact('role', 'permissions'));
    }

    /**
     * Xoá mềm vai trò.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('admin.roles.list')
            ->with('success', 'Vai trò đã được ẩn (soft delete).');
    }

    /**
     * Danh sách vai trò đã bị xoá mềm.
     */
    public function trashed()
    {
        $roles = Role::onlyTrashed()->get();
        return view('admin.roles.trashed', compact('roles'));
    }

    /**
     * Khôi phục vai trò đã xoá mềm.
     */
    public function restore($id)
    {
        Role::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('admin.roles.trashed')
            ->with('success', 'Vai trò đã được khôi phục.');
    }

    /**
     * Xoá vĩnh viễn vai trò.
     */
    public function forceDelete($id)
    {
        $role = Role::onlyTrashed()->findOrFail($id);

        if ($role->image) {
            Storage::disk('public')->delete($role->image);
        }

        $role->forceDelete();

        return redirect()->route('admin.roles.trashed')
            ->with('success', 'Vai trò đã được xoá vĩnh viễn.');
    }

    /**
     * Cập nhật gán vai trò cho người dùng (từ bảng).
     */
    public function updateUsers(Request $request)
    {
        $data = $request->input('roles', []);

        foreach ($data as $userId => $roleIds) {
            $user = User::find($userId);
            if ($user) {
                $user->roles()->sync($roleIds);
            }
        }

        return redirect()->route('admin.roles.index')->with('success', 'Phân vai trò cho người dùng thành công.');
    }
}
