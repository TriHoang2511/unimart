<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng (Admin)
     */
    public function index()
    {
        $orders = Order::with('customer')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.order.index', compact('orders'));
    }

    /**
     * Form tạo đơn hàng (Admin)
     */
    public function create()
    {
        return view('admin.order.create');
    }

    /**
     * Lưu đơn hàng
     * Dùng chung cho: Admin Web / API / Chatbot
     */
    public function store(Request $request)
    {
        // 1️⃣ Validate
        $validated = $request->validate([
            'customer_id'                => 'required|exists:customers,id',
            'shipping_address'           => 'required|string|max:255',
            'payment_method'             => 'required|in:COD,Online Payment',
            'items'                      => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity'           => 'required|integer|min:1',
        ]);

        // 2️⃣ Transaction (CHUẨN HỆ THỐNG LỚN)
        $order = DB::transaction(function () use ($validated) {

            $totalAmount = 0;

            // 2.1 Tạo order (chưa có total chính xác)
            $order = Order::create([
                'customer_id'      => $validated['customer_id'],
                'shipping_address' => $validated['shipping_address'],
                'payment_method'   => $validated['payment_method'],
                'total_amount'     => 0,
                'status'           => 'pending',
            ]);

            // 2.2 Xử lý từng item
            foreach ($validated['items'] as $item) {

                // 🔒 LOCK biến thể để tránh oversell
                $variant = ProductVariant::lockForUpdate()
                    ->findOrFail($item['product_variant_id']);

                // 2.3 Kiểm tra tồn kho
                if ($variant->stock < $item['quantity']) {
                    throw new \Exception(
                        "Sản phẩm {$variant->id} không đủ tồn kho"
                    );
                }

                $price = $variant->price;
                $quantity = $item['quantity'];
                $subtotal = $price * $quantity;

                // 2.4 Snapshot order item
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'quantity'           => $quantity,
                    'price'              => $price,
                ]);

                // 2.5 Trừ kho
                $variant->decrement('stock', $quantity);

                $totalAmount += $subtotal;
            }

            // 2.6 Cập nhật tổng tiền
            $order->update([
                'total_amount' => $totalAmount
            ]);

            return $order;
        });

        // 3️⃣ Redirect cho Admin Web
        return redirect()
            ->route('admin.order.show', $order->id)
            ->with('success', 'Tạo đơn hàng thành công');
    }

    /**
     * Xem chi tiết đơn hàng
     */
    public function show(Order $order)
    {
        $order->load([
            'customer',
            'items.product',
            'items.variant'
        ]);

        return view('admin.order.show', compact('order'));
    }
}
