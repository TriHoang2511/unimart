<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Attribute;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'active');

        if ($status === 'trash') {
            $categories = ProductCategory::onlyTrashed()
                ->orderBy('deleted_at', 'desc')
                ->get();
        } else {
            $categories = ProductCategory::whereNull('parent_id')
                ->with('children', 'children.children', 'parent')
                ->orderBy('sort_order')
                ->get();
        }

        return view('admin.product.cat.index', compact('categories', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:product_categories,slug',
            'parent_id'   => 'nullable|exists:product_categories,id',
            'sort_order'  => 'required|integer|min:0',
            'status'      => 'required|in:0,1',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = [
            'name'        => $validated['name'],
            'slug'        => $validated['slug'] ?? Str::slug($validated['name']),
            'parent_id'   => $validated['parent_id'] ?? null,
            'sort_order'  => $validated['sort_order'],
            'status'      => $validated['status'],
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('categories', 'public');
        }

        ProductCategory::create($data);

        return back()->with('success', 'Thêm danh mục thành công!');
    }

    private function getDescendantIds(ProductCategory $category)
    {
        $ids = [];

        foreach ($category->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }

        return $ids;
    }

    // public function edit(ProductCategory $category)
    // {
    //     $category->load('children');

    //     $excludedIds = $this->getDescendantIds($category);
    //     $excludedIds[] = $category->id;

    //     $categories = ProductCategory::whereNotIn('id', $excludedIds)
    //         ->orderBy('sort_order')
    //         ->get();

    //     return view('admin.product.cat.edit', compact('category', 'categories'));
    // }

    public function edit(ProductCategory $category)
    {
        // Load cây con (đang dùng cho parent select)
        $category->load('children');

        // ====== PHẦN CŨ: XỬ LÝ DANH MỤC CHA ======
        $excludedIds = $this->getDescendantIds($category);
        $excludedIds[] = $category->id;

        $categories = ProductCategory::whereNotIn('id', $excludedIds)
            ->orderBy('sort_order')
            ->get();

        // ====== PHẦN MỚI: ATTRIBUTE ======
        $attributes = Attribute::orderBy('name')->get();

        $selectedAttributes = $category->attributes()
            ->pluck('attributes.id')
            ->toArray();

        return view('admin.product.cat.edit', compact(
            'category',
            'categories',
            'attributes',
            'selectedAttributes'
        ));
    }

    public function destroy(ProductCategory $category)
    {
        // 1. Lấy hoặc tạo danh mục "Khác"
        $uncategorized = ProductCategory::firstOrCreate(
            ['slug' => 'khac'],
            [
                'name' => 'Khác',
                'parent_id' => null,
                'sort_order' => 999,
                'status' => 1,
            ]
        );

        // 2. Không cho xóa danh mục hệ thống
        if ($category->id === $uncategorized->id) {
            return back()->with('error', 'Không thể xóa danh mục hệ thống!');
        }

        /**
         * 3. HÀM ĐỆ QUY:
         *    Lấy toàn bộ ID của danh mục con (mọi cấp)
         */
        $getAllChildrenIds = function ($category) use (&$getAllChildrenIds) {
            $ids = [];

            foreach ($category->children as $child) {
                $ids[] = $child->id;

                // Gọi lại chính nó để lấy con của con
                $ids = array_merge(
                    $ids,
                    $getAllChildrenIds($child)
                );
            }

            return $ids;
        };

        // 4. Lấy danh sách toàn bộ ID con
        $childrenIds = $getAllChildrenIds($category);

        // 5. Nếu có con → chuyển toàn bộ sang "Khác"
        if (!empty($childrenIds)) {
            ProductCategory::whereIn('id', $childrenIds)
                ->update(['parent_id' => $uncategorized->id]);
        }

        // 6. Xóa (soft delete) danh mục cha
        $category->delete();

        return redirect()
            ->route('admin.product.cat.index')
            ->with('status', 'Đã xóa danh mục và chuyển toàn bộ danh mục con sang "Khác".');
    }

    public function restore($id)
    {
        $category = ProductCategory::onlyTrashed()->findOrFail($id);
        $category->restore();

        return back()->with('status', 'Đã khôi phục danh mục!');
    }

    public function force_delete($id)
    {
        $category = ProductCategory::onlyTrashed()->findOrFail($id);

        // Không cho force delete danh mục "Khác"
        if ($category->slug === 'khac') {
            return back()->with('error', 'Không thể xóa vĩnh viễn danh mục hệ thống!');
        }

        $category->forceDelete();

        return back()->with('status', 'Đã xóa vĩnh viễn!');
    }

    public function update(Request $request, ProductCategory $category)
    {
        // ==========================
        // DANH MỤC HỆ THỐNG "KHÁC"
        // ==========================
        if ($category->slug === 'khac') {

            $request->validate([
                'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'description' => 'nullable|string',
            ]);

            $data = [];

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('categories', 'public');
            }

            if ($request->filled('description')) {
                $data['description'] = $request->description;
            }

            // Không có gì để cập nhật
            if (empty($data)) {
                return back()->with(
                    'status',
                    'Danh mục "Khác" không có thay đổi nào.'
                );
            }

            $category->update($data);

            return back()->with(
                'status',
                'Đã cập nhật thông tin hiển thị cho danh mục "Khác".'
            );
        }

        // ==========================
        // DANH MỤC BÌNH THƯỜNG
        // ==========================
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:product_categories,slug,' . $category->id,
            'parent_id'   => 'nullable|exists:product_categories,id',
            'sort_order'  => 'required|integer|min:0',
            'status'      => 'required|in:0,1',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            // Phần mới thêm để xử lý thuộc tính
            'attribute_ids' => 'nullable|array', // Thêm dòng này để nhận mảng ID thuộc tính
            'attribute_ids.*' => 'exists:attributes,id',
        ]);

        $data = [
            'name'        => $validated['name'],
            'slug'        => $validated['slug'] ?? Str::slug($validated['name']),
            'parent_id'   => $validated['parent_id'] ?? null,
            'sort_order'  => $validated['sort_order'],
            'status'      => $validated['status'],
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('categories', 'public');
        }

        $category->update($data);

        // ==========================================
        // CẬP NHẬT QUY TẮC THUỘC TÍNH (NEW)
        // ==========================================
        // Nếu có gửi mảng attribute_ids lên thì đồng bộ, nếu không thì xóa hết liên kết
        $category->attributes()->sync($request->input('attribute_ids', []));

        return redirect()
            ->route('admin.product.cat.index')
            ->with('status', 'Cập nhật danh mục thành công!');
    }

    // public function editAttributes($id)
    // {
    //     $category = ProductCategory::findOrFail($id);
    //     $attributes = Attribute::all(); // Lấy tất cả thuộc tính từ bảng attributes

    //     // Lấy danh sách ID các thuộc tính đã gán cho danh mục này
    //     $selectedAttributes = $category->attributes()->pluck('attributes.id')->toArray();

    //     return view('admin.product.cat.edit', compact('category', 'attributes', 'selectedAttributes'));
    // }

    // public function updateAttributes(Request $request, $id)
    // {
    //     $category = ProductCategory::findOrFail($id);

    //     // Lưu vào bảng category_attributes
    //     $category->attributes()->sync($request->attribute_ids);

    //     return redirect()->route('admin.product.cat.index')->with('status', 'Cập nhật quy tắc thuộc tính thành công!');
    // }
}
