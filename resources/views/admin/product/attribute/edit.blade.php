@extends('layouts.admin')
@section('title', 'Cập nhật thuộc tính')

@section('content')
<div id="content" class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 mb-0 text-gray-800 font-weight-bold">
            <i class="fas fa-sliders-h mr-2 text-primary"></i>Cập nhật thuộc tính
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.product.attributes.index') }}">Thuộc tính</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
            </ol>
        </nav>
    </div>

    <div class="row">

        {{-- CỘT TRÁI: FORM UPDATE --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm border-0 mb-4 border-top-primary">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Thông tin thuộc tính
                    </h6>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.product.attributes.update', $attribute->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold text-dark">
                                Tên thuộc tính
                            </label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Ví dụ: Màu sắc, Size"
                                   value="{{ old('name', $attribute->name) }}">

                            <small class="form-text text-muted font-italic">
                                Thuộc tính dùng để phân loại sản phẩm (variant)
                            </small>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.product.attributes.index') }}"
                               class="btn btn-light btn-sm font-weight-bold">
                                <i class="fas fa-arrow-left mr-1"></i> Quay lại
                            </a>
                            <button type="submit"
                                    class="btn btn-primary btn-sm px-4 shadow-sm font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: GIÁ TRỊ THUỘC TÍNH --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-dark">
                        Giá trị của thuộc tính
                    </h6>
                    <span class="badge badge-primary badge-pill px-3">
                        {{ $attribute->values->count() }} giá trị
                    </span>
                </div>

                <div class="card-body">

                    {{-- FORM THÊM GIÁ TRỊ --}}
                    <form action="{{ route('admin.product.attributes.values.store', $attribute->id) }}"
                          method="POST" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input type="text"
                                   name="value"
                                   class="form-control"
                                   placeholder="Ví dụ: Đỏ, Xanh, XL..."
                                   required>
                            <div class="input-group-append">
                                <button class="btn btn-success">
                                    <i class="fas fa-plus"></i> Thêm
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- DANH SÁCH VALUE --}}
                    <div class="d-flex flex-wrap">
                        @forelse ($attribute->values as $val)
                            <span class="badge badge-light border p-2 mr-2 mb-2">
                                {{ $val->value }}
                                <form action="{{ route('admin.product.attributes.values.destroy', $val->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-danger border-0 bg-transparent ml-1"
                                            onclick="return confirm('Xóa giá trị này?')">
                                        &times;
                                    </button>
                                </form>
                            </span>
                        @empty
                            <p class="text-muted font-italic">Chưa có giá trị nào</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .border-top-primary { border-top: 4px solid #4e73df !important; }
    .form-control { border-radius: .35rem; font-size: .9rem; }
    .badge { border-radius: .35rem; font-size: .85rem; }
</style>
@endsection
