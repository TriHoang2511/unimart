<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request, $slug)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Tìm Sản phẩm cha (Product) theo slug
        |--------------------------------------------------------------------------
        | Chúng ta luôn lấy sản phẩm cha làm gốc để đảm bảo URL đẹp.
        */
        $product = Product::with([
            'category',
            'images',
            'variants' => function ($query) {
                $query->where('status', 1); // Chỉ lấy các biến thể đang bật
            },
            'variants.attributeValues.attribute',
            'defaultVariant'
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->first();

        /*
        |--------------------------------------------------------------------------
        | 2. Xử lý tìm Biến thể (Variant)
        |--------------------------------------------------------------------------
        */
        $selectedVariant = null;

        if ($product) {
            // Trường hợp A: Có tham số ?product_id= trên URL (Giống CellphoneS)
            $variantIdFromUrl = $request->query('product_id');
            
            if ($variantIdFromUrl) {
                $selectedVariant = $product->variants->where('id', $variantIdFromUrl)->first();
            }

            // Trường hợp B: Nếu không có tham số URL hoặc ID không hợp lệ, lấy mặc định
            if (!$selectedVariant) {
                $selectedVariant = $product->defaultVariant ?? $product->variants->first();
            }
        } 
        else {
            /*
            | Trường hợp C: Nếu khách truy cập bằng slug của biến thể cũ (Redirect hoặc Fallback)
            | Bạn có thể giữ đoạn này để hỗ trợ các link cũ đã chia sẻ trước đó.
            */
            $selectedVariant = ProductVariant::with([
                'product.category',
                'product.images',
                'product.variants.attributeValues.attribute'
            ])
            ->where('slug', $slug)
            ->where('status', 1)
            ->first();

            if ($selectedVariant) {
                $product = $selectedVariant->product;
                
                // Optional: Có thể redirect về URL chuẩn (Slug cha + ?product_id) để SEO tốt hơn
                // return redirect()->route('product.detail', ['slug' => $product->slug, 'product_id' => $selectedVariant->id]);
            }
        }

        // Nếu cuối cùng vẫn không tìm thấy gì thì 404
        if (!$product || !$selectedVariant) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Dữ liệu bổ trợ (Category & Related Products)
        |--------------------------------------------------------------------------
        */
        $category = $product->category;

        $related_products = Product::with(['defaultVariant'])
            ->where('product_category_id', $product->product_category_id)
            ->where('status', 'published')
            ->where('id', '!=', $product->id)
            ->latest()
            ->limit(8)
            ->get();

        return view('client.products.show', compact(
            'product',
            'selectedVariant',
            'category',
            'related_products'
        ));
    }
}