@extends('layouts.admin')
@section('title', 'Cập nhật thuộc tính')

@section('content')
    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h5 font-weight-bold text-dark">
                Cập nhật thuộc tính
            </h1>

            <a href="{{ route('admin.product.attributes.index') }}" class="btn btn-light btn-sm">
                ← Quay lại
            </a>
        </div>

        <div class="row">

            {{-- CỘT TRÁI --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thông tin thuộc tính
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.product.attributes.update', $attribute->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>Tên thuộc tính</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $attribute->name) }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button class="btn btn-primary btn-sm">
                                Lưu thay đổi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header font-weight-bold d-flex justify-content-between">
                        <span>Giá trị thuộc tính</span>
                        <span class="badge badge-secondary">
                            {{ $attribute->values->count() }} giá trị
                        </span>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="card-body">

                        {{-- FORM THÊM VALUE --}}
                        <form action="{{ route('admin.product.attributes.values.store', $attribute->id) }}" method="POST"
                            class="mb-4">
                            @csrf

                            <div class="form-row align-items-end">
                                <div class="col">
                                    <label class="small fw-bold">Giá trị</label>
                                    <input type="text" name="value" value="{{ old('value') }}" class="form-control"
                                        placeholder="Đỏ, Xanh, XL..." required>
                                    @error('value')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-3">
                                    <label class="small fw-bold">SKU Code</label>
                                    <input type="text" name="sku_code" value="{{ old('sku_code') }}"
                                        class="form-control sku-input" placeholder="VD: TITANIUM_DESERT" required>

                                    @error('sku_code')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-auto">
                                    <button class="btn btn-success">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">
                                SKU: Chỉ dùng A–Z, 0–9 và dấu gạch dưới (_) "hạn chế sửa sau khi đã dùng"
                            </small>
                        </form>

                        {{-- LIST VALUE --}}
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Giá trị</th>
                                    <th width="200">SKU</th>
                                    <th width="110">Trạng thái</th>
                                    <th width="250">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attribute->values as $val)
                                    <tr>
                                        {{-- Định nghĩa Form ẩn nằm ngoài các cột --}}
                                        <form id="form-update-{{ $val->id }}"
                                            action="{{ route('admin.product.attributes.values.update', $val->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                        </form>

                                        {{-- Cột 1: Giá trị --}}
                                        <td>
                                            {{-- Dùng thuộc tính form="id" để liên kết input với form ẩn --}}
                                            <input type="text" name="value" form="form-update-{{ $val->id }}"
                                                class="form-control form-control-sm" value="{{ $val->value }}" required>
                                        </td>

                                        {{-- Cột 2: SKU --}}
                                        <td>
                                            <input type="text" name="sku_code" form="form-update-{{ $val->id }}"
                                                class="form-control form-control-sm sku-input" value="{{ $val->sku_code }}"
                                                required>
                                        </td>

                                        {{-- Cột 3: Trạng thái --}}
                                        <td class="text-center">
                                            @if ($val->is_active)
                                                <span class="badge bg-success">Đang dùng</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Tạm tắt</span>
                                            @endif
                                        </td>

                                        {{-- Cột 4: Hành động --}}
                                        <td class="text-nowrap">
                                            {{-- Nút Lưu thay đổi - Liên kết tới form cập nhật --}}
                                            <button type="submit" form="form-update-{{ $val->id }}"
                                                class="btn btn-sm btn-outline-primary">
                                                Lưu thay đổi
                                            </button>

                                            {{-- Nút Bật/Tắt - Một Form riêng biệt --}}
                                            <form action="{{ route('admin.product.attributes.values.toggle', $val->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-warning">
                                                    {{ $val->is_active ? 'Tắt' : 'Bật' }}
                                                </button>
                                            </form>

                                            {{-- Nút Xóa - Một Form riêng biệt --}}
                                            <form action="{{ route('admin.product.attributes.values.destroy', $val->id) }}"
                                                method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            Chưa có giá trị nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('input', function(e) {
            if (!e.target.classList.contains('sku-input')) return;

            let v = e.target.value
                .toUpperCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/Đ/g, 'D')
                .replace(/[^A-Z0-9_-]/g, ''); // ✅ cho phép _ và -

            e.target.value = v;
        });
    </script>
@endpush
