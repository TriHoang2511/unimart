@extends('layouts.admin')

@php
    /**
     * Render option cho select Danh mục cha (đệ quy)
     */
    function renderCategoryOptions($categories, $level = 0, $selectedId = null)
    {
        foreach ($categories as $cat) {
            echo '<option value="'.$cat->id.'"'.
                ($selectedId == $cat->id ? ' selected' : '').
                '>'.
                str_repeat('-- ', $level).e($cat->name).
                '</option>';

            if ($cat->children->isNotEmpty()) {
                renderCategoryOptions($cat->children, $level + 1, $selectedId);
            }
        }
    }

    /**
     * Render bảng danh mục (đệ quy) – CHỈ DÙNG CHO TAB ACTIVE
     */
    function renderCategoryRows($categories, $level = 0)
    {
        foreach ($categories as $cat) {
@endphp

<tr>
    <td>{{ $cat->id }}</td>

    <td>
        @if ($cat->thumbnail)
            <img src="{{ asset('storage/'.$cat->thumbnail) }}" width="40" class="img-thumbnail">
        @endif
    </td>

    <td>
    {!! str_repeat('|— ', $level) !!}
    <strong>{{ $cat->name }}</strong>
</td>

<td>
    @if ($cat->parent)
        <span class="text-dark">{{ $cat->parent->name }}</span>
    @else
        <span class="badge badge-light p-2">Danh mục gốc</span>
    @endif
</td>

    <td>{{ $cat->sort_order }}</td>

    <td>
        @if ($cat->status)
            <span class="badge badge-success">Công khai</span>
        @else
            <span class="badge badge-secondary">Ẩn</span>
        @endif
    </td>

    <td>
        <a href="{{ route('admin.product.cat.edit', $cat->id) }}"
           class="btn btn-success btn-sm">
            <i class="fa fa-edit"></i>
        </a>

        @if ($cat->slug !== 'khac')
            <form action="{{ route('admin.product.cat.destroy', $cat->id) }}"
                  method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Bạn chắc chắn muốn xóa danh mục này?')">
                    <i class="fa fa-trash"></i>
                </button>
            </form>
        @endif
    </td>
</tr>

@php
            if ($cat->children->isNotEmpty()) {
                renderCategoryRows($cat->children, $level + 1);
            }
        }
    }
@endphp

@section('title', 'Danh sách danh mục sản phẩm')

@section('content')
<div id="content" class="container-fluid">
    <div class="row">

        {{-- CỘT TRÁI: FORM THÊM --}}
        <div class="col-4">
            @if ($status === 'active')
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm danh mục
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.product.cat.store') }}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf

                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="form-group">
                                <label>Tên danh mục</label>
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ old('name') }}">
                            </div>

                            <div class="form-group">
                                <label>Slug</label>
                                <input type="text"
                                       name="slug"
                                       class="form-control"
                                       value="{{ old('slug') }}">
                            </div>

                            <div class="form-group">
                                <label>Danh mục cha</label>
                                <select name="parent_id" class="form-control">
                                    <option value="">Không có</option>
                                    @php
                                        renderCategoryOptions(
                                            $categories,
                                            0,
                                            old('parent_id')
                                        )
                                    @endphp
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Thứ tự</label>
                                <input type="number"
                                       name="sort_order"
                                       class="form-control"
                                       value="{{ old('sort_order', 10) }}">
                            </div>

                            <div class="form-group">
                                <label>Ảnh</label>
                                <input type="file"
                                       name="thumbnail"
                                       class="form-control-file">
                            </div>

                            <div class="form-group">
                                <label>Mô tả</label>
                                <textarea name="description"
                                          class="form-control"
                                          rows="3">{{ old('description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Trạng thái</label><br>
                                <label>
                                    <input type="radio"
                                           name="status"
                                           value="1"
                                           {{ old('status', '1') === '1' ? 'checked' : '' }}>
                                    Công khai
                                </label>
                                &nbsp;
                                <label>
                                    <input type="radio"
                                           name="status"
                                           value="0"
                                           {{ old('status', '1') === '0' ? 'checked' : '' }}>
                                    Ẩn
                                </label>
                            </div>

                            <button class="btn btn-primary w-100">
                                Thêm mới
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- CỘT PHẢI: DANH SÁCH --}}
        <div class="col-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Danh sách danh mục
                </div>

                <div class="card-body">

                    {{-- TABS --}}
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'active' ? 'active' : '' }}"
                               href="{{ route('admin.product.cat.index') }}">
                                Đang hoạt động
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'trash' ? 'active' : '' }}"
                               href="{{ route('admin.product.cat.index', ['status' => 'trash']) }}">
                                Đã xóa
                            </a>
                        </li>
                    </ul>

                    {{-- TABLE --}}
                    @if ($status === 'trash')
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên danh mục</th>
                                    <th>Ngày xóa</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $cat)
                                    <tr>
                                        <td>{{ $cat->id }}</td>
                                        <td>{{ $cat->name }}</td>
                                        <td>{{ $cat->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <form action="{{ route('admin.product.cat.restore', $cat->id) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                <button class="btn btn-info btn-sm">
                                                    Khôi phục
                                                </button>
                                            </form>

                                            @if ($cat->slug !== 'khac')
                                                <form action="{{ route('admin.product.cat.forceDelete', $cat->id) }}"
                                                      method="POST"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Xóa vĩnh viễn?')">
                                                        Xóa vĩnh viễn
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ảnh</th>
                                    <th>Tên danh mục</th>
                                    <th>Danh mục cha</th>
                                    <th>Thứ tự</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php renderCategoryRows($categories) @endphp
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
