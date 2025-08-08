<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use App\Http\Requests\Admin\PermissionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

        return redirect()->route('admin.permissions.list')
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
        DB::beginTransaction();
        try {
            $data = $request->input('permissions', []);
            $roles = Role::all();

            foreach ($roles as $role) {
                // Nếu vai trò không có trong dữ liệu gửi lên, tức là nó bị bỏ check hết
                // Ngược lại, gán các quyền được check
                $permissionIds = $data[$role->id] ?? [];
                $role->permissions()->sync($permissionIds);
            }
            DB::commit();

            return redirect()->route('admin.permissions.index')
                ->with('success', 'Cập nhật phân quyền cho vai trò thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi cập nhật phân quyền.');
        }
    }

    /**
     * [MỚI] Tự động quét và đồng bộ quyền từ các routes của hệ thống.
     */
    public function sync(Request $request)
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $allRoutes = Route::getRoutes();
        $newPermissionsCount = 0;
        $restoredPermissionsCount = 0;

        // [SỬA LỖI] Sử dụng withTrashed() để lấy tất cả quyền, bao gồm cả những quyền trong thùng rác
        // để tránh lỗi "Duplicate entry".
        $allDbPermissions = Permission::withTrashed()->get()->keyBy('name');
        $existingPermissionNames = $allDbPermissions->keys()->toArray();

        $routePermissionNames = [];

        foreach ($allRoutes as $route) {
            $routeName = $route->getName();

            if (
                $routeName &&
                Str::startsWith($routeName, 'admin.') &&
                !Str::contains($routeName, ['login', 'logout', 'password', 'debugbar']) &&
                $route->getActionName() != 'Closure'
            ) {
                $routePermissionNames[] = $routeName;

                // Nếu quyền chưa tồn tại trong DB (kể cả trong thùng rác) thì tạo mới
                if (!in_array($routeName, $existingPermissionNames)) {
                    $newPermission = Permission::create([
                        'name' => $routeName,
                        'description' => "Auto-generated for route: {$routeName}"
                    ]);
                    $adminRole->permissions()->attach($newPermission->id);
                    $newPermissionsCount++;
                }
                // Nếu quyền đã tồn tại nhưng nằm trong thùng rác, hãy khôi phục nó
                elseif ($allDbPermissions->has($routeName) && $allDbPermissions[$routeName]->trashed()) {
                    $allDbPermissions[$routeName]->restore();
                    $restoredPermissionsCount++;
                }
            }
        }

        $message = "Đồng bộ hoàn tất!";
        $messages = [];

        if ($newPermissionsCount > 0) {
            $messages[] = "Đã thêm {$newPermissionsCount} quyền mới và gán cho Admin.";
        }
        if ($restoredPermissionsCount > 0) {
            $messages[] = "Đã khôi phục {$restoredPermissionsCount} quyền từ thùng rác.";
        }

        // (Tùy chọn) Xóa các quyền không còn tồn tại trong routes
        if ($request->input('prune') === 'true') {
            // [SỬA LỖI] So sánh trực tiếp mảng tên quyền trong DB với mảng tên quyền từ route.
            $permissionsToDelete = array_diff($existingPermissionNames, $routePermissionNames);

            if (!empty($permissionsToDelete)) {
                // Chỉ xóa những quyền được tạo tự động để tránh xóa nhầm quyền tạo tay
                $deletedCount = Permission::whereIn('name', $permissionsToDelete)
                    ->where('description', 'like', 'Auto-generated%')
                    ->delete();
                if ($deletedCount > 0) {
                    $messages[] = "Đã xoá {$deletedCount} quyền cũ không còn sử dụng.";
                }
            }
        }

        if (empty($messages)) {
            return redirect()->route('admin.permissions.list')->with('info', 'Không có thay đổi nào về quyền.');
        }

        return redirect()->route('admin.permissions.list')->with('success', $message . ' ' . implode(' ', $messages));
    }
}
