@extends('layouts.admin')

@section('title', 'Quản lý thuộc tính sản phẩm')

@section('content')
    <div class="container-fluid">
        <div class="row">

            {{-- CỘT TRÁI: THÊM THUỘC TÍNH --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm thuộc tính gốc
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.product.attributes.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Tên thuộc tính</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Ví dụ: Màu sắc, Size" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button class="btn btn-primary btn-sm mt-2">
                                Thêm thuộc tính
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: DANH SÁCH --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách thuộc tính & giá trị
                    </div>

                    <div class="card-body p-0">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show mx-3 mt-3">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        <table class="table table-bordered table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Thuộc tính</th>
                                    <th width="40%">Giá trị</th>
                                    <th width="30%">Thêm giá trị</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attributes as $attr)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        {{-- TÊN THUỘC TÍNH --}}
                                        <td>
                                            <strong>{{ $attr->name }}</strong>
                                            <div class="mt-1">
                                                <a href="{{ route('admin.product.attributes.edit', $attr->id) }}"
                                                    class="btn btn-xs btn-outline-secondary">
                                                    Sửa
                                                </a>
                                            </div>
                                        </td>

                                        {{-- DANH SÁCH VALUE --}}
                                        <td>
                                            @forelse($attr->values as $val)
                                                <span class="badge badge-light border mr-1 mb-1 p-2">
                                                    {{ $val->value }}
                                                    <span class="text-primary">({{ $val->value_code }})</span>

                                                    <form
                                                        action="{{ route('admin.product.attributes.values.destroy', $val->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="border-0 bg-transparent text-danger"
                                                            onclick="return confirm('Xóa giá trị này?')">
                                                            &times;
                                                        </button>
                                                    </form>
                                                </span>
                                            @empty
                                                <small class="text-muted">Chưa có giá trị</small>
                                            @endforelse
                                        </td>

                                        {{-- FORM THÊM VALUE --}}
                                        <td>
                                            <form action="{{ route('admin.product.attributes.values.store', $attr->id) }}"
                                                method="POST" class="d-flex align-items-center gap-1">
                                                @csrf

                                                <input type="text" name="value"
                                                    class="form-control form-control-sm mr-1" placeholder="Tên (Đỏ)"
                                                    required>

                                                <input type="text" name="value_code"
                                                    class="form-control form-control-sm sku-input" placeholder="SKU (RED)"
                                                    maxlength="10" required>

                                                <button class="btn btn-success btn-sm">+</button>
                                            </form>

                                            <small class="text-muted">
                                                SKU: viết hoa, không dấu
                                            </small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            Chưa có thuộc tính nào
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
                .replace(/[^A-Z0-9]/g, '');

            e.target.value = v;
        });
    </script>
@endpush
