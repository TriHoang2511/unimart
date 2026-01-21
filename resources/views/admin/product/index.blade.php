@extends('layouts.admin')

@section('title', 'Danh sách sản phẩm')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card shadow-sm border-0">
            {{-- HEADER --}}
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold text-uppercase"><i class="fa fa-boxes me-2"></i>Quản lý sản phẩm</h5>
                <a href="{{ route('admin.product.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fa fa-plus-circle"></i> Thêm sản phẩm mới
                </a>
            </div>

            <div class="card-body">
                {{-- ANALYTIC (Dạng tab đơn giản) --}}
                <div class="analytic mb-4">
                    <a href="#" class="me-3 fw-bold text-decoration-none text-primary">
                        Tất cả <span class="badge bg-secondary">120</span>
                    </a>
                    <a href="#" class="me-3 fw-bold text-decoration-none text-muted">
                        Đang bán <span class="badge bg-success">85</span>
                    </a>
                    <a href="#" class="me-3 fw-bold text-decoration-none text-muted">
                        Hết hàng <span class="badge bg-warning text-dark">35</span>
                    </a>
                    <a href="#" class="me-3 fw-bold text-decoration-none text-muted">
                        Thùng rác <span class="badge bg-danger">5</span>
                    </a>
                </div>

                {{-- BỘ LỌC TÌM KIẾM (FILTER) --}}
                <div class="filter-box bg-light p-3 rounded mb-4">
                    <form action="" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="small fw-bold">Tìm kiếm</label>
                            <input type="text" name="keyword" class="form-control" placeholder="Tên sản phẩm, SKU...">
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold">Danh mục</label>
                            <select class="form-select">
                                <option value="">-- Tất cả danh mục --</option>
                                {{-- Sau này lặp danh mục ở đây --}}
                                <option>Điện thoại</option>
                                <option>Laptop</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold">Trạng thái</label>
                            <select class="form-select">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="1">Đang bán</option>
                                <option value="0">Ngừng bán</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fa fa-filter"></i> Lọc dữ liệu
                            </button>
                        </div>
                    </form>
                </div>

                {{-- TÁC VỤ HÀNG LOẠT --}}
                <div class="bulk-actions mb-3 d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm w-auto">
                        <option value="">-- Tác vụ --</option>
                        <option value="delete">Xóa tạm thời</option>
                        <option value="active">Cập nhật trạng thái</option>
                    </select>
                    <button class="btn btn-sm btn-secondary">Áp dụng</button>
                </div>

                {{-- TABLE DỮ LIỆU --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light text-uppercase small fw-bold">
                            <tr>
                                <th width="30"><input type="checkbox" id="checkAll"></th>
                                <th width="50">#</th>
                                <th width="80">Ảnh</th>
                                <th>Thông tin sản phẩm</th>
                                <th>Giá bán (Min-Max)</th>
                                <th>Kho hàng</th>
                                <th>Danh mục</th>
                                <th>Ngày tạo</th>
                                <th width="100">Trạng thái</th>
                                <th width="110">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Ví dụ dòng dữ liệu 1 --}}
                            <tr>
                                <td><input type="checkbox" class="checkItem"></td>
                                <td>1</td>
                                <td>
                                    <img src="https://via.placeholder.com/60x60" class="rounded border shadow-sm"
                                        alt="">
                                </td>
                                <td>
                                    <a href="#" class="fw-bold text-decoration-none text-dark d-block">iPhone 15 Pro
                                        Max 256GB</a>
                                    <div class="text-muted small">
                                        SKU: <span class="text-uppercase">IP15PM-256</span> |
                                        <span class="text-info">4 biến thể</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-danger fw-bold">28.990.000₫</span>
                                    <div class="text-muted small text-decoration-line-through">32.000.000₫</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">Còn 45</span>
                                </td>
                                <td><span class="badge bg-info-subtle text-info border border-info-subtle px-2">Điện
                                        thoại</span></td>
                                <td class="small">12/01/2026</td>
                                <td>
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle p-1 px-2">
                                        <i class="fa fa-check-circle me-1"></i>Đang bán
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-outline-success" title="Sửa"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-danger" title="Xóa"><i
                                                class="fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>

                            {{-- Ví dụ dòng dữ liệu 2 --}}
                            <tr>
                                <td><input type="checkbox" class="checkItem"></td>
                                <td>2</td>
                                <td>
                                    <img src="https://via.placeholder.com/60x60" class="rounded border shadow-sm"
                                        alt="">
                                </td>
                                <td>
                                    <a href="#" class="fw-bold text-decoration-none text-dark d-block">MacBook Air
                                        M2 8GB/256GB</a>
                                    <div class="text-muted small">
                                        SKU: <span class="text-uppercase">MBA-M2-01</span> |
                                        <span class="text-info">2 biến thể</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-danger fw-bold">24.500.000₫</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2">Hết
                                        hàng</span>
                                </td>
                                <td><span
                                        class="badge bg-info-subtle text-info border border-info-subtle px-2">Laptop</span>
                                </td>
                                <td class="small">10/01/2026</td>
                                <td>
                                    <span
                                        class="badge bg-secondary-subtle text-secondary border border-secondary-subtle p-1 px-2">
                                        Tạm ngưng
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-outline-success"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-danger"><i
                                                class="fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- PHÂN TRANG (PAGINATION) --}}
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div class="small text-muted">Hiển thị <b>1-10</b> trong tổng số <b>120</b> sản phẩm</div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom nhẹ cho giao diện Ecommerce chuyên nghiệp */
        .analytic a {
            font-size: 14px;
            transition: all 0.3s;
        }

        .analytic a:hover {
            color: #0d6efd !important;
        }

        .table thead th {
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .img-thumbnail {
            object-fit: cover;
        }

        .filter-box {
            border: 1px solid #e3e6f0;
        }
    </style>

    <script>
        // JS Chọn tất cả checkbox
        document.getElementById('checkAll').onclick = function() {
            var checkboxes = document.querySelectorAll('.checkItem');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }
    </script>
@endsection
