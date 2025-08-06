<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use App\Http\Requests\Admin\PermissionRequest;
use Illuminate\Support\Facades\DB;

class AdminPermissionController extends Controller
{
    /**
     * Trang phân quyền dạng ma trận: dọc là quyền, ngang là vai trò.
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $permissions = $query->with('roles')->orderByDesc('id')->get();
        $roles = Role::all();

        return view('admin.permissions.index', compact('permissions', 'roles'));
    }

    /**
     * Danh sách quyền có phân trang.
     */
    public function list(Request $request)
    {
        $query = Permission::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $permissions = $query->orderByDesc('id')->paginate(10);

        return view('admin.permissions.list', compact('permissions'));
    }

    /**
     * Hiển thị form tạo quyền.
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Lưu quyền mới và gán luôn cho vai trò admin nếu tồn tại.
     */
    public function store(PermissionRequest $request)
    {
        $permission = Permission::create($request->only('name', 'description'));

        // Gán quyền cho vai trò admin nếu tồn tại
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->permissions()->attach($permission->id);
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Thêm quyền mới thành công và đã gán cho vai trò admin.');
    }

    /**
     * Hiển thị form chỉnh sửa quyền.
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Cập nhật quyền.
     */
    public function update(PermissionRequest $request, Permission $permission)
    {
        $permission->update($request->only('name', 'description'));

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Cập nhật quyền thành công.');
    }

    /**
     * Xoá mềm quyền.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions.list')
            ->with('success', 'Quyền đã được xoá.');
    }

    /**
     * Danh sách quyền đã xoá.
     */
    public function trashed()
    {
        $permissions = Permission::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);
        return view('admin.permissions.trashed', compact('permissions'));
    }

    /**
     * Khôi phục quyền đã xoá mềm.
     */
    public function restore($id)
    {
        $permission = Permission::onlyTrashed()->findOrFail($id);
        $permission->restore();

        return redirect()->route('admin.permissions.trashed')
            ->with('success', 'Khôi phục quyền thành công.');
    }

    /**
     * Xoá vĩnh viễn quyền.
     */
    public function forceDelete($id)
    {
        $permission = Permission::onlyTrashed()->findOrFail($id);
        $permission->forceDelete();

        return redirect()->route('admin.permissions.trashed')
            ->with('success', 'Đã xoá vĩnh viễn quyền.');
    }

    /**
     * Cập nhật gán quyền cho vai trò (từ bảng phân quyền).
     */
    public function updateRoles(Request $request)
    {
        $data = $request->input('permissions', []);

        foreach ($data as $roleId => $permissionIds) {
            $role = Role::find($roleId);
            if ($role) {
                $role->permissions()->sync($permissionIds);
            }
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Cập nhật phân quyền cho vai trò thành công.');
    }
}
