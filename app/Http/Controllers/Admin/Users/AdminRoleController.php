<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Http\Requests\Admin\RoleRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Exception;

class AdminRoleController extends Controller
{
    /**
     * Hiển thị danh sách vai trò + gán vai trò cho người dùng (bảng user - role).
     */
    public function index(Request $request)
    {
        try {
            $query = Role::query();

            if ($request->has('search') && !empty($request->input('search'))) {
                $search = $request->input('search');
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
            }

            $roles = $query->with(['permissions:id,name'])
                          ->withCount('permissions')
                          ->orderBy('id', 'desc')
                          ->get();

            $permissions = Permission::select('id', 'name')->orderBy('name')->get();
            $users = User::with(['roles:id,name'])->select('id', 'name', 'email')->get();

            return view('admin.roles.index', compact('roles', 'permissions', 'users'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tải danh sách vai trò.');
        }
    }

    public function list(Request $request)
    {
        try {
            $query = Role::query();

            if ($request->has('search') && !empty($request->input('search'))) {
                $search = $request->input('search');
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
            }

            $roles = $query->withCount('permissions')
                          ->orderBy('id', 'desc')
                          ->paginate(10);

            return view('admin.roles.list', compact('roles'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tải danh sách vai trò.');
        }
    }

    /**
     * Hiển thị form tạo vai trò.
     */
    public function create()
    {
        try {
            $permissions = Permission::select('id', 'name')->orderBy('name')->get();
            return view('admin.roles.create', compact('permissions'));
        } catch (Exception $e) {
            return redirect()->route('admin.roles.index')->with('error', 'Có lỗi xảy ra khi tải form tạo vai trò.');
        }
    }

    /**
     * Lưu vai trò mới và gán quyền.
     */
    public function store(RoleRequest $request)
    {
        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name')),
                'status' => $request->input('status', 'active'),
            ]);

            // Gán quyền cho vai trò
            if ($request->has('permissions')) {
                $role->permissions()->sync($request->input('permissions'));
            }

            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Vai trò đã được tạo thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo vai trò. Vui lòng thử lại.');
        }
    }

    /**
     * Hiển thị form chỉnh sửa vai trò và quyền.
     */
    public function edit(Role $role)
    {
        try {
            $permissions = Permission::select('id', 'name')->orderBy('name')->get();
            $rolePermissions = $role->permissions->pluck('id')->toArray();

            return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
        } catch (Exception $e) {
            return redirect()->route('admin.roles.index')->with('error', 'Có lỗi xảy ra khi tải form chỉnh sửa.');
        }
    }

    /**
     * Cập nhật thông tin và quyền của vai trò.
     */
    public function update(RoleRequest $request, Role $role)
    {
        try {
            DB::beginTransaction();

            $role->update([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name')),
                'status' => $request->input('status', 'active'),
            ]);

            // Cập nhật quyền cho vai trò
            if ($request->has('permissions')) {
                $role->permissions()->sync($request->input('permissions'));
            } else {
                $role->permissions()->detach();
            }

            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Vai trò đã được cập nhật thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật vai trò. Vui lòng thử lại.');
        }
    }

    /**
     * Xem chi tiết vai trò + danh sách quyền.
     */
    public function show(Role $role)
    {
        try {
            $role->load(['permissions:id,name']);
            $permissions = $role->permissions;
            
            return view('admin.roles.show', compact('role', 'permissions'));
        } catch (Exception $e) {
            return redirect()->route('admin.roles.index')->with('error', 'Có lỗi xảy ra khi tải chi tiết vai trò.');
        }
    }

    /**
     * Xoá mềm vai trò.
     */
    public function destroy(Role $role)
    {
        try {
            // Kiểm tra xem vai trò có đang được sử dụng không
            if ($role->users()->count() > 0) {
                return redirect()->back()->with('error', 'Không thể xóa vai trò đang được sử dụng bởi người dùng.');
            }

            $role->delete();

            return redirect()->route('admin.roles.list')
                ->with('success', 'Vai trò đã được ẩn (soft delete) thành công.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa vai trò.');
        }
    }

    /**
     * Danh sách vai trò đã bị xoá mềm.
     */
    public function trashed()
    {
        try {
            $roles = Role::onlyTrashed()
                        ->withCount('permissions')
                        ->orderBy('deleted_at', 'desc')
                        ->get();
                        
            return view('admin.roles.trashed', compact('roles'));
        } catch (Exception $e) {
            return redirect()->route('admin.roles.index')->with('error', 'Có lỗi xảy ra khi tải danh sách vai trò đã xóa.');
        }
    }

    /**
     * Khôi phục vai trò đã xoá mềm.
     */
    public function restore($id)
    {
        try {
            $role = Role::onlyTrashed()->findOrFail($id);
            $role->restore();

            return redirect()->route('admin.roles.trashed')
                ->with('success', 'Vai trò đã được khôi phục thành công.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi khôi phục vai trò.');
        }
    }

    /**
     * Xoá vĩnh viễn vai trò.
     */
    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();

            $role = Role::onlyTrashed()->findOrFail($id);

            // Xóa hình ảnh nếu có
            if ($role->image && Storage::disk('public')->exists($role->image)) {
                Storage::disk('public')->delete($role->image);
            }

            // Xóa quan hệ với permissions
            $role->permissions()->detach();
            
            // Xóa vĩnh viễn
            $role->forceDelete();

            DB::commit();

            return redirect()->route('admin.roles.trashed')
                ->with('success', 'Vai trò đã được xóa vĩnh viễn thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn vai trò.');
        }
    }

    /**
     * Cập nhật gán vai trò cho người dùng (từ bảng).
     */
    public function updateUsers(Request $request)
    {
        try {
            $request->validate([
                'roles' => 'array',
                'roles.*' => 'array',
                'roles.*.*' => 'exists:roles,id'
            ]);

            DB::beginTransaction();

            $data = $request->input('roles', []);
            $updatedCount = 0;

            foreach ($data as $userId => $roleIds) {
                $user = User::find($userId);
                if ($user) {
                    $user->roles()->sync($roleIds);
                    $updatedCount++;
                }
            }

            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('success', "Đã phân vai trò cho {$updatedCount} người dùng thành công.");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi phân vai trò cho người dùng.');
        }
    }
}
