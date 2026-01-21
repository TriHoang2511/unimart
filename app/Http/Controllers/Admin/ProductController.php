<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(20);
        return view('admin.product.index', compact('products'));
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

    /**
     * Lưu sản phẩm mới
     */
    public function store(Request $request)
    {
        $request->validate([
            // PRODUCT
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:product_categories,id',
            'sku'            => 'required|string|max:255|unique:products,sku',
            'status'         => 'required|in:draft,published,pending',
            'summary'        => 'nullable|string|max:500', // Giới hạn cho textarea
            'description'    => 'nullable|string',

            // IMAGES
            'main_image_url' => 'nullable|string',
            'gallery_images' => 'nullable|array',


            // VARIANTS
            'v_sku'          => 'required|array|min:1',
            'v_sku.*'        => 'required|string|distinct|unique:product_variants,sku',
            'v_price'        => 'required|array',
            'v_price.*'      => 'nullable|numeric|min:0',
            'v_compare_at_price' => 'nullable|array',
            'v_compare_at_price.*' => 'nullable|numeric|min:0',
            'v_stock'        => 'required|array',
            'v_stock.*'      => 'nullable|integer|min:0',
            'v_values'       => 'required|array',
            'v_values.*'     => 'required|string', // IDs thuộc tính cách nhau bằng dấu phẩy
            'v_image'        => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            /* 1. XỬ LÝ ẢNH ĐẠI DIỆN */
            $thumbnailPath = $this->moveTempFile($request->main_image_url);

            /* 2. TẠO SẢN PHẨM CHÍNH */
            $product = Product::create([
                'name'                => $request->name,
                'product_category_id' => $request->category_id,
                'sku'                 => $request->sku,
                'slug'                => $request->slug ?: Str::slug($request->name),
                'summary'             => $request->summary,
                'description'         => $request->description,
                'status'              => $request->status,
                'featured_image'      => $thumbnailPath,
            ]);

            /* 3. XỬ LÝ ALBUM ẢNH (GALLERY) */
            if ($request->has('gallery_images')) {
                foreach ($request->gallery_images as $index => $imgUrl) {
                    // Phải đảm bảo hàm này trả về path mới hoặc null nếu lỗi
                    $newPath = $this->moveTempFile($imgUrl);

                    if ($newPath) {
                        $product->images()->create([
                            'image_path' => $newPath,
                            'sort_order' => (int)$index // Ép kiểu về int để chắc chắn
                        ]);
                    }
                }
            }

            /* 4. TẠO BIẾN THỂ (VARIANTS) */
            foreach ($request->v_sku as $index => $sku) {
                // Xử lý ảnh cho từng biến thể (có thể dùng chung ảnh với thumbnail hoặc gallery)
                $vImagePath = $this->moveTempFile($request->v_image[$index] ?? null);

                $variant = $product->variants()->create([
                    'sku'              => $sku,
                    'price'            => (float) ($request->v_price[$index] ?? 0),
                    'compare_at_price' => (float) ($request->v_compare_at_price[$index] ?? 0),
                    'stock_qty'        => (int) ($request->v_stock[$index] ?? 0),
                    'variant_image'            => $vImagePath,
                ]);

                // Gắn thuộc tính (Màu sắc, kích thước...) vào bảng pivot
                $attributeValueIds = array_filter(
                    array_map('intval', explode(',', $request->v_values[$index]))
                );

                if (!empty($attributeValueIds)) {
                    $variant->attributeValues()->attach($attributeValueIds);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.product.index')
                ->with('status', 'Thêm sản phẩm thành công!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
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
