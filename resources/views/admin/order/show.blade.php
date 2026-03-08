@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <a href="{{ route('order.index') }}" class="btn btn-secondary mb-3">
        ← Quay lại danh sách
    </a>

    <h1 class="mb-4">Chi tiết đơn hàng #{{ $order->id }}</h1>

    {{-- Thông tin đơn hàng --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Thông tin khách hàng</div>
                <div class="card-body">
                    <p><strong>Tên:</strong> {{ $order->customer->name ?? '—' }}</p>
                    <p><strong>Email:</strong> {{ $order->customer->email ?? '—' }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Thông tin đơn</div>
                <div class="card-body">
                    <p><strong>Thanh toán:</strong> {{ $order->payment_method }}</p>
                    <p>
                        <strong>Trạng thái:</strong>
                        <span class="badge bg-info">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách sản phẩm --}}
    <div class="card">
        <div class="card-header">Sản phẩm trong đơn</div>
        <div class="card-body">

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Sản phẩm</th>
                        <th>Biến thể</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                {{ $item->product->name ?? '—' }}
                            </td>

                            <td>
                                {{ $item->variant->name ?? '—' }}
                            </td>

                            <td>{{ number_format($item->price) }} đ</td>

                            <td>{{ $item->quantity }}</td>

                            <td>
                                <strong>
                                    {{ number_format($item->price * $item->quantity) }} đ
                                </strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Tổng cộng:</th>
                        <th>{{ number_format($order->total_amount) }} đ</th>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>

</div>
@endsection