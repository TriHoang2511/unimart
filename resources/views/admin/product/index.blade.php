@extends('layouts.admin')

@section('title', 'Danh sách sản phẩm')

@section('content')
    <style>
        /* Container chính */
        .analytic {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Style cho từng link */
        .analytic-link {
            display: flex;
            align-items: center;
            padding: 0 15px;
            font-size: 14px;
            font-weight: 500;
            color: #64748b !important;
            text-decoration: none;
            position: relative;
            transition: color 0.2s;
        }

        /* Tạo đường kẻ đứng ngăn cách (trừ tab cuối cùng) */
        /* .analytic-link:not(:last-child)::after {
            content: "";
            position: absolute;
            right: 0;
            top: 20%;
            height: 60%;
            width: 1px;
            background-color: #cbd5e1;
        } */

        /* Xóa padding cho tab đầu tiên để thẳng hàng với nội dung bên dưới */
        .analytic-link:first-child {
            padding-left: 0;
        }

        /* Trạng thái Active & Hover */
        .analytic-link:hover,
        .analytic-link.active {
            color: #3b82f6 !important;
            /* Màu xanh giống trong hình */
        }

        /* Badge tròn số lượng */
        .analytic-link .badge {
            margin-left: 8px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            /* Bo tròn tuyệt đối */
            font-size: 12px;
            font-weight: bold;
        }

        /* Màu Badge mặc định (Tất cả) */
        .badge-blue {
            background-color: #3b82f6;
            color: white;
        }

        /* Màu Badge cho Thùng rác (Đỏ) */
        .badge-red {
            background-color: #ef4444;
            color: white;
        }

        /* Status Badge chuyên nghiệp */
        .badge-status {
            display: inline-block;
            min-width: 100px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 50px;
            text-align: center;
        }

        .status-published {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .status-draft {
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .status-pending {
            background-color: #fef9c3;
            color: #854d0e;
            border: 1px solid #fef08a;
        }

        .status-archived {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Table & Variants */
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        .variant-row {
            background-color: #f9fafb;
            border-left: 4px solid #3b82f6;
        }

        .img-preview {
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #eee;
        }

        .quick-edit-input {
            width: 100%;
            border: 1px solid #ddd;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .quick-edit-input:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }

        /* Container bảng chính */
        .table-responsive.border.rounded {
            border-color: #e2e8f0 !important;
            overflow: hidden;
            /* Để border-radius hoạt động tốt hơn */
        }

        /* Header bảng chính */
        .table-light {
            background-color: #f8fafc !important;
            font-size: 0.875rem;
        }

        .table thead th {
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
            vertical-align: middle;
            padding: 12px 8px;
            font-size: 0.8125rem;
            /* 13px */
            letter-spacing: 0.05em;
        }

        /* Dòng sản phẩm chính */
        .product-main-row {
            background-color: #ffffff;
            transition: background-color 0.2s ease;
        }

        .product-main-row:hover {
            background-color: #f1f5f9 !important;
            /* Màu hover đậm hơn một chút */
        }

        /* Cột thông tin sản phẩm */
        .product-main-row td:nth-child(3) .fw-bold {
            font-size: 1rem;
            color: #1e293b;
        }

        .product-main-row td:nth-child(3) .text-muted {
            font-size: 0.8125rem;
            margin-top: 2px;
        }

        /* Badge tồn kho */
        .badge.bg-info {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 50px;
        }

        /* Giá min-max */
        .text-danger.fw-bold.small {
            font-size: 0.875rem;
            white-space: nowrap;
        }

        /* Nút mở/đóng biến thể */
        .btn-toggle-variants {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .btn-toggle-variants:hover {
            background-color: #eff6ff;
            border-color: #3b82f6;
        }

        .btn-toggle-variants i {
            transition: transform 0.3s ease;
        }

        .btn-toggle-variants.collapsed i {
            transform: rotate(0);
        }

        /* Phần biến thể (collapse) */
        .variant-row {
            background-color: #f8fafc;
            border-left: 4px solid #3b82f6;
            transition: all 0.3s ease;
        }

        .variant-row .bg-white {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .variant-row h6 {
            color: #3b82f6;
            font-size: 0.9375rem;
            margin-bottom: 1rem;
        }

        /* Bảng biến thể bên trong */
        .variant-row .table-sm thead {
            background-color: #f1f5f9;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #475569;
        }

        .variant-row .table-sm th,
        .variant-row .table-sm td {
            padding: 10px 8px;
            vertical-align: middle;
            font-size: 0.8125rem;
        }

        .variant-row .table-sm tbody tr:hover {
            background-color: #f0f9ff;
        }

        /* Badge phân loại trong biến thể */
        .badge.bg-light.border {
            background-color: #f8fafc !important;
            color: #475569;
            border: 1px solid #cbd5e1;
            font-size: 0.75rem;
            padding: 4px 10px;
            margin: 2px;
            border-radius: 50px;
        }

        /* Input quick edit */
        .quick-edit-input {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .quick-edit-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            outline: none;
        }

        /* Nút lưu quick edit */
        .btn-save-quick {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .btn-save-quick:hover {
            background-color: #dcfce7;
            transform: scale(1.05);
        }

        /* Nút thao tác (sửa, xóa) */
        .btn-group .btn {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .btn-group .btn:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }

        /* Hình ảnh preview */
        .img-preview {
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .img-preview:hover {
            transform: scale(1.1);
        }

        /* Cải thiện badge trạng thái (đã có trong CSS cũ, giữ và tinh chỉnh nhẹ) */
        .badge-status {
            min-width: 90px;
            padding: 6px 12px;
            font-size: 0.8125rem;
            border-radius: 50px;
        }
    </style>

    <div id="content" class="container-fluid">
        {{-- THÔNG BÁO --}}
        @if (session('status'))
            <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold"><i class="fa fa-boxes me-2 text-primary"></i>QUẢN LÝ SẢN PHẨM</h5>
                <a href="{{ route('admin.product.create') }}" class="btn btn-primary px-4 shadow-sm">
                    <i class="fa fa-plus-circle me-1"></i> Thêm sản phẩm
                </a>
            </div>

            <div class="card-body">
                {{-- BỘ LỌC TRẠNG THÁI (ANALYTIC) --}}
                <div class="analytic mb-4">
                    {{-- Nút Tất cả --}}
                    <a href="{{ route('admin.product.index') }}"
                        class="analytic-link {{ !request('status') ? 'active' : '' }}">
                        Tất cả <span class="badge badge-blue">{{ $allCount }}</span>
                    </a>

                    @foreach ($status as $key => $label)
                        <a href="{{ route('admin.product.index', ['status' => $key]) }}"
                            class="analytic-link {{ request('status') == $key ? 'active' : '' }}">
                            {{ $label }} <span class="badge badge-blue">{{ $statusCounts[$key] }}</span>
                        </a>
                    @endforeach

                    {{-- Nút Thùng rác --}}
                    <a href="{{ route('admin.product.index', ['status' => 'trashed']) }}"
                        class="analytic-link {{ request('status') == 'trashed' ? 'active' : '' }}">
                        Thùng rác <span class="badge badge-red">{{ $trashedCount }}</span>
                    </a>
                </div>

                {{-- BỘ LỌC TÌM KIẾM --}}
                <div class="filter-box bg-light p-3 rounded mb-4">
                    <form action="{{ route('admin.product.index') }}" method="GET" class="row g-2">
                        <div class="col-md-5">
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                                placeholder="Tìm tên sản phẩm, mã SKU biến thể...">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">-- Mọi trạng thái --</option>
                                @foreach ($status as $key => $val)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark w-100"><i class="fa fa-search"></i> Lọc</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>

                {{-- FORM TÁC VỤ HÀNG LOẠT --}}
                <form action="{{ route('admin.product.bulk-action') }}" method="POST" id="bulkActionForm">
                    @csrf
                    <div class="bulk-actions mb-3 d-flex align-items-center gap-2 d-none">
                        <div
                            class="selected-info bg-primary-subtle border border-primary-subtle px-3 py-1 rounded text-primary small fw-bold">
                            <i class="fa fa-check-square"></i> Đã chọn <span id="selected-count">0</span>
                        </div>
                        <select name="action" class="form-select form-select-sm w-auto" required>
                            <option value="">-- Chọn tác vụ --</option>
                            @foreach ($status as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach

                            <option value="delete">Xóa tạm thời</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary px-3">Áp dụng</button>
                    </div>

                    {{-- TABLE DỮ LIỆU --}}
                    <div class="table-responsive border rounded">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="small text-uppercase fw-bold">
                                    <th width="40" class="text-center"><input type="checkbox" id="checkAll"
                                            class="form-check-input"></th>
                                    <th width="70">Ảnh</th>
                                    <th>Thông tin sản phẩm</th>
                                    <th>Kho</th>
                                    <th>Giá (Min - Max)</th>
                                    <th>Danh mục</th>
                                    <th>Trạng thái</th>
                                    <th width="80" class="text-center">Biến thể</th>
                                    <th width="100">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr class="product-main-row">
                                        <td class="text-center">
                                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                                                class="checkItem form-check-input">
                                        </td>
                                        <td>
                                            <img src="{{ $product->featured_image ? asset('storage/' . $product->featured_image) : asset('assets/img/no-image.png') }}"
                                                class="img-preview" width="55" height="55">
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                                            <div class="small text-muted">SKU: {{ $product->sku }} |
                                                {{ $product->variants_count }} phân loại</div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $product->total_stock > 10 ? 'bg-info' : 'bg-warning' }}">
                                                {{ number_format($product->total_stock) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-danger fw-bold small">
                                                {{ number_format($product->variants->min('price')) }}₫ -
                                                {{ number_format($product->variants->max('price')) }}₫
                                            </div>
                                        </td>
                                        <td class="small">{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge-status status-{{ $product->status }}">
                                                {{ $status[$product->status] ?? $product->status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary btn-toggle-variants"
                                                data-target="#variants-{{ $product->id }}">
                                                <i class="fa fa-plus-square"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @if (request('status') == 'trashed')
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-success btn-restore"
                                                        data-id="{{ $product->id }}" title="Khôi phục">
                                                        <i class="fa fa-undo"></i>
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger btn-force-delete"
                                                        data-id="{{ $product->id }}" title="Xóa vĩnh viễn">
                                                        <i class="fa fa-fire"></i>
                                                    </button>
                                                @else
                                                    <a href="{{ route('admin.product.edit', $product->id) }}"
                                                        class="btn btn-sm btn-light border" title="Sửa">
                                                        <i class="fa fa-edit text-primary"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-sm btn-light border text-danger btn-delete"
                                                        data-id="{{ $product->id }}" title="Xóa tạm thời">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Dòng biến thể xổ xuống --}}
                                    <tr id="variants-{{ $product->id }}" class="variant-row d-none">
                                        <td colspan="9" class="p-3">
                                            <div class="bg-white p-3 rounded shadow-sm border">
                                                <h6 class="fw-bold mb-3 small text-primary"><i class="fa fa-list"></i> CHI
                                                    TIẾT BIẾN THỂ</h6>
                                                <table class="table table-sm table-bordered align-middle">
                                                    <thead class="table-light small">
                                                        <tr>
                                                            <th>Tên biến thể</th>
                                                            <th>Ảnh</th>
                                                            <th>Phân loại</th>
                                                            <th width="180">Giá bán (₫)</th>
                                                            <th width="180">Giá cũ (₫)</th>
                                                            <th width="120">Tồn kho</th>
                                                            <th width="50">Lưu</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($product->variants as $variant)
                                                            <tr class="variant-item"
                                                                data-variant-id="{{ $variant->id }}">
                                                                <td class="small fw-bold">{{ $variant->variant_full_name }}</td>
                                                                <td>
                                                                    <img src="{{ $variant->variant_image ? asset('storage/' . $variant->variant_image) : asset('storage/app/public/products/Image_Placeholder.jpg') }}"
                                                                        class="img-preview" width="45"
                                                                        height="45">
                                                                </td>
                                                                <td class="small">
                                                                    @foreach ($variant->attributeValues as $val)
                                                                        <span
                                                                            class="badge bg-light text-dark border">{{ $val->value }}</span>
                                                                    @endforeach
                                                                </td>
                                                                <td><input type="number"
                                                                        class="quick-edit-input quick-price"
                                                                        value="{{ $variant->price }}"></td>
                                                                <td><input type="number"
                                                                        class="quick-edit-input quick-compare-price"
                                                                        value="{{ $variant->compare_at_price }}"></td>
                                                                <td><input type="number"
                                                                        class="quick-edit-input quick-stock"
                                                                        value="{{ $variant->stock_qty }}"></td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-success btn-save-quick"><i
                                                                            class="fa fa-save"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">Không tìm thấy sản phẩm nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

                {{-- PHÂN TRANG --}}
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const checkItems = document.querySelectorAll('.checkItem');
            const bulkBar = document.querySelector('.bulk-actions');
            const selectedCount = document.getElementById('selected-count');

            // 1. Logic Checkbox & Bulk Bar
            function updateBulkBar() {
                const checked = document.querySelectorAll('.checkItem:checked').length;
                if (checked > 0) {
                    bulkBar.classList.remove('d-none');
                    selectedCount.textContent = checked;
                } else {
                    bulkBar.classList.add('d-none');
                }
            }

            checkAll.addEventListener('change', function() {
                checkItems.forEach(item => item.checked = checkAll.checked);
                updateBulkBar();
            });

            checkItems.forEach(item => {
                item.addEventListener('change', updateBulkBar);
            });

            // 2. Toggle Variants
            document.querySelectorAll('.btn-toggle-variants').forEach(btn => {
                btn.addEventListener('click', function() {
                    const target = document.querySelector(this.dataset.target);
                    const icon = this.querySelector('i');
                    target.classList.toggle('d-none');
                    icon.classList.toggle('fa-plus-square');
                    icon.classList.toggle('fa-minus-square');
                });
            });

            // 3. Quick Update Variant (Ajax)
            document.querySelectorAll('.btn-save-quick').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('.variant-item');
                    const payload = {
                        id: row.dataset.variantId,
                        price: row.querySelector('.quick-price').value,
                        compare_at_price: row.querySelector('.quick-compare-price').value,
                        stock_qty: row.querySelector('.quick-stock').value
                    };

                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                    fetch("{{ route('admin.product.quick_update') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify(payload)
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                btn.classList.replace('btn-success', 'btn-outline-success');
                                btn.innerHTML = '<i class="fa fa-check"></i>';
                                setTimeout(() => {
                                    btn.classList.replace('btn-outline-success',
                                        'btn-success');
                                    btn.innerHTML = '<i class="fa fa-save"></i>';
                                }, 2000);
                            }
                        })
                        .finally(() => btn.disabled = false);
                });
            });

            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;

                    if (!confirm('Bạn có chắc chắn muốn chuyển sản phẩm này vào thùng rác?'))
                        return;

                    // Tạo form ảo
                    const form = document.createElement('form');
                    form.method = 'POST'; // Luôn là POST khi submit form

                    // Sử dụng URL chuẩn từ Laravel (Cẩn thận dấu gạch chéo)
                    form.action = `{{ url('admin/product') }}/${id}`;

                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                    `;

                    document.body.appendChild(form);
                    form.submit();
                });
            });
            // 1. Xử lý Khôi phục (Sử dụng PATCH)
            document.querySelectorAll('.btn-restore').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!confirm('Khôi phục sản phẩm này về danh sách đang bán?')) return;

                    const id = this.dataset.id;
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('admin/product') }}/${id}/restore`;
                    form.innerHTML = `
            @csrf
            @method('PATCH')
        `;
                    document.body.appendChild(form);
                    form.submit();
                });
            });

            // 2. Xử lý Xóa vĩnh viễn (Sử dụng DELETE)
            document.querySelectorAll('.btn-force-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!confirm(
                            'CẢNH BÁO: Sản phẩm này sẽ bị xóa vĩnh viễn khỏi hệ thống và không thể hoàn tác. Bạn chắc chắn chứ?'
                        )) return;

                    const id = this.dataset.id;
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('admin/product') }}/${id}/force-delete`;
                    form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        });
    </script>
@endsection
