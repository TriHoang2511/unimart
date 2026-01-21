@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
    <style>
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

        /* =========================
               VARIANT TABLE
            ========================== */
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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug" id="product_slug" class="form-control bg-light"
                                        readonly value="{{ old('slug') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mã SKU gốc</label>
                                    <input type="text" name="sku" id="base_sku" class="form-control"
                                        placeholder="VD: IP16" value="{{ old('sku') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tóm tắt ngắn (SEO & Quickview)</label>
                                <textarea name="summary" class="form-control" rows="3" placeholder="Nhập mô tả ngắn tối đa 255 ký tự...">{{ old('summary') }}</textarea>
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
                                        <input type="number" id="bulk-stock" class="form-control form-control-sm"
                                            placeholder="Kho">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="applyBulk()">Áp dụng</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle variant-table">
                                        <thead>
                                            <tr>
                                                <th width="70">Ảnh</th>
                                                <th>Biến thể</th>
                                                <th>SKU</th>
                                                <th>Giá bán</th>
                                                <th>Giá gốc</th>
                                                <th width="100">Kho</th>
                                                <th width="40"></th>
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
            document.getElementById('product_name').addEventListener('input', function() {
                let slug = this.value.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^\w ]+/g, '').replace(/ +/g, '-');
                document.getElementById('product_slug').value = slug;
            });

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
                const baseSku = document.getElementById('base_sku').value.trim().toUpperCase();
                const tbody = document.getElementById('variants-table-body');
                const selected = {};

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
                    const name = c.map(x => x.name).join(' / ');
                    const sku = (baseSku ? baseSku + '-' : '') + c.map(x => x.sku).join('-');
                    tbody.insertAdjacentHTML('beforeend', `
                        <tr>
                            <td>
                                <div class="variant-img-slot rounded border shadow-sm" id="v-img-display-${i}" onclick="openImagePicker(${i})">
                                    <i class="fa fa-plus text-muted small"></i>
                                </div>
                                <input type="hidden" name="v_image[]" id="v-input-${i}">
                            </td>
                            <td><small class="fw-bold">${name}</small><input type="hidden" name="v_values[]" value="${c.map(x=>x.id).join(',')}"></td>
                            <td><input type="text" name="v_sku[]" class="form-control form-control-sm" value="${sku}"></td>
                            <td><input type="number" name="v_price[]" class="form-control form-control-sm variant-price" placeholder="Bán"></td>
                            <td><input type="number" name="v_compare_at_price[]" class="form-control form-control-sm variant-compare-price" placeholder="Gốc"></td>
                            <td><input type="number" name="v_stock[]" class="form-control form-control-sm variant-stock" value="0"></td>
                            <td><button type="button" class="btn btn-sm text-danger" onclick="this.closest('tr').remove()"><i class="fa fa-trash"></i></button></td>
                        </tr>`);
                });
                document.getElementById('variants-preview').classList.remove('d-none');
            }

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
                            <div class="attribute-group shadow-sm">
                                <div class="attribute-name">
                                    <i class="fa fa-th-large"></i> ${attr.name}
                                </div>
                                <div class="chips-container">
                                    ${attr.values.map(v => `
                                                                <div class="chip-item">
                                                                    <input class="attribute-checkbox" type="checkbox" value="${v.id}" 
                                                                        data-name="${v.value}" data-sku="${v.sku_code}" 
                                                                        data-attr-id="${attr.id}" id="v${v.id}">
                                                                    <label class="attribute-label" for="v${v.id}">
                                                                        ${v.value}
                                                                    </label>
                                                                </div>
                                                            `).join('')}
                                </div>
                            </div>
                            `).join('');
                        });
                };
            });

            function applyBulk() {
                const p = document.getElementById('bulk-price').value;
                const s = document.getElementById('bulk-stock').value;
                if (p) document.querySelectorAll('.variant-price').forEach(i => i.value = p);
                if (s) document.querySelectorAll('.variant-stock').forEach(i => i.value = s);
            }
        </script>
    @endpush
@endsection
