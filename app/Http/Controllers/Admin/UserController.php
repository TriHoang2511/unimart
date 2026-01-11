<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Danh sách user
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        // Action theo trạng thái
        $list_act = [
            'delete' => 'Xóa tạm thời',
        ];

        if ($status === 'trash') {
            $list_act = [
                'restore'     => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn',
            ];

            $users = User::onlyTrashed()->paginate(10);
        } else {
            $keyword = $request->query('keyword', '');

            $users = User::where('name', 'LIKE', "%{$keyword}%")
                ->paginate(10);
        }

        $count = [
            'active' => User::count(),
            'trash'  => User::onlyTrashed()->count(),
        ];

        return view('admin.user.index', compact(
            'users',
            'count',
            'list_act'
        ));
    }

    /**
     * Form tạo user
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Lưu user mới
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles'    => 'nullable|array',
            'roles.*'  => 'exists:roles,id',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (!empty($data['roles'])) {
            $roles = Role::whereIn('id', $data['roles'])->get();
            $user->syncRoles($roles);
        }

        return redirect()
            ->route('admin.user.index')
            ->with('status', 'Đã thêm thành viên thành công!');
    }

    /**
     * Form chỉnh sửa user
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        $selectedRoles = old(
            'roles',
            $user->roles->pluck('id')->toArray()
        );

        return view('admin.user.edit', compact(
            'user',
            'roles',
            'selectedRoles'
        ));
    }

    /**
     * Cập nhật user
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        $roles = Role::whereIn('id', $data['roles'] ?? [])
            ->pluck('name')
            ->toArray();

        $user->syncRoles($roles);

        return redirect()
            ->route('admin.user.index')
            ->with('status', 'Cập nhật người dùng thành công!');
    }

    /**
     * Xóa mềm user
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()
                ->route('admin.user.index')
                ->with('status', 'Bạn không thể xóa chính mình!');
        }

        $user->delete();

        return redirect()
            ->route('admin.user.index')
            ->with('status', 'Xóa thành viên thành công!');
    }

    /**
     * Bulk action: delete / restore / forceDelete
     */
    public function action(Request $request)
    {
        $ids = $request->input('list_check', []);
        $act = $request->input('act');

        if (empty($ids)) {
            return redirect()
                ->route('admin.user.index')
                ->with('status', 'Bạn chưa chọn thành viên!');
        }

        // Không cho thao tác lên chính mình
        $ids = array_filter($ids, fn($id) => $id != Auth::id());

        if (empty($ids)) {
            return redirect()
                ->route('admin.user.index')
                ->with('status', 'Bạn không thể thao tác lên chính mình!');
        }

        switch ($act) {
            case 'delete':
                User::whereIn('id', $ids)->delete();
                $message = 'Xóa thành viên thành công!';
                break;

            case 'restore':
                User::onlyTrashed()
                    ->whereIn('id', $ids)
                    ->restore();
                $message = 'Khôi phục thành viên thành công!';
                break;

            case 'forceDelete':
                User::onlyTrashed()
                    ->whereIn('id', $ids)
                    ->forceDelete();
                $message = 'Xóa vĩnh viễn thành viên thành công!';
                break;

            default:
                $message = 'Tác vụ không hợp lệ!';
        }

        return redirect()
            ->route('admin.user.index')
            ->with('status', $message);
    }
}
