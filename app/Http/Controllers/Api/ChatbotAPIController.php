<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;

class ChatbotAPIController extends Controller
{
    // 1. Lấy giá và kho từ bảng product_variants
    public function getVariants($id)
    {
        $variants = ProductVariant::where('product_id', $id)
            ->select('variant_full_name', 'price', 'stock_qty', 'availability')
            ->get();
        return response()->json($variants);
    }

    // 2. Kiểm tra đơn hàng qua số điện thoại
    public function trackOrder(Request $request)
    {
        $phone = $request->query('phone');
        $order = Order::whereHas('customer', function ($query) use ($phone) {
            $query->where('phone', $phone);
        })->latest()->first();

        if (!$order) return response()->json(['message' => 'Not found'], 404);

        return response()->json([
            'status' => $order->status,
            'total_amount' => number_format($order->total_amount),
            'created_at' => $order->created_at->format('d/m/Y')
        ]);
    }

    // 3. API cho AI "học" (Dùng cho script ingest.py)
    public function getSyncData()
    {
        // Lấy dữ liệu từ bảng pages
        $pages = Page::select('id', 'title', 'content')
            ->get()
            ->map(fn($item) => [
                'type' => 'page',
                'id' => $item->id,
                'title' => $item->title,
                'content' => strip_tags($item->content) // Khuyên dùng: loại bỏ thẻ HTML nếu có
            ]);

        // Lấy dữ liệu từ bảng products
        $products = Product::select('id', 'name as title', 'description as content')
            ->get()
            ->map(fn($item) => [
                'type' => 'product',
                'id' => $item->id,
                'title' => $item->title,
                'content' => strip_tags($item->content)
            ]);

        // Chỉ gộp Page và Product thôi
        return response()->json($pages->concat($products));
    }
}
