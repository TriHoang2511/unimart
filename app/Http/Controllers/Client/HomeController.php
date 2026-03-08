<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Sản phẩm nổi bật: Lấy Product kèm defaultVariant
        // Giả sử bạn có cột 'is_featured' hoặc lấy theo logic riêng, ở đây tôi lấy 8 cái mới nhất
        $list_featured_products = Product::with(['defaultVariant'])
            ->where('status', 'published')
            ->latest()
            ->limit(8)
            ->get();

        // 2. Danh mục cha
        $products_by_category = ProductCategory::whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        // 3. Gắn sản phẩm cho từng danh mục (Lấy biến thể mặc định)
        foreach ($products_by_category as $cat) {
            $categoryIds = $cat->getAllChildrenIds();

            $cat->products_for_home = Product::whereIn('product_category_id', $categoryIds)
                ->where('status', 'published')
                ->with(['defaultVariant'])
                ->latest()
                ->limit(12)
                ->get();
        }

        // 4. Menu Sidebar (Recursive)
        $categories = ProductCategory::whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->with('childrenRecursive')
            ->get();

        return view('client.home.index', compact('list_featured_products', 'products_by_category', 'categories'));
    }
}