<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    /**
     * GET /admin/permission
     * Trang quản lý: list + create
     */
    public function index()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('admin.permission.index', compact('permissions'));
    }

    /**
     * POST /admin/permission
     * Lưu quyền mới
     **/
    public function store(Request $request)
    {
        $request->merge([
            'name' => strtolower(trim($request->name))
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_-]+(\.[a-z0-9_-]+)+$/',
                'unique:permissions,name',
            ],
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Tên quyền không được để trống',
            'name.unique' => 'Quyền này đã tồn tại',
            'name.regex' => 'Tên quyền phải viết thường và phân tách bằng dấu chấm (vd: product.create, product.cat.force_delete)',
        ]);;

        Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.permission.index')
            ->with('status', 'Quyền mới đã được tạo thành công!');
    }

    /**
     * GET /admin/permission/{permission}/edit
     * Form chỉnh sửa
     */
    public function edit(Permission $permission)
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('admin.permission.edit', compact('permission', 'permissions'));
    }

    /**
     * PUT /admin/permission/{permission}
     * Cập nhật quyền
     */
    public function update(Request $request, Permission $permission)
    {

        $request->merge([
            'name' => strtolower(trim($request->name))
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_-]+(\.[a-z0-9_-]+)+$/',
                'unique:permissions,name,' . $permission->id,
            ],
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Tên quyền không được để trống',
            'name.unique' => 'Quyền này đã tồn tại',
            'name.regex' => 'Tên quyền phải viết thường và phân tách bằng dấu chấm (vd: product.create, product.cat.force_delete)',
        ]);;

        $permission->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.permission.index')
            ->with('status', 'Đã cập nhật quyền thành công!');
    }

    /**
     * DELETE /admin/permission/{permission}
     * Xóa quyền
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()
            ->route('admin.permission.index')
            ->with('status', 'Đã xóa quyền thành công!');
    }
}
