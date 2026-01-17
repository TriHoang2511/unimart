@extends('layouts.admin')

@section('title', 'Cập nhật danh mục sản phẩm')

@section('content')
    <div id="content" class="container-fluid">
        <div class="row justify-content-center">

            <div class="col-xl-6 col-lg-8">
                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white font-weight-bold">
                        Cập nhật danh mục sản phẩm
                    </div>

                    <div class="card-body">

                        {{-- CẢNH BÁO DANH MỤC HỆ THỐNG --}}
                        @if ($category->slug === 'khac')
                            <div class="alert alert-warning">
                                <strong>Danh mục "Khác"</strong> là danh mục hệ thống.
                                Bạn chỉ có thể chỉnh <b>ảnh đại diện</b> và <b>mô tả</b>.
                            </div>
                        @endif

                        <form action="{{ route('admin.product.cat.update', $category->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- TÊN --}}
                            <div class="form-group mb-3">
                                <label class="fw-bold">Tên danh mục</label>
                                <input type="text" name="name" class="form-control" value="{{ $category->name }}"
                                    {{ $category->slug === 'khac' ? 'disabled' : '' }}>
                            </div>

                            {{-- SLUG --}}
                            <div class="form-group mb-3">
                                <label class="fw-bold">Slug</label>
                                <input type="text" name="slug" class="form-control" value="{{ $category->slug }}"
                                    {{ $category->slug === 'khac' ? 'readonly' : '' }}>
                            </div>

                            {{-- DANH MỤC CHA --}}
                            <div class="form-group mb-3">
                                <label class="fw-bold">Danh mục cha</label>
                                <select name="parent_id" class="form-control"
                                    {{ $category->slug === 'khac' ? 'disabled' : '' }}>
                                    <option value="">— Danh mục gốc —</option>
                                    @foreach ($categories as $cat)
                                        @if ($cat->id !== $category->id)
                                            <option value="{{ $cat->id }}"
                                                {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                                                {{ str_repeat('— ', $cat->level ?? 0) . $cat->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            {{-- THỨ TỰ --}}
                            <div class="form-group mb-3">
                                <label class="fw-bold">Thứ tự hiển thị</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ $category->sort_order }}" {{ $category->slug === 'khac' ? 'disabled' : '' }}>
                            </div>

                            {{-- THUMBNAIL --}}
                            <div class="form-group mb-3">
                                <label class="fw-bold">Ảnh đại diện</label>

                                @if ($category->thumbnail)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $category->thumbnail) }}" class="img-thumbnail"
                                            width="120">
                                    </div>
                                @endif

                                <input type="file" name="thumbnail" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label class="fw-bold">Mô tả</label>
                                <textarea name="description" class="form-control" rows="3"
                                    {{ $category->description === 'khac' ? 'disabled' : '' }}>{{ $category->description }}</textarea>
                            </div>

                            {{-- TRẠNG THÁI --}}
                            <div class="form-group mb-4">
                                <label class="fw-bold d-block">Trạng thái</label>

                                <div class="form-check form-check-inline">
                                    <input type="radio" name="status" id="active" value="1"
                                        {{ $category->status == 1 ? 'checked' : '' }}
                                        {{ $category->slug === 'khac' ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="active">Công khai</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input type="radio" name="status" id="inactive" value="0"
                                        {{ $category->status == 0 ? 'checked' : '' }}
                                        {{ $category->slug === 'khac' ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="inactive">Ẩn</label>
                                </div>
                            </div>

                            {{-- THUỘC TÍNH ÁP DỤNG --}}
                            @if ($category->slug !== 'khac')
                                <div class="card mt-4 border-0 shadow-sm">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0 fw-bold">Quy tắc thuộc tính cho danh mục</h6>
                                        <small class="text-muted">
                                            Các thuộc tính được chọn sẽ hiển thị khi tạo sản phẩm thuộc danh mục này
                                        </small>
                                    </div>

                                    <div class="card-body">

                                        @foreach ($attributes as $attr)
                                            <div class="border rounded p-3 mb-3">

                                                {{-- CHECKBOX + NAME --}}
                                                <div class="form-check mb-2 align-items-center d-flex">
                                                    <input class="form-check-input" type="checkbox" name="attribute_ids[]"
                                                        value="{{ $attr->id }}" id="attr_{{ $attr->id }}"
                                                        {{ in_array($attr->id, $selectedAttributes) ? 'checked' : '' }}>

                                                    <label class="form-check-label fw-bold u-cursor-pointer"
                                                        for="attr_{{ $attr->id }}">
                                                        {{ $attr->name }}
                                                    </label>
                                                </div>

                                                {{-- DESCRIPTION --}}
                                                @if ($attr->description)
                                                    <div class="text-muted small mb-2">
                                                        {{ $attr->description }}
                                                    </div>
                                                @endif

                                                {{-- METADATA --}}
                                                <div class="small text-muted mb-2">
                                                    Loại: <strong>{{ ucfirst($attr->type) }}</strong>
                                                    @if ($attr->unit)
                                                        • Đơn vị: <strong>{{ $attr->unit }}</strong>
                                                    @endif
                                                </div>

                                                {{-- VALUES PREVIEW --}}
                                                @if ($attr->values->count())
                                                    <div class="d-flex flex-wrap attr-values">
                                                        @foreach ($attr->values as $value)
                                                            <span class="badge bg-light text-dark border px-2 py-1">
                                                                {{ $value->value }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif

                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            @endif

                            {{-- ACTION --}}
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.product.cat.index') }}" class="btn btn-outline-secondary">
                                    ← Quay lại
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    {{ $category->slug === 'khac' ? 'Cập nhật hiển thị' : 'Cập nhật danh mục' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .u-cursor-pointer {
            cursor: pointer;
        }

        .attr-values {
            gap: 8px;
        }
    </style>
@endsection
