@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
    <style>
        /* 1. Thu gọn và cố định các ô input/select trong bảng biến thể */
        .variant-table input.form-control-sm,
        .variant-table select.form-select-sm {
            padding: 4px 6px;
            font-size: 13px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            display: inline-block;
        }

        /* 2. Cố định chiều rộng cụ thể để tiêu đề và nội dung luôn thẳng hàng */
        .variant-table input[type="number"].variant-price,
        .variant-table input[type="number"].variant-compare-price {
            width: 95px;
            /* Độ rộng cho ô Giá bán và Giá gốc */
        }

        .variant-table input[type="number"].variant-stock {
            width: 70px;
            /* Độ rộng cho ô Kho hàng */
        }

        .variant-table input[name="v_sku[]"] {
            width: 120px;
            /* Độ rộng cho ô SKU */
        }

        .variant-table select {
            width: 110px;
            /* Độ rộng cho Cung ứng và Trạng thái */
        }

        /* 3. Khống chế phần chữ của tên biến thể không làm vỡ hàng */
        .variant-name-text {
            max-width: 160px;
            display: block;
            word-wrap: break-word;
            line-height: 1.2;
        }

        /* 4. Căn giữa các icon trong bảng */
        .variant-table .variant-img-slot {
            margin: 0 auto;
        }

        .variant-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-bottom: 12px;
            margin-bottom: 12px;
            border-bottom: 1px dashed #dee2e6;
        }

        .variant-bulk {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .variant-bulk input {
            width: 120px;
        }

        /* ============================================================
                                                                                        VARIANT TABLE STYLE
                                                                    ============================================================ */
        .variant-table thead {
            background: #f8fafc;
        }

        .variant-table th {
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: #555;
            vertical-align: middle;
            border-top: none;
            padding: 10px 5px !important;
            /* Dùng bản rút gọn để kiểm soát chính xác độ hẹp */
        }

        .variant-table td {
            padding: 8px 5px !important;
            vertical-align: middle;
        }

        .variant-table .form-control,
        .variant-table .form-select {
            padding: 0.4rem 0.5rem;
            font-size: 0.85rem;
            border-radius: 4px;
        }

        /* Slot ảnh biến thể */
        .variant-img-slot {
            width: 45px;
            height: 45px;
            border: 1px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 4px;
            background: #f9f9f9;
            margin: 0 auto;
            /* Căn giữa trong ô */
        }

        /* =========================
                                                                        VARIANT IMAGE SLOT
                                                                     ========================== */
        .variant-img-slot {
            width: 45px;
            height: 45px;
            border-radius: 8px;
            border: 2px dashed #cbd5e1;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
        }

        .variant-img-slot img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* =========================
                                                                        MEDIA MANAGER
                                                                    ========================== */
        .image-card {
            border: 2px solid transparent;
            transition: 0.2s;
            position: relative;
        }

        .is-main .image-card {
            border-color: #0d6efd;
            background-color: #f0f7ff;
        }

        .delete-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: #fff;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 12px;
            border: 2px solid #fff;
            z-index: 10;
            line-height: 1;
        }

        #gallery-sortable .sort-item {
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            border-radius: 6px;
            position: relative;
            cursor: grab;
        }

        #gallery-sortable .sort-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        /* =========================
                                                                        ATTRIBUTE (CHIPS)
                                                    ========================== */
        :root {
            --primary-color: #4361ee;
        }

        .attribute-group {
            background: #fff;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .attribute-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .attribute-name i {
            color: var(--primary-color);
        }

        .chips-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .attribute-checkbox {
            display: none;
        }

        .attribute-label {
            padding: 8px 16px;
            background: #f1f5f9;
            border: 2px solid #f1f5f9;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: 0.2s;
            user-select: none;
        }

        .attribute-checkbox:checked+.attribute-label {
            background: #eef2ff;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .variant-table input[name="v_full_name[]"] {
            width: 100%;
            min-width: 200px;
            border: 1px solid #e2e8f0;
            background-color: #fff;
        }
    </style>

    <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Thêm sản phẩm mới</h4>
                <p class="text-muted small mb-0">Quản lý kho ảnh tập trung và biến thể thông minh</p>
            </div>
            <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i>
                Quay lại</a>
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.product.store') }}" method="POST" id="productForm">
            @csrf
            <div class="row g-4">
                <div class="col-lg-8">
                    {{-- THÔNG TIN CHUNG --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold py-3"><i
                                class="fa fa-info-circle me-2 text-primary"></i>Thông tin chung</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tên sản phẩm <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="product_name" class="form-control" required
                                    value="{{ old('name') }}">
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Slug (Đường dẫn)</label>
                                    <div class="input-group">
                                        <input type="text" id="product_slug" name="slug" class="form-control"
                                            value="{{ old('slug') }}" placeholder="Tự động tạo theo tên...">
                                        <button class="btn btn-outline-primary" type="button" id="btn-lock-slug"
                                            title="Mở/Khóa tự động">
                                            <i class="fa fa-link"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Mặc định sẽ tự động tạo. Nhấn vào biểu tượng mắt xích để tự
                                        sửa tay.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mã SKU gốc</label>
                                    <input type="text" name="sku" id="base_sku" class="form-control"
                                        placeholder="VD: IP16" value="{{ old('sku') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tóm tắt ngắn (SEO & Quickview)</label>
                                <textarea name="technical_specifications" class="form-control" rows="3"
                                    placeholder="Nhập mô tả ngắn tối đa 255 ký tự...">{{ old('technical_specifications') }}</textarea>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-semibold">Mô tả chi tiết</label>
                                <textarea name="description" id="product-description" class="form-control">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- QUẢN LÝ ẢNH (MEDIA MANAGER) --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <span class="fw-bold"><i class="fa fa-images me-2 text-primary"></i>Kho ảnh sản phẩm</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('ajax-upload').click()">
                                    <i class="fa fa-upload me-1"></i>Tải ảnh mới
                                </button>
                                <input type="file" id="ajax-upload" class="d-none" multiple accept="image/*">
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="media-pool" class="row g-3 mb-4">
                                <div class="col-12 text-center py-4 border rounded bg-light" id="empty-media">
                                    <p class="text-muted mb-0">Chưa có ảnh nào được tải lên.</p>
                                </div>
                            </div>

                            <hr>
                            <h6 class="fw-bold small mb-3 text-uppercase text-secondary">Sắp xếp Album (Kéo thả để sắp xếp)
                            </h6>
                            <div id="gallery-sortable" class="d-flex flex-wrap gap-2 p-3 border rounded bg-light"
                                style="min-height: 120px;">
                            </div>
                        </div>
                    </div>

                    {{-- BIẾN THỂ --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <span class="fw-bold"><i class="fa fa-tags me-2 text-primary"></i>Biến thể</span>
                            <button type="button" class="btn btn-sm btn-primary" onclick="generateVariants()"><i
                                    class="fa fa-magic"></i> Tạo biến thể</button>
                        </div>
                        <div class="card-body">
                            <div id="attributes-container">
                                <p class="text-muted small">Vui lòng chọn danh mục để hiển thị thuộc tính.</p>
                            </div>

                            <div id="variants-preview" class="mt-4 d-none">
                                <div class="variant-header">
                                    <div class="variant-bulk">
                                        <input type="number" id="bulk-price" class="form-control form-control-sm"
                                            placeholder="Giá bán">
                                        <input type="number" id="bulk-compare-at-price"
                                            class="form-control form-control-sm" placeholder="Giá gốc">
                                        <input type="number" id="bulk-stock" class="form-control form-control-sm"
                                            placeholder="Kho">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="applyBulk()">Áp dụng</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle variant-table" style="min-width: 1000px;">
                                        <thead>
                                            <tr class="bg-light text-uppercase" style="font-size: 0.75rem;">
                                                <th width="70" class="text-center">Ảnh</th>
                                                <th>Sản phẩm đại diện</th>
                                                <th width="180">Biến thể</th>
                                                <th width="300">Tên biến thể hiển thị</th>
                                                <th width="160">SKU</th>
                                                <th width="140">Giá bán</th>
                                                <th width="140">Giá gốc</th>
                                                <th width="90" class="text-center">Kho</th>
                                                <th width="150">Cung ứng</th>
                                                <th width="130">Trạng thái</th>
                                                <th width="50" class="text-center">Mô tả</th>
                                                <th width="50"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="variants-table-body"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold py-3">Xuất bản</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Ảnh đại diện (Thumbnail):</label>
                                <div id="main-preview" class="text-center p-2 border rounded bg-light">
                                    <img src="{{ asset('storage/images/Placeholder_Image.jpg') }}"
                                        class="img-fluid rounded" id="main-img-display" style="max-height: 200px;">
                                    <input type="hidden" name="main_image_url" id="main-img-input">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="published">Đang bán</option>
                                    <option value="pending">Chờ duyệt</option>
                                    <option value="draft">Lưu nháp</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">LƯU SẢN PHẨM</button>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold py-3">Danh mục sản phẩm</div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            @foreach ($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="category_id"
                                        id="cat_{{ $category->id }}" value="{{ $category->id }}"
                                        {{ $category->has_child ? 'disabled' : '' }}>
                                    <label class="form-check-label {{ $category->has_child ? 'text-muted' : '' }}"
                                        for="cat_{{ $category->id }}">
                                        {{ str_repeat('--| ', $category->level) }}{{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="variantDescriptionModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-light">
                                <h5 class="modal-title fw-bold">Mô tả cho biến thể: <span id="variant-name-display"
                                        class="text-primary"></span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tóm tắt ngắn (Biến thể)</label>
                                    <textarea id="modal-v-technical-specifications" class="form-control" rows="3"
                                        placeholder="Thông số nhanh cho riêng biến thể này..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mô tả chi tiết (Biến thể)</label>
                                    <textarea id="variant_description_editor" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <div>
                                    <button type="button" class="btn btn-warning fw-bold"
                                        onclick="applyToAllVariants()">
                                        <i class="fa fa-copy me-1"></i> Áp dụng cho tất cả biến thể
                                    </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Đóng</button>
                                    <button type="button" class="btn btn-primary px-4"
                                        onclick="saveVariantContent()">Lưu thay đổi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Modal chọn ảnh cho biến thể --}}
    <div class="modal fade" id="imagePickerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Chọn ảnh từ kho ảnh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="picker-pool" class="row g-2">
                        <p class="text-center text-muted">Vui lòng tải ảnh lên kho ảnh trước.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- TinyMCE với API Key của bạn --}}
        <script src="https://cdn.tiny.cloud/1/gy0w6zmrjbzf7udvwhd52fevt0wqiz64hjkxwjmvf608cbh4/tinymce/8/tinymce.min.js"
            referrerpolicy="origin"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const nameInput = document.getElementById('product_name');
                const slugInput = document.getElementById('product_slug');
                const btnLock = document.getElementById('btn-lock-slug');

                // Biến trạng thái: true là đang "xích" (tự động), false là "mở" (tự sửa)
                let isAutoSlug = true;

                // Hàm tạo Slug chuẩn
                function generateSlug(text) {
                    return text.toLowerCase()
                        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                        .replace(/[đĐ]/g, 'd')
                        .replace(/([^0-9a-z-\s])/g, '')
                        .replace(/(\s+)/g, '-')
                        .replace(/-+/g, '-')
                        .trim().replace(/^-+|-+$/g, '');
                }

                // 1. Sự kiện khi gõ Tên sản phẩm
                nameInput.addEventListener('input', function() {
                    if (isAutoSlug) {
                        slugInput.value = generateSlug(this.value);
                    }
                });

                // 2. Sự kiện khi nhấn nút Mở/Khóa
                btnLock.addEventListener('click', function() {
                    isAutoSlug = !isAutoSlug; // Đảo trạng thái

                    if (isAutoSlug) {
                        // Chế độ Tự động
                        this.innerHTML = '<i class="fa fa-link"></i>';
                        this.classList.replace('btn-secondary', 'btn-outline-primary');
                        slugInput.value = generateSlug(nameInput.value); // Cập nhật lại ngay theo tên
                        slugInput.setAttribute('readonly', true); // Nên khóa lại để tránh gõ nhầm
                    } else {
                        // Chế độ Tự sửa (Manual)
                        this.innerHTML = '<i class="fa fa-unlink text-danger"></i>';
                        this.classList.replace('btn-outline-primary', 'btn-secondary');
                        slugInput.removeAttribute('readonly');
                        slugInput.focus();
                    }
                });

                // 3. Nếu tự gõ vào Slug thì cũng ép định dạng slug luôn
                slugInput.addEventListener('input', function() {
                    if (!isAutoSlug) {
                        this.value = generateSlug(this.value);
                    }
                });
            });
            let currentVariantTarget = null;
            let uploadedImages = []; // Danh sách object ảnh {id, url, path, name}

            // 1. Khởi tạo TinyMCE
            tinymce.init({
                selector: '#product-description', // Bỏ #product-summary ở đây
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline | link image media table | align lineheight | numlist bullist indent outdent | removeformat',
                height: 400,
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce.triggerSave();
                    });
                }
            });

            // 2. Tạo Slug tự động
            // document.getElementById('product_name').addEventListener('input', function() {
            //     let slug = this.value.toLowerCase()
            //         .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            //         .replace(/[^\w ]+/g, '').replace(/ +/g, '-');
            //     document.getElementById('product_slug').value = slug;
            // });

            // 3. Upload ảnh qua AJAX
            document.getElementById('ajax-upload').addEventListener('change', function() {
                const files = this.files;
                if (files.length === 0) return;

                const formData = new FormData();
                for (let file of files) formData.append('images[]', file);

                fetch("{{ route('admin.product.upload-temp') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('empty-media')?.remove();
                        data.forEach(img => {
                            uploadedImages.push(img);
                            renderMediaItem(img);
                        });
                    });
            });

            function renderMediaItem(img) {
                const html = `
                    <div class="col-md-2 col-4 media-item" id="media-item-${img.id}">
                        <div class="card image-card p-1 shadow-sm">
                            <img src="${img.url}" class="card-img-top rounded" style="height:100px; object-fit:cover">
                            <button type="button" class="delete-btn shadow-sm" onclick="deleteAjaxImage('${img.id}', '${img.name}', '${img.path}')">×</button>
                            <div class="card-body p-1 text-center mt-1">
                                <button type="button" class="btn btn-xs btn-link p-0 small text-decoration-none" onclick="setMainImg('${img.url}', '${img.id}')">Chính</button>
                                <span class="text-muted mx-1">|</span>
                                <button type="button" class="btn btn-xs btn-link p-0 small text-success text-decoration-none" onclick="addToGallery('${img.id}', '${img.url}')">Album</button>
                            </div>
                        </div>
                    </div>`;
                document.getElementById('media-pool').insertAdjacentHTML('beforeend', html);
            }

            // 4. Xóa ảnh (Fix lỗi route)
            function deleteAjaxImage(id, name, path) {
                if (!confirm(`Bạn có muốn xóa ảnh "${name}" khỏi hệ thống?`)) return;

                fetch("/admin/product/delete-temp/" + id, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            path: path,
                            _method: 'DELETE'
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById(`media-item-${id}`).remove();
                        uploadedImages = uploadedImages.filter(i => i.id != id);
                        document.getElementById(`gal-item-${id}`)?.remove();
                        if (document.getElementById('main-img-input').value === uploadedImages.find(i => i.id === id)
                            ?.url) {
                            document.getElementById('main-img-display').src = "https://via.placeholder.com/150";
                            document.getElementById('main-img-input').value = "";
                        }
                    });
            }

            // 5. Quản lý Ảnh chính và Gallery
            function setMainImg(url, id) {
                document.querySelectorAll('.media-item').forEach(el => el.classList.remove('is-main'));
                document.getElementById(`media-item-${id}`).classList.add('is-main');
                document.getElementById('main-img-display').src = url;
                document.getElementById('main-img-input').value = url;
            }

            function addToGallery(id, url) {
                if (document.querySelector(`#gal-item-${id}`)) return;
                const html = `
                    <div class="sort-item" id="gal-item-${id}" data-id="${id}">
                        <img src="${url}">
                        <button type="button" class="delete-btn" onclick="this.parentElement.remove()">×</button>
                        <input type="hidden" name="gallery_images[]" value="${url}">
                    </div>`;
                document.getElementById('gallery-sortable').insertAdjacentHTML('beforeend', html);
            }

            new Sortable(document.getElementById('gallery-sortable'), {
                animation: 150
            });

            // 6. Logic Biến thể
            function openImagePicker(index) {
                currentVariantTarget = index;
                const pool = document.getElementById('picker-pool');
                if (uploadedImages.length === 0) {
                    pool.innerHTML = '<p class="text-center py-4">Kho ảnh trống. Hãy tải ảnh lên trước.</p>';
                } else {
                    pool.innerHTML = uploadedImages.map(img => `
                        <div class="col-3 col-md-2">
                            <div class="card p-1 cursor-pointer h-100 shadow-sm" onclick="selectVariantImg('${img.url}')">
                                <img src="${img.url}" class="card-img-top rounded" style="height:80px; object-fit:cover">
                            </div>
                        </div>`).join('');
                }
                new bootstrap.Modal(document.getElementById('imagePickerModal')).show();
            }

            function selectVariantImg(url) {
                document.getElementById(`v-img-display-${currentVariantTarget}`).innerHTML = `<img src="${url}">`;
                document.getElementById(`v-input-${currentVariantTarget}`).value = url;
                bootstrap.Modal.getInstance(document.getElementById('imagePickerModal')).hide();
            }

            function generateVariants() {
                const productName = document.getElementById('product_name').value.trim(); // Lấy tên sản phẩm gốc
                const baseSku = document.getElementById('base_sku').value.trim().toUpperCase();
                const tbody = document.getElementById('variants-table-body');
                const selected = {};

                if (!productName) {
                    alert('Vui lòng nhập tên sản phẩm gốc trước!');
                    return;
                }

                document.querySelectorAll('.attribute-checkbox:checked').forEach(cb => {
                    const aid = cb.dataset.attrId;
                    if (!selected[aid]) selected[aid] = [];
                    selected[aid].push({
                        id: cb.value,
                        name: cb.dataset.name,
                        sku: cb.dataset.sku
                    });
                });

                const keys = Object.keys(selected);
                if (keys.length === 0) return alert('Vui lòng chọn ít nhất một thuộc tính!');

                function combine(i, current) {
                    if (i === keys.length) return [current];
                    let res = [];
                    for (let v of selected[keys[i]]) res = res.concat(combine(i + 1, [...current, v]));
                    return res;
                }

                const combos = combine(0, []);
                tbody.innerHTML = '';

                combos.forEach((c, i) => {
                    // LOGIC GHÉP TÊN: Tên gốc + các thuộc tính
                    const variantSuffix = c.map(x => x.name).join(' ');
                    const suggestedFullName = `${productName} ${variantSuffix}`;

                    const sku = (baseSku ? baseSku + '-' : '') + c.map(x => x.sku).join('-');
                    tbody.insertAdjacentHTML('beforeend', `
                        <tr class="text-center">
                            <td>
                                <div class="variant-img-slot mx-auto" id="v-img-display-${i}" onclick="openImagePicker(${i})">
                                    <i class="fa fa-plus text-muted" style="font-size: 10px;"></i>
                                </div>
                                <input type="hidden" name="v_image[]" id="v-input-${i}">
                            </td>

                            <td>
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input" type="radio" name="is_default_index" value="${i}" ${i === 0 ? 'checked' : ''}>
                                </div>
                            </td>

                            <td class="text-start">
                                <span class="badge bg-light text-dark border">${variantSuffix}</span>
                                <input type="hidden" name="v_values[]" value="${c.map(x=>x.id).join(',')}">
                            </td>

                            <td>
                                <input type="text" name="v_full_name[]" class="form-control form-control-sm fw-bold" 
                                    value="${suggestedFullName}" placeholder="Nhập tên biến thể">
                            </td>

                            <td><input type="text" name="v_sku[]" class="form-control form-control-sm" value="${sku}"></td>

                            <td><input type="number" name="v_price[]" class="form-control form-control-sm text-end variant-price" placeholder="0"></td>

                            <td><input type="number" name="v_compare_at_price[]" class="form-control form-control-sm text-end variant-compare-price" placeholder="0"></td>

                            <td><input type="number" name="v_stock[]" class="form-control form-control-sm text-center variant-stock" value="0"></td>

                            <td>
                                <select name="v_availability[]" class="form-select form-select-sm">
                                    <option value="ready">Sẵn có</option>
                                    <option value="coming_soon">Sắp về</option>
                                    <option value="contact">Liên hệ</option>
                                    <option value="preorder">Đặt trước</option>
                                </select>
                            </td>

                            <td>
                                <select name="v_status[]" class="form-select form-select-sm">
                                    <option value="1">Mở bán</option>
                                    <option value="0">Khóa</option>
                                </select>
                            </td>

                            <td>
                                <button type="button" class="btn btn-sm btn-outline-info" onclick="openDescriptionModal(${i})">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <input type="hidden" name="v_technical_specifications[]" id="v-technical-specifications-input-${i}">
                                <input type="hidden" name="v_description[]" id="v-description-input-${i}">
                            </td>

                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="this.closest('tr').remove()">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
                document.getElementById('variants-preview').classList.remove('d-none');
            }

            // Thêm sự kiện này vào ô input #product_name
            document.getElementById('product_name').addEventListener('input', function() {
                const newName = this.value.trim();
                const rows = document.querySelectorAll('#variants-table-body tr');

                rows.forEach(row => {
                    const fullNameInput = row.querySelector('input[name="v_full_name[]"]');
                    const suffixLabel = row.querySelector('small.text-muted'); // Cái dòng "Gốc: ..." bạn tạo

                    if (fullNameInput && suffixLabel) {
                        const suffix = suffixLabel.innerText.replace('Gốc: ', '');
                        fullNameInput.value = `${newName} ${suffix}`;
                    }
                });
            });

            // 7. Load thuộc tính khi chọn Category
            document.querySelectorAll('input[name="category_id"]').forEach(r => {
                r.onchange = () => {
                    const container = document.getElementById('attributes-container');
                    container.innerHTML =
                        '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div> Đang tải thuộc tính...</div>';

                    fetch(`/admin/product/cat/${r.value}/attributes`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.length === 0) {
                                container.innerHTML =
                                    '<p class="text-warning small">Danh mục này không có thuộc tính biến thể.</p>';
                                return;
                            }
                            container.innerHTML = data.map(attr => `
                                <div class="attribute-group shadow-sm mb-3 p-3 border rounded">
                                    <div class="attribute-name fw-bold mb-2">
                                        <i class="fa fa-dot-circle text-primary me-2"></i>${attr.name}
                                    </div>
                                    <div class="chips-container d-flex flex-wrap gap-2">
                                        ${attr.values.map(val => `
                                                                                            <input type="checkbox" class="attribute-checkbox d-none" 
                                                                                                id="attr_${val.id}" 
                                                                                                value="${val.id}" 
                                                                                                data-attr-id="${attr.id}" 
                                                                                                data-name="${val.value}" 
                                                                                                data-sku="${val.value_code || val.value}">
                                                                                            <label for="attr_${val.id}" class="attribute-label border px-3 py-1 rounded cursor-pointer">
                                                                                                ${val.value}
                                                                                            </label>
                                                                                        `).join('')}
                                    </div>
                                </div>
                            `).join('');
                        })
                        .catch(err => {
                            container.innerHTML = '<p class="text-danger">Lỗi khi tải thuộc tính.</p>';
                        });
                };
            });

            // Biến lưu trữ index của biến thể đang được sửa
            let currentEditVariantIndex = null;

            // 1. Khởi tạo TinyMCE DUY NHẤT 1 lần cho Modal
            tinymce.init({
                selector: '#variant_description_editor',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | bold italic underline | link image | numlist bullist | removeformat',
                height: 400,
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce.triggerSave();
                    });
                }
            });

            // 2. Hàm mở Modal và nạp dữ liệu
            function openDescriptionModal(index) {
                currentEditVariantIndex = index;

                // Lấy tên biến thể từ input đã tạo trong table
                const variantNames = document.getElementsByName('v_full_name[]');
                const variantName = variantNames[index] ? variantNames[index].value : "Không xác định";
                document.getElementById('variant-name-display').innerText = variantName;

                // Lấy dữ liệu từ input ẩn của dòng đó
                const oldTechnicalSpecs = document.getElementById(`v-technical-specifications-input-${index}`).value;
                const oldDescription = document.getElementById(`v-description-input-${index}`).value;

                // Đổ dữ liệu vào các field trong Modal
                document.getElementById('modal-v-technical-specifications').value = oldTechnicalSpecs;

                // Nạp dữ liệu vào TinyMCE (Kiểm tra editor tồn tại để tránh lỗi)
                if (tinymce.get('variant_description_editor')) {
                    tinymce.get('variant_description_editor').setContent(oldDescription || '');
                }

                // Hiển thị Modal
                var myModal = new bootstrap.Modal(document.getElementById('variantDescriptionModal'));
                myModal.show();
            }

            // 3. Hàm lưu dữ liệu từ Modal vào lại biến thể
            function saveVariantContent() {
                const technicalSpecs = document.getElementById('modal-v-technical-specifications').value;
                const description = tinymce.get('variant_description_editor').getContent();

                // Ghi dữ liệu vào input hidden của biến thể tương ứng
                document.getElementById(`v-technical-specifications-input-${currentEditVariantIndex}`).value = technicalSpecs;
                document.getElementById(`v-description-input-${currentEditVariantIndex}`).value = description;

                // Đóng modal
                bootstrap.Modal.getInstance(document.getElementById('variantDescriptionModal')).hide();
            }

            // 4. Hàm áp dụng hàng loạt (Copy nội dung Modal cho toàn bộ biến thể)
            function applyToAllVariants() {
                if (!confirm('Bạn có chắc chắn muốn áp dụng Tóm tắt & Mô tả này cho TẤT CẢ các biến thể hiện có?')) return;

                const technicalSpecs = document.getElementById('modal-v-technical-specifications').value;
                const description = tinymce.get('variant_description_editor').getContent();

                // Lặp qua tất cả các input ẩn của biến thể để ghi đè
                document.querySelectorAll('input[name="v_technical_specifications[]"]').forEach(input => {
                    input.value = technicalSpecs;
                });
                document.querySelectorAll('input[name="v_description[]"]').forEach(input => {
                    input.value = description;
                });

                bootstrap.Modal.getInstance(document.getElementById('variantDescriptionModal')).hide();
                alert('Đã cập nhật mô tả cho toàn bộ biến thể!');
            }

            // 8. Hàm áp dụng hàng loạt (Bulk Apply)
            function applyBulk() {
                const price = document.getElementById('bulk-price').value;
                const comparePrice = document.getElementById('bulk-compare-at-price').value;
                const stock = document.getElementById('bulk-stock').value;

                if (price) document.querySelectorAll('.variant-price').forEach(i => i.value = price);
                if (comparePrice) document.querySelectorAll('.variant-compare-price').forEach(i => i.value = comparePrice);
                if (stock) document.querySelectorAll('.variant-stock').forEach(i => i.value = stock);
            }
        </script>
    @endpush
@endsection
