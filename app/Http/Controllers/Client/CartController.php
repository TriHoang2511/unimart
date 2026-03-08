<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant; // Chú ý dùng Variant nếu bạn có biến thể
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    // Hàm này thay thế hoàn toàn cho việc khởi tạo $_SESSION['cart']
    private function getOrCreateCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        // Khách vãng lai: Dùng Cookie để nhận diện (giống session nhưng bền hơn)
        $token = Cookie::get('cart_token');
        if (!$token) {
            $token = (string) Str::uuid();
            Cookie::queue('cart_token', $token, 60 * 24 * 30); // 30 ngày
        }

        return Cart::firstOrCreate(['session_token' => $token]);
    }

    // Tương đương addCartAction trong PHP thuần của bạn
    public function add(Request $request, $id)
    {
        $qty = $request->input('qty', 1);
        $cart = $this->getOrCreateCart();

        // Logic: Nếu sản phẩm đã có trong giỏ thì cộng dồn số lượng
        // Ở đây dùng updateOrCreate để code cực gọn
        // 1. Tìm xem sản phẩm này đã có trong giỏ hàng chưa
        $item = $cart->items()->where('product_id', $id)->first();

        if ($item) {
            // Nếu đã có: Cộng dồn số lượng (giống update_cart trong PHP thuần)
            $item->quantity += $qty;
            $item->save();
        } else {
            // Nếu chưa có: Tạo mới bản ghi (giống add_cart lần đầu)
            $cart->items()->create([
                'product_id' => $id,
                'quantity'   => $qty,
                'selected'   => 1
            ]);
        }

        // Trả về JSON giống hệt cái echo json_encode cũ của bạn
        return response()->json([
            'success' => true,
            'num' => $cart->items()->count(),
        ]);
    }

    // Tương đương buyNowAction
    public function buyNow(Request $request)
    {
        // Lấy ID từ form (variant_id hoặc product_id tùy bạn đặt ở view)
        $id = $request->input('variant_id') ?? $request->input('product_id');
        $qty = $request->input('qty', 1);

        if ($id) {
            // Bước 1: Thêm vào giỏ hàng (Gọi lại hàm add ở trên)
            $this->add($request, $id);

            // Bước 2: Đánh dấu chỉ sản phẩm này được SELECTED (để thanh toán ngay)
            $cart = $this->getOrCreateCart();
            $cart->items()->update(['selected' => 0]); // Bỏ chọn tất cả
            $cart->items()->where('product_id', $id)->update(['selected' => 1]); // Chọn riêng nó

            // Bước 3: Chuyển hướng sang trang giỏ hàng/thanh toán
            return redirect()->route('cart.index');
        }

        return redirect()->back();
    }
}
