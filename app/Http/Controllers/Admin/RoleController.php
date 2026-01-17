<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    function index()
    {
        // echo "Danh sách vai trò";
        // return Gate::allows('role.view');
        $roles = Role::all();
        return view('admin.role.index', compact('roles'));
    }

    function create()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });
        return view('admin.role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'name.required' => 'Tên vai trò không được để trống',
            'name.unique' => 'Vai trò này đã tồn tại',
        ]);

        // 1️⃣ Tạo role
        $role = Role::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'guard_name' => 'web',
        ]);

        // 2️⃣ Gán quyền cho role (Spatie xử lý)
        if (!empty($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()
            ->route('admin.role.index')
            ->with('status', 'Vai trò mới đã được tạo thành công!');
    }

    function destroy(Role $role)
    {
        // Xử lý xóa vai trò với ID $id
        // return "Xóa vai trò với ID: " . $id;
        $role->delete();
        return redirect()->route('admin.role.index')->with('status', 'Vai trò đã được xóa thành công!');
    }

    function edit(Role $role)
    {
        // return $role;
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });
        return view('admin.role.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'name.required' => 'Tên vai trò không được để trống',
            'name.unique' => 'Vai trò này đã tồn tại',
        ]);

        // 1. Cập nhật thông tin cơ bản
        $role->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        // 2. Cập nhật quyền (Spatie tự xử lý mảng ID cực tốt)
        // Nếu $data['permissions'] null, nó sẽ hiểu là mảng rỗng [] và xóa hết quyền cũ.
        $permissions = Permission::whereIn('id', $data['permissions'] ?? [])->get();

        $role->syncPermissions($permissions);

        return redirect()
            ->route('admin.role.index')
            ->with('status', 'Vai trò đã được cập nhật thành công!');
    }
}
