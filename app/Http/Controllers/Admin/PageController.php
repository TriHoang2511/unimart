<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Str;

class PageController extends Controller
{
    function create()
    {
        return view('admin.page.create');
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            // 'slug' => 'required|string|unique:pages,slug',
            'status' => 'nullable|in:publish,draft,pending',
        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'content.required' => 'Nội dung không được để trống',
        ]);

        Page::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'status' => $validated['status'] ?? 'draft',
        ]);

        return redirect()->route('admin.page.index')->with('status', 'Trang mới đã được tạo thành công!');
    }

    function index(Request $request)
    {
        $status = $request->input('status'); // publish | draft | pending | trash
        $keyword = $request->input('keyword', '');

        // ===== Danh sách trạng thái hiển thị =====
        $list_status = [
            'publish' => 'Công khai',
            'draft' => 'Bản nháp',
            'pending' => 'Chờ duyệt',
            'trash' => 'Thùng rác',
        ];

        // ===== Mặc định: trang chưa bị xóa =====
        $query = Page::query();

        // ===== Nếu xem thùng rác =====
        if ($status === 'trash') {
            $pages = Page::onlyTrashed()->paginate(10);

            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn',
            ];
        }
        // ===== Trang bình thường =====
        else {
            if ($status) {
                $query->where('status', $status);
            }
            if ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%");
            }
            $pages = $query->paginate(10);

            $list_act = [
                'delete' => 'Xóa tạm thời',
            ];
        }
        $count_page_trash = Page::onlyTrashed()->count();
        $count_page_publish = Page::where('status', 'publish')->count();
        $count_page_draft = Page::where('status', 'draft')->count();
        $count_page_pending = Page::where('status', 'pending')->count();

        $count = [
            'trash' => $count_page_trash,
            'publish' => $count_page_publish,
            'draft' => $count_page_draft,
            'pending' => $count_page_pending,
        ];

        return view('admin.page.index', compact('pages', 'list_status', 'list_act', 'count', 'status', 'keyword'));
    }

    // function edit(Page $page)
    // {
    //     // $page = Page::find($page->id);
    //     return view('admin.page.edit', compact('page'));
    // }
    function edit(Page $page)
    {
        if ($page->trashed()) {
            abort(403, 'Trang đã bị xóa, hãy khôi phục trước khi chỉnh sửa.');
        }
        return view('admin.page.edit', compact('page'));
    }


    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:pages,title,' . $page->id,
            'content' => 'required|string',
            'slug' => 'nullable|string|unique:pages,slug,' . $page->id,
            'status' => 'nullable|in:publish,draft,pending',
        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'content.required' => 'Nội dung không được để trống',
        ]);

        // Ưu tiên slug người dùng nhập
        $slug = $validated['slug']
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        $page->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'status' => $validated['status'] ?? 'draft',
        ]);

        return redirect()
            ->route('admin.page.index')
            ->with('status', 'Trang đã được cập nhật thành công!');
    }

    function destroy(Page $page)
    {
        // $page = Page::findOrFail($page->id);
        $page->delete();
        return redirect()->route('admin.page.index')->with('status', 'Đã xóa trang thành công!');
    }

    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if ($list_check) {
            $act = $request->input('act');
            if ($act == 'delete') {
                Page::destroy($list_check);
                return redirect()->route('admin.page.index')->with('status', 'Đã xóa tạm thời các trang đã chọn!');
            } elseif ($act == 'restore') {
                Page::onlyTrashed()->whereIn('id', $list_check)->restore();
                return redirect()->route('admin.page.index')->with('status', 'Đã khôi phục các trang đã chọn!');
            } elseif ($act == 'forceDelete') {
                Page::onlyTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect()->route('admin.page.index')->with('status', 'Đã xóa vĩnh viễn các trang đã chọn!');
            } else {
                return redirect()->route('admin.page.index')->with('status', 'Bạn chưa chọn hành động nào!');
            }
        } else {
            return redirect()->route('admin.page.index')->with('status', 'Bạn chưa chọn trang nào để thực hiện hành động!');
        }
    }
}
