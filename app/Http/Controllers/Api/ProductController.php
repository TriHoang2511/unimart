<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getVariant($id)
    {
        // Eager load 'product' để không bị lỗi N+1 query (tối ưu hiệu năng)
        $variant = ProductVariant::with('product')->findOrFail($id);

        return response()->json([
            'id'          => $variant->id,
            'full_name'   => $variant->variant_full_name,
            'price'       => number_format($variant->price) . 'đ',
            // Laravel sẽ tự gọi hàm getDisplayDescriptionAttribute() ở trên
            'description' => $variant->display_description,
            'specs'       => $variant->display_specs,
        ]);
    }
}
