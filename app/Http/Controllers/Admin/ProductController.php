<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // ProductController.php
    public function index(Request $request)
    {
        $statusParam = $request->input('status');

        // 1. Khởi tạo Query
        $query = Product::with(['category', 'variants'])
            ->withCount('variants')
            ->withSum('variants as total_stock', 'stock_qty');

        // 2. Đếm số lượng cho các tab (Dùng cho Analytic)
        // Tổng số sản phẩm đang hoạt động (không tính trash)
        $allCount = Product::count();

        // Số lượng sản phẩm theo từng trạng thái
        $statusCounts = [
            'draft'     => Product::where('status', 'draft')->count(),
            'published' => Product::where('status', 'published')->count(),
            'pending'   => Product::where('status', 'pending')->count(),
            'archived'  => Product::where('status', 'archived')->count(),
        ];

        // Số lượng trong thùng rác
        $trashedCount = Product::onlyTrashed()->count();

        // 3. Xử lý lọc theo Status
        if ($statusParam === 'trashed') {
            $query->onlyTrashed();
        } elseif ($statusParam) {
            $query->where('status', $statusParam);
        }

        // Keyword search
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('sku', 'LIKE', "%{$keyword}%");
            });
        }

        $products = $query->latest()->paginate(20)->withQueryString();

        $status = [
            'draft'     => 'Bản nháp',
            'published' => 'Đã đăng',
            'pending'   => 'Chờ duyệt',
            'archived'  => 'Đã lưu trữ'
        ];

        return view('admin.product.index', compact('products', 'status', 'allCount', 'trashedCount', 'statusCounts'));
    }

    /**
     * Lưu sản phẩm mới
     */
    public function store(Request $request)
    {
        // 1. Validate - Khớp chính xác Enum và bảng
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255', // Thêm cái này
            'technical_specifications' => 'nullable|string',       // Thêm cái này
            'description'      => 'nullable|string',        // Thêm cái này
            'category_id'      => 'required|exists:product_categories,id',
            'sku'              => 'required|string|max:100|unique:products,sku',
            'status'           => 'required|in:draft,published,pending,archived',
            'is_default_index' => 'required|integer',

            // Validate cho biến thể
            'v_full_name'      => 'required|array|min:1',
            'v_sku.*'          => 'required|string|distinct|unique:product_variants,sku',
            'v_technical_specifications.*' => 'nullable|string', // Specs riêng của biến thể
            'v_description.*'          => 'nullable|string',     // Desc riêng của biến thể
            'v_price.*'        => 'nullable|numeric|min:0',
            'v_compare_at_price.*' => 'nullable|numeric|min:0',
            'v_stock.*'        => 'nullable|integer|min:0',
            'v_availability.*' => 'required|in:ready,coming_soon,contact,preorder',
            'v_status.*'       => 'required|in:0,1',
            'v_values.*'       => 'nullable|string', // Cần thiết để lấy dữ liệu attribute
            'v_image.*'        => 'nullable|string', // Cần thiết để lấy path ảnh
        ]);

        DB::beginTransaction();
        try {
            $thumbnailPath = $this->moveTempFile($request->main_image_url);

            // 2. Tạo Product
            $product = Product::create([
                'product_category_id' => $validated['category_id'],
                'name'                => $validated['name'],
                'sku'                 => $validated['sku'],
                'slug'                => $validated['slug'] ?: Str::slug($validated['name']),
                'technical_specifications' => $validated['technical_specifications'],
                'description'         => $validated['description'],
                'featured_image'      => $thumbnailPath,
                'status'              => $validated['status'], // Enum: draft, published...
            ]);

            // 3. Gallery (Giữ nguyên logic cũ của bạn)
            if ($request->has('gallery_images')) {
                foreach ($request->gallery_images as $index => $imgUrl) {
                    $newPath = $this->moveTempFile($imgUrl);
                    if ($newPath) {
                        $product->images()->create(['image_path' => $newPath, 'sort_order' => $index]);
                    }
                }
            }

            // 4. Tạo Biến thể - KHỚP TÊN CỘT DATABASE
            foreach ($validated['v_sku'] as $index => $sku) {
                $vImagePath = $this->moveTempFile($validated['v_image'][$index] ?? null);

                $variant = $product->variants()->create([
                    'sku'              => $sku,
                    'variant_full_name' => $validated['v_full_name'][$index],
                    'technical_specifications' => $validated['v_technical_specifications'][$index] ?? null,
                    'description'              => $validated['v_description'][$index] ?? null,
                    'slug'            => Str::slug($validated['v_full_name'][$index]),
                    'price'            => (float) ($validated['v_price'][$index] ?? 0),
                    'compare_at_price' => (float) ($validated['v_compare_at_price'][$index] ?? 0),
                    'stock_qty'        => (int) ($validated['v_stock'][$index] ?? 0),
                    'variant_image'    => $vImagePath,
                    'status'           => $validated['v_status'][$index],       // 1 hoặc 0
                    'availability'     => $validated['v_availability'][$index], // ready, coming_soon...
                ]);

                // KIỂM TRA: Nếu index hiện tại trùng với Radio đã chọn
                if ($validated['is_default_index'] == $index) {
                    $defaultVariantId = $variant->id;
                }

                // Gắn Pivot
                $valIds = array_filter(explode(',', $validated['v_values'][$index]));
                if (!empty($valIds)) {
                    $variant->attributeValues()->attach($valIds);
                }
            }

            if ($defaultVariantId) {
                $product->update(['default_variant_id' => $defaultVariantId]);
            }

            DB::commit();
            return redirect()->route('admin.product.index')->with('status', 'Thêm sản phẩm thành) công!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        $categories = $this->data_tree($categories);
        $availability = [
            'ready'        => 'Sẵn có',
            'coming_soon'  => 'Sắp về',
            'contact'      => 'Liên hệ',
            'preorder'     => 'Đặt trước',
        ];

        $product->load(['images', 'variants.attributeValues']);
        return view('admin.product.edit', compact('product', 'categories', 'availability'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            // PRODUCT
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:product_categories,id',
            // SKU của Product chính: Bỏ qua ID hiện tại để không báo lỗi trùng chính nó
            'sku'            => 'required|string|max:255|unique:products,sku,' . $product->id,
            'status'         => 'required|in:draft,published,pending',
            'technical_specifications'        => 'nullable|string|max:500',
            'description'    => 'nullable|string',

            // IMAGES
            'main_image_url' => 'nullable|string',
            'gallery_images' => 'nullable|array',

            // VARIANTS
            'v_id'           => 'required|array', // Bắt buộc gửi mảng ID biến thể
            'v_sku'          => 'required|array|min:1',
            // Validate SKU biến thể: Bỏ qua kiểm tra unique nếu bạn xử lý logic tay, 
            // hoặc dùng rule đặc biệt. Ở đây tạm để đơn giản để bạn tập trung logic.
            'v_sku.*'        => 'required|string|distinct',
            'v_technical_specifications.*' => 'nullable|string',
            'v_description.*'              => 'nullable|string',
            'v_full_name'    => 'required|array|min:1',
            'v_full_name.*'  => 'required|string|max:255',
            'v_price'        => 'required|array',
            'v_price.*'      => 'nullable|numeric|min:0',
            'v_compare_at_price' => 'required|array',
            'v_compare_at_price.*' => 'nullable|numeric|min:0',
            'v_stock'        => 'required|array',
            'v_stock.*'      => 'nullable|integer|min:0',
            'v_availability'   => 'required|array',
            'v_status'         => 'required|array',
            'v_values'       => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            /* 1. XỬ LÝ ẢNH ĐẠI DIỆN */
            // Chỉ cập nhật nếu có đường dẫn ảnh mới, tránh ghi đè rỗng
            $thumbnailPath = $request->main_image_url ? $this->moveTempFile($request->main_image_url) : $product->featured_image;

            /* 2. CẬP NHẬT SẢN PHẨM CHÍNH */
            $product->update([
                'name'                => $request->name,
                'product_category_id' => $request->category_id,
                'sku'                 => $request->sku,
                'technical_specifications' => $request->technical_specifications,
                'description'              => $request->description,
                'slug'                => $request->slug ?: Str::slug($request->name),
                'summary'             => $request->summary,
                'description'         => $request->description,
                'status'              => $request->status,
                'featured_image'      => $thumbnailPath,
            ]);

            /* 3. XỬ LÝ ALBUM ẢNH (GALLERY) */
            // Thường trang edit sẽ xóa album cũ tạo lại hoặc check tồn tại. 
            // Ở đây để đơn giản: Xóa cũ, tạo mới dựa trên mảng gửi lên.
            if ($request->has('gallery_images')) {
                $product->images()->delete();
                foreach ($request->gallery_images as $index => $imgUrl) {
                    $newPath = $this->moveTempFile($imgUrl);
                    if ($newPath) {
                        $product->images()->create([
                            'image_path' => $newPath,
                            'sort_order' => (int)$index
                        ]);
                    }
                }
            }

            /* 4. CẬP NHẬT BIẾN THỂ (VARIANTS) */
            $defaultVariantId = null;

            foreach ($request->v_id as $index => $vId) {
                // 1. Tìm hoặc tạo mới nếu chưa có ID (trường hợp admin thêm biến thể mới khi edit)
                $variant = \App\Models\ProductVariant::find($vId);

                // Xử lý ảnh: Nếu có ảnh mới thì move, không thì giữ ảnh cũ của variant
                $vImagePath = $request->v_image[$index]
                    ? $this->moveTempFile($request->v_image[$index])
                    : ($variant ? $variant->variant_image : null);

                $dataVariant = [
                    'sku'               => $request->v_sku[$index],
                    'technical_specifications' => $request->v_technical_specifications[$index] ?? null,
                    'description'              => $request->v_description[$index] ?? null,
                    'variant_full_name' => $request->v_full_name[$index],
                    'slug'              => $request->v_slug[$index]
                        ? Str::slug($request->v_slug[$index])
                        : Str::slug($request->v_full_name[$index]),
                    'price'             => (float) ($request->v_price[$index] ?? 0),
                    'compare_at_price'  => (float) ($request->v_compare_at_price[$index] ?? 0),
                    'stock_qty'         => (int) ($request->v_stock[$index] ?? 0),
                    'variant_image'     => $vImagePath,
                    'availability'      => $request->v_availability[$index],
                    'status'            => $request->v_status[$index],
                ];

                if ($variant) {
                    // Cập nhật biến thể cũ
                    $variant->update($dataVariant);
                } else {
                    // Tạo biến thể mới hoàn toàn (nếu admin thêm dòng mới lúc edit)
                    $variant = $product->variants()->create($dataVariant);
                }

                // 2. Kiểm tra đây có phải là biến thể được chọn làm mặc định không?
                if ($request->is_default_index == $index) {
                    $defaultVariantId = $variant->id;
                }

                // 3. Cập nhật Pivot (Attribute Values)
                $attributeValueIds = array_filter(
                    array_map('intval', explode(',', $request->v_values[$index]))
                );
                $variant->attributeValues()->sync($attributeValueIds);
            }

            // 5. CHỐT SỔ HOA HẬU: Cập nhật lại cho Product cha
            if ($defaultVariantId) {
                $product->update(['default_variant_id' => $defaultVariantId]);
            }

            DB::commit();

            return redirect()
                ->route('admin.product.index')
                ->with('status', 'Cập nhật sản phẩm thành công!');
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log lỗi để debug cho dễ
            Log::error("Update Product Error: " . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        // 1. Định nghĩa các hành động hợp lệ
        $allowedStatuses = ['published', 'archived', 'pending', 'draft'];
        $allowedActions = array_merge(['delete'], $allowedStatuses);

        // 2. Validate chặt chẽ
        $request->validate([
            'product_ids'   => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'action'        => 'required|in:' . implode(',', $allowedActions),
        ]);

        $ids = $request->product_ids;
        $action = $request->action;
        $count = count($ids);

        try {
            if ($action === 'delete') {
                Product::whereIn('id', $ids)->delete();
                $message = "Đã xóa tạm thời $count sản phẩm.";
            } else {
                // Xử lý tất cả các trường hợp cập nhật status chỉ bằng 1 dòng
                Product::whereIn('id', $ids)->update(['status' => $action]);

                // Map tin nhắn thông báo (tùy chọn để thân thiện hơn)
                $messages = [
                    'published' => "Đã đăng $count sản phẩm.",
                    'archived'  => "Đã lưu trữ $count sản phẩm.",
                    'pending'   => "Đã chuyển $count sản phẩm sang chờ duyệt.",
                    'draft'     => "Đã lưu nháp $count sản phẩm.",
                ];
                $message = $messages[$action] ?? "Đã cập nhật trạng thái thành công.";
            }

            return back()->with('status', $message);
        } catch (\Exception $e) {
            Log::error("Bulk Action Error: " . $e->getMessage()); // Bỏ dấu \
            return back()->with('error', 'Có lỗi xảy ra.');
        }
    }

    public function quickUpdate(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:product_variants,id',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($data) {
            ProductVariant::where('id', $data['id'])->update($data);
        });

        return response()->json([
            'success' => true
        ]);
    }
    /**
     * Hiển thị form thêm sản phẩm
     */
    public function create()
    {
        $categories = ProductCategory::all();
        $categories = $this->data_tree($categories);
        return view('admin.product.create', compact('categories'));
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Xóa các quan hệ liên quan trước nếu cần
            $product->variants()->delete();

            // Sau đó xóa chính nó
            $product->delete();

            DB::commit();
            return redirect()->route('admin.product.index')
                ->with('status', 'Đã chuyển sản phẩm vào thùng rác!');
        } catch (\Throwable $e) { // Dùng Throwable để bắt được cả Error và Exception
            DB::rollBack();

            // Log lỗi lại để Admin xem trong storage/logs/laravel.log
            Log::error("Lỗi xóa sản phẩm ID {$product->id}: " . $e->getMessage());

            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau.');
        }
    }

    // 1. Khôi phục sản phẩm
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        $product->variants()->restore(); // Đừng quên khôi phục cả biến thể nhé!

        return back()->with('status', 'Đã khôi phục sản phẩm thành công.');
    }

    // 2. Xóa vĩnh viễn
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        // Xóa file ảnh thật trên disk trước khi xóa DB
        if ($product->featured_image) {
            Storage::disk('public')->delete($product->featured_image);
        }

        $product->forceDelete(); // Xóa mất xác trong DB

        return back()->with('status', 'Đã xóa vĩnh viễn sản phẩm và các dữ liệu liên quan.');
    }
    /**
     * Hàm hỗ trợ di chuyển file từ temp sang folder chính
     */
    private function moveTempFile($url)
    {
        if (!$url) return null;

        // 1. Lấy tên file từ URL
        $filename = basename($url);
        $newRelativePath = 'products/' . $filename;
        $tempRelativePath = 'temp/products/' . $filename;

        // 2. Nếu file đã nằm sẵn trong folder products (đã xử lý trước đó trong cùng request)
        if (Storage::disk('public')->exists($newRelativePath)) {
            return $newRelativePath;
        }

        // 3. Nếu file đang nằm trong temp, tiến hành di chuyển
        if (Storage::disk('public')->exists($tempRelativePath)) {
            // Sử dụng copy thay vì move nếu bạn lo lắng ảnh đại diện và gallery dùng chung 1 file temp
            // Nhưng ở đây dùng move là được nếu ta đã có bước 2 check tồn tại ở đích.
            Storage::disk('public')->move($tempRelativePath, $newRelativePath);
            return $newRelativePath;
        }

        // 4. Trường hợp cuối: Nếu URL chứa path sạch (không phải temp cũng không phải domain)
        // Ví dụ người dùng edit sản phẩm, giữ nguyên ảnh cũ
        $cleanPath = str_replace(asset('storage/'), '', $url);
        return $cleanPath;
    }

    /**
     * AJAX: Upload ảnh vào thư mục tạm
     */
    public function uploadTemp(Request $request)
    {
        $uploadedFiles = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('temp/products', 'public');
                $uploadedFiles[] = [
                    'id'   => Str::random(10),
                    'url'  => asset('storage/' . $path),
                    'path' => $path,
                    'name' => $file->getClientOriginalName()
                ];
            }
        }
        return response()->json($uploadedFiles);
    }

    /**
     * AJAX: Xóa ảnh tạm
     */
    public function deleteTemp(Request $request)
    {
        $path = $request->input('path');
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    /* --- CÁC HÀM HỖ TRỢ DANH MỤC CÂY --- */

    private function data_tree($categories, $parent_id = 0, $level = 0)
    {
        $result = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parent_id) {
                $category->level = $level;
                $category->has_child = $this->has_child($categories, $category->id);
                $result[] = $category;

                if ($category->has_child) {
                    $result_child = $this->data_tree($categories, $category->id, $level + 1);
                    $result = array_merge($result, $result_child);
                }
            }
        }
        return $result;
    }

    private function has_child($data, $id)
    {
        foreach ($data as $item) {
            if ($item->parent_id == $id) return true;
        }
        return false;
    }
}
