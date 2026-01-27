@extends('layouts.admin')

@section('title', 'Chỉnh sửa sản phẩm: ' . $product->name)

@section('content')
    <style>
        /* Giữ nguyên toàn bộ phần <style> từ bản Create của bạn */
        .variant-table input.form-control-sm,
        .variant-table select.form-select-sm {
            padding: 4px 6px;
            font-size: 13px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            display: inline-block;
        }

        .variant-table input[type="number"].variant-price,
        .variant-table input[type="number"].variant-compare-price {
            width: 95px;
        }

        .variant-table input[type="number"].variant-stock {
            width: 70px;
        }

        .variant-table input[name="v_sku[]"] {
            width: 120px;
        }

        .variant-table select {
            width: 110px;
        }

        .variant-name-text {
            max-width: 160px;
            display: block;
            word-wrap: break-word;
            line-height: 1.2;
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

        .variant-table thead {
            background: #f8fafc;
        }

        .variant-table th {
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            color: #64748b;
            padding: 12px;
        }

        .variant-table td {
            padding: 12px;
            vertical-align: middle;
        }

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
        }

        .attribute-checkbox:checked+.attribute-label {
            background: #eef2ff;
            border-color: #4361ee;
            color: #4361ee;
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
    </style>

    <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Chỉnh sửa: {{ $product->name }}</h4>
                <p class="text-muted small mb-0">Cập nhật thông tin kho hàng và biến thể</p>
            </div>
            <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i>
                Quay lại</a>
        </div>

        <form action="{{ route('admin.product.update', $product->id) }}" method="POST" id="productForm">
            @csrf
            @method('PUT')
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
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
                                    value="{{ old('name', $product->name) }}">
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug" id="product_slug" class="form-control bg-light"
                                        readonly value="{{ old('slug', $product->slug) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mã SKU gốc</label>
                                    <input type="text" name="sku" id="base_sku" class="form-control"
                                        placeholder="VD: IP16" value="{{ old('sku', $product->sku) }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tóm tắt ngắn</label>
                                <textarea name="summary" class="form-control" rows="3">{{ old('summary', $product->summary) }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold">Mô tả chi tiết</label>
                                <textarea name="description" id="product-description" class="form-control">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- QUẢN LÝ ẢNH --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <span class="fw-bold"><i class="fa fa-images me-2 text-primary"></i>Kho ảnh sản phẩm</span>
                            <button type="button" class="btn btn-sm btn-primary"
                                onclick="document.getElementById('ajax-upload').click()">
                                <i class="fa fa-upload me-1"></i>Tải ảnh mới
                            </button>
                            <input type="file" id="ajax-upload" class="d-none" multiple accept="image/*">
                        </div>
                        <div class="card-body">
                            <div id="media-pool" class="row g-3 mb-4">
                                {{-- Ảnh hiện có trong DB sẽ được JS đổ vào đây --}}
                            </div>
                            <hr>
                            <h6 class="fw-bold small mb-3 text-uppercase text-secondary">Sắp xếp Album</h6>
                            <div id="gallery-sortable" class="d-flex flex-wrap gap-2 p-3 border rounded bg-light"
                                style="min-height: 120px;">
                                @if ($product->images)
                                    @foreach ($product->images as $img)
                                        <div class="sort-item" id="gal-item-{{ $loop->index }}"
                                            data-id="{{ $loop->index }}">
                                            <img src="{{ asset('storage/' . $img->image_path) }}">
                                            <button type="button" class="delete-btn"
                                                onclick="this.parentElement.remove()">×</button>
                                            <input type="hidden" name="gallery_images[]" value="{{ $img->image_path }}">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- BIẾN THỂ --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <span class="fw-bold"><i class="fa fa-tags me-2 text-primary"></i>Biến thể</span>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="generateVariants()"><i
                                    class="fa fa-magic"></i> Làm mới biến thể</button>
                        </div>
                        <div class="card-body">
                            <div id="attributes-container">
                                {{-- Load via Ajax --}}
                            </div>

                            <div id="variants-preview" class="mt-4">
                                <div class="variant-header">
                                    <div class="variant-bulk">
                                        <input type="number" id="bulk-price" class="form-control form-control-sm"
                                            placeholder="Giá bán">
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
                                                <th width="180">Biến thể</th>
                                                <th width="160">SKU</th>
                                                <th width="140">Giá bán</th>
                                                <th width="140">Giá gốc</th>
                                                <th width="90" class="text-center">Kho</th>
                                                <th width="150">Cung ứng</th>
                                                <th width="130">Trạng thái</th>
                                                <th width="50"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="variants-table-body">
                                            @foreach ($product->variants as $index => $variant)
                                                <tr class="text-center">
                                                    <input type="hidden" name="v_id[]" value="{{ $variant->id }}">
                                                    <td>
                                                        <div class="variant-img-slot mx-auto"
                                                            id="v-img-display-{{ $index }}"
                                                            onclick="openImagePicker({{ $index }})">
                                                            @if ($variant->variant_image)
                                                                <img
                                                                    src="{{ asset('storage/' . $variant->variant_image) }}">
                                                            @else
                                                                <i class="fa fa-plus text-muted small"></i>
                                                            @endif
                                                        </div>
                                                        <input type="hidden" name="v_image[]"
                                                            id="v-input-{{ $index }}"
                                                            value="{{ $variant->variant_image }}">
                                                    </td>
                                                    <td class="text-start">
                                                        <span class="variant-name-text fw-bold small">
                                                            {{ $variant->attributeValues->pluck('value')->implode(' / ') }}
                                                        </span>
                                                        {{-- Lưu lại ID các giá trị thuộc tính hiện tại --}}
                                                        <input type="hidden" name="v_values[]"
                                                            value="{{ $variant->attributeValues->pluck('id')->implode(',') }}">
                                                    </td>
                                                    <td><input type="text" name="v_sku[]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $variant->sku }}"></td>
                                                    <td><input type="number" name="v_price[]"
                                                            class="form-control form-control-sm variant-price text-end"
                                                            value="{{ $variant->price }}"></td>
                                                    <td>
                                                        <input type="number" name="v_compare_at_price[]"
                                                            class="form-control form-control-sm variant-price text-end"
                                                            value="{{ $variant->compare_at_price }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="v_stock[]"
                                                            class="form-control form-control-sm variant-stock text-center"
                                                            value="{{ $variant->stock_qty }}">
                                                    </td>
                                                    <td>
                                                        <select name="v_availability[]" class="form-select">
                                                            @foreach($availability as $key => $label)
                                                                <option value="{{ $key }}"
                                                                    {{ $variant->availability == $key ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                            {{-- <option value="ready">Sẵn có</option>
                                                            <option value="coming_soon">Sắp về</option>
                                                            <option value="contact">Liên hệ</option>
                                                            <option value="preorder">Đặt trước</option> --}}
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="v_status[]" class="form-select form-select-sm">
                                                            <option value="1"
                                                                {{ $variant->status == 1 ? 'selected' : '' }}>Mở bán
                                                            </option>
                                                            <option value="0"
                                                                {{ $variant->status == 0 ? 'selected' : '' }}>Khóa</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-link text-danger"
                                                            onclick="this.closest('tr').remove()"><i
                                                                class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
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
                                <label class="form-label small fw-bold">Ảnh đại diện:</label>
                                <div id="main-preview" class="text-center p-2 border rounded bg-light">
                                    <img src="{{ $product->featured_image ? asset('storage/' . $product->featured_image) : asset('storage/images/Placeholder_Image.jpg') }}"
                                        class="img-fluid rounded" id="main-img-display" style="max-height: 200px;">
                                    <input type="hidden" name="main_image_url" id="main-img-input"
                                        value="{{ $product->featured_image }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="published" {{ $product->status == 'published' ? 'selected' : '' }}>Đang
                                        bán</option>
                                    <option value="pending" {{ $product->status == 'pending' ? 'selected' : '' }}>Chờ
                                        duyệt</option>
                                    <option value="draft" {{ $product->status == 'draft' ? 'selected' : '' }}>Lưu nháp
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">CẬP NHẬT SẢN PHẨM</button>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold py-3">Danh mục sản phẩm</div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            <input type="hidden" name="category_id" value="{{ $product->product_category_id }}">
                            @foreach ($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="category_id"
                                        id="cat_{{ $category->id }}" value="{{ $category->id }}"
                                        {{ $product->product_category_id == $category->id ? 'checked' : '' }}
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
            </div>
        </form>
    </div>

    {{-- Modal chọn ảnh biến thể --}}
    <div class="modal fade" id="imagePickerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Chọn ảnh biến thể</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="picker-pool" class="row g-2"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/gy0w6zmrjbzf7udvwhd52fevt0wqiz64hjkxwjmvf608cbh4/tinymce/8/tinymce.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        let currentVariantTarget = null;
        let uploadedImages = [];

        // Khởi tạo TinyMCE
        tinymce.init({
            selector: '#product-description',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media table wordcount',
            toolbar: 'undo redo | bold italic underline | link image | numlist bullist',
            height: 650,
        });

        // Tải thuộc tính khi trang load (theo Category đã chọn)
        window.onload = function() {
            const checkedCat = document.querySelector('input[name="category_id"]:checked');
            if (checkedCat) loadAttributes(checkedCat.value);
        };

        function loadAttributes(catId) {
            const container = document.getElementById('attributes-container');
            container.innerHTML = 'Đang tải...';
            fetch(`/admin/product/cat/${catId}/attributes`)
                .then(res => res.json())
                .then(data => {
                    // Logic render chip giống bản create
                    container.innerHTML = data.map(attr => `
                        <div class="attribute-group shadow-sm">
                            <div class="attribute-name"><i class="fa fa-th-large"></i> ${attr.name}</div>
                            <div class="chips-container">
                                ${attr.values.map(v => `
                                                                <div class="chip-item">
                                                                    <input class="attribute-checkbox" type="checkbox" value="${v.id}" 
                                                                        data-name="${v.value}" data-sku="${v.sku_code}" data-attr-id="${attr.id}" id="v${v.id}">
                                                                    <label class="attribute-label" for="v${v.id}">${v.value}</label>
                                                                </div>
                                                            `).join('')}
                            </div>
                        </div>
                    `).join('');
                });
        }

        // Logic xử lý ảnh, upload, biến thể... (Copy y hệt từ bản Create của bạn sang)
        // Lưu ý: Nhớ cập nhật phần uploadedImages khi upload thành công để Modal Picker có ảnh.
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
    </script>
@endpush
