@extends('layouts.admin') {{-- Thay bằng layout admin của bạn --}}

@section('content')
<div id="content" class="container-fluid">
    <div class="row">
        {{-- CỘT TRÁI: THÊM MỚI THUỘC TÍNH --}}
        <div class="col-4">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Thêm thuộc tính gốc
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.product.attribute.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên thuộc tính (Ví dụ: Màu sắc, Size)</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name" placeholder="Nhập tên thuộc tính...">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: DANH SÁCH VÀ THÊM GIÁ TRỊ --}}
        <div class="col-8">
            <div class="card">
                <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                    Danh sách thuộc tính và giá trị
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col" width="5%">#</th>
                                <th scope="col" width="20%">Thuộc tính</th>
                                <th scope="col" width="45%">Các giá trị</th>
                                <th scope="col" width="30%">Thêm giá trị nhanh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($attributes->count() > 0)
                                @foreach ($attributes as $index => $attr)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong class="text-primary">{{ $attr->name }}</strong></td>
                                        <td>
                                            @foreach ($attr->values as $val)
                                                <span class="badge badge-secondary p-2 mb-1" style="background: #e9ecef; color: #495057; border: 1px solid #ced4da;">
                                                    {{ $val->value }}
                                                    {{-- Nút xóa giá trị --}}
                                                    <a href="{{ route('admin.product.attribute.destroyValue', $val->id) }}" 
                                                       onclick="return confirm('Xóa giá trị này?')"
                                                       class="text-danger ml-1" style="text-decoration: none;">&times;</a>
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{-- Form thêm nhanh giá trị --}}
                                            <form action="{{ route('admin.product.attribute.storeValue') }}" method="POST" class="form-inline">
                                                @csrf
                                                <input type="hidden" name="attribute_id" value="{{ $attr->id }}">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="value" class="form-control" placeholder="Ví dụ: Đỏ..." required>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-success" type="submit">+</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">Chưa có thuộc tính nào được tạo.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .badge {
        display: inline-block;
        margin-right: 5px;
        border-radius: 4px;
    }
    .input-group-sm .form-control {
        height: 31px;
    }
</style>