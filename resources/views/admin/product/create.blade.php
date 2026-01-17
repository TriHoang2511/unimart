@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
    <div class="container-fluid py-3">

        {{-- PAGE HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Thêm sản phẩm mới</h4>
                <div class="text-muted small">Cấu hình thông tin cơ bản và các biến thể sản phẩm</div>
            </div>
            <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fa fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>

        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">

                {{-- CỘT TRÁI: THÔNG TIN CHÍNH --}}
                <div class="col-lg-8">

                    {{-- THÔNG TIN CƠ BẢN --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="fa fa-info-circle me-2 text-primary"></i>Thông tin chung
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="product_name" class="form-control" value="{{ old('name') }}"
                                    placeholder="Ví dụ: Samsung Galaxy S24 Ultra" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Đường dẫn (Slug)</label>
                                    <input type="text" name="slug" id="product_slug" class="form-control bg-light" value="{{ old('slug') }}"
                                        placeholder="VD: iphone-16" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Mã sản phẩm (Base SKU)</label>
                                    <input type="text" name="sku" class="form-control" value="{{ old('sku') }}"
                                        placeholder="SS-S24U">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mô tả ngắn</label>
                                <textarea name="summary" class="form-control" rows="3" placeholder="Tóm tắt ngắn gọn về sản phẩm">{{ old('summary') }}</textarea>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-semibold">Nội dung chi tiết</label>

                                {{-- Toolbar --}}
                                <div class="border rounded-top p-2 bg-light">
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        onclick="window.editor.chain().focus().toggleBold().run()">B</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        onclick="window.editor.chain().focus().toggleHeading({ level: 2 }).run()">H2</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        onclick="window.editor.chain().focus().toggleBulletList().run()">• List</button>
                                </div>

                                {{-- Editor --}}
                                <div id="tiptap-editor" class="border rounded-bottom p-3" style="min-height: 250px;"></div>

                                {{-- Hidden input để submit --}}
                                <input type="hidden" name="description" id="description-input"
                                    value="{{ old('description') }}">
                            </div>
                        </div>
                    </div>

                    {{-- THUỘC TÍNH SẢN PHẨM --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="fa fa-tags me-2 text-primary"></i>Thuộc tính & Biến thể
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info small">
                                <i class="fa fa-lightbulb me-2"></i>Chọn danh mục để hiển thị các thuộc tính tương ứng.
                            </div>
                            {{-- (Giữ nguyên phần demo thuộc tính của bạn hoặc render động tại đây) --}}
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI: CẤU HÌNH PHỤ --}}
                <div class="col-lg-4">
                    {{-- XUẤT BẢN --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold py-3">Trạng thái</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <select name="status" class="form-select border-primary-subtle shadow-sm">
                                    <option value="active">Đang bán (Công khai)</option>
                                    <option value="draft">Lưu nháp</option>
                                    <option value="pending">Chờ duyệt</option>
                                </select>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">
                                    <i class="fa fa-save me-1"></i> LƯU SẢN PHẨM
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- DANH MỤC --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold py-3">Danh mục sản phẩm</div>
                        <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                            @foreach ($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="category_id"
                                        id="cat_{{ $category->id }}" value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'checked' : '' }}
                                        {{ $category->has_child ? 'disabled' : '' }}>
                                    <label class="form-check-label fw-semibold" for="cat_{{ $category->id }}">
                                        {{ str_repeat('--|', $category->level) }} {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- HÌNH ẢNH --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold py-3">Ảnh đại diện</div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img id="img-preview" src="https://via.placeholder.com/150x150?text=Sản+phẩm"
                                    class="img-thumbnail shadow-sm rounded-3"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <input type="file" name="thumbnail" id="thumbnail-input" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('styles')
        <style>
            .ProseMirror { min-height: 250px; outline: none; cursor: text; background: #fff; }
            .ProseMirror p { margin: 0; }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Kiểm tra thư viện từ app.js
                if (!window.TiptapEditor) {
                    console.error('❌ Tiptap chưa được load từ app.js. Kiểm tra lại npm run dev');
                    return;
                }

                // 1. Khởi tạo Editor
                window.editor = new window.TiptapEditor({
                    element: document.querySelector('#tiptap-editor'),
                    extensions: [ window.TiptapStarterKit ],
                    content: document.getElementById('description-input').value,
                    onUpdate({ editor }) {
                        document.getElementById('description-input').value = editor.getHTML();
                    },
                });

                // 2. Logic Auto-Slug
                const nameInput = document.getElementById('product_name');
                const slugInput = document.getElementById('product_slug');

                nameInput.addEventListener('input', () => {
                    let slug = nameInput.value.toLowerCase()
                        .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Khử dấu tiếng Việt
                        .replace(/[^\w ]+/g, '')
                        .replace(/ +/g, '-');
                    slugInput.value = slug;
                });

                // 3. Logic Preview Ảnh
                const thumbInput = document.getElementById('thumbnail-input');
                const thumbPreview = document.getElementById('img-preview');

                thumbInput.onchange = evt => {
                    const [file] = thumbInput.files;
                    if (file) {
                        thumbPreview.src = URL.createObjectURL(file);
                    }
                };
            });
        </script>
    @endpush
@endsection