@extends('layouts.admin')

@section('content')
    <head>
        <script src="https://cdn.tiny.cloud/1/gy0w6zmrjbzf7udvwhd52fevt0wqiz64hjkxwjmvf608cbh4/tinymce/8/tinymce.min.js" referrerpolicy="origin"></script>
        <script>
            tinymce.init({
                selector: '#textarea-content',
                path_absolute: "/",
                relative_urls: false,
                height: 500,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'pagebreak', 'searchreplace', 'wordcount', 'visualblocks',
                    'code', 'fullscreen', 'insertdatetime', 'media', 'table', 'emoticons'
                ],
                toolbar: "undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | " +
                    "bullist numlist outdent indent | link image media",
                file_picker_callback: function(callback, value, meta) {
                    let x = window.innerWidth * 0.8;
                    let y = window.innerHeight * 0.8;
                    let cmsURL = "/laravel-filemanager?editor=" + meta.fieldname;
                    cmsURL += meta.filetype === "image" ? "&type=Images" : "&type=Files";

                    tinymce.activeEditor.windowManager.openUrl({
                        title: "Thư viện tệp tin",
                        url: cmsURL,
                        width: x,
                        height: y,
                        resizable: true,
                        onMessage: (api, message) => {
                            callback(message.content);
                        }
                    });
                }
            });
        </script>
    </head>

    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- Chú ý: Đổi action sang update và thêm @method('PUT') --}}
                <form action="{{ route('admin.page.update', $page->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Thông báo --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-edit mr-2"></i>Chỉnh sửa trang: <span class="text-dark">{{ $page->title }}</span>
                        </h5> 
                        <a href="{{ route('admin.page.list') }}" class="btn btn-sm btn-light border">
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại danh sách
                        </a>
                    </div>

                    <div class="row">
                        {{-- Nội dung chính --}}
                        <div class="col-md-9">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body">
                                    {{-- Title --}}
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">Tiêu đề trang</label>
                                        <input type="text" name="title"
                                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                                            placeholder="Nhập tiêu đề trang..." 
                                            value="{{ old('title', $page->title) }}"> {{-- Đổ dữ liệu cũ --}}
                                        @error('title')
                                            <small class="text-danger font-weight-bold">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Slug (Đường dẫn tĩnh - Rất quan trọng cho RAG và SEO) --}}
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">Đường dẫn (Slug)</label>
                                        <input type="text" name="slug" class="form-control" 
                                            value="{{ old('slug', $page->slug) }}" placeholder="Ví dụ: chinh-sach-bao-hanh">
                                        <small class="text-muted">Để trống nếu muốn tự động tạo từ tiêu đề.</small>
                                        @error('slug')
                                            <small class="text-danger font-weight-bold">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Content --}}
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold text-dark">Nội dung trang</label>
                                        <textarea name="content" id="textarea-content" rows="15">
                                            {{ old('content', $page->content) }} {{-- Đổ nội dung từ DB --}}
                                        </textarea>
                                        @error('content')
                                            <small class="text-danger font-weight-bold">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sidebar --}}
                        <div class="col-md-3">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-white font-weight-bold">
                                    Cập nhật trạng thái
                                </div>
                                <div class="card-body">
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="status_draft" name="status" value="draft"
                                            class="custom-control-input" {{ $page->status == 'draft' ? 'checked' : '' }}>
                                        <label class="custom-control-label text-secondary" for="status_draft">
                                            <i class="fas fa-pencil-alt mr-1"></i>Bản nháp
                                        </label>
                                    </div>

                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="status_pending" name="status" value="pending"
                                            class="custom-control-input" {{ $page->status == 'pending' ? 'checked' : '' }}>
                                        <label class="custom-control-label text-muted" for="status_pending">
                                            <i class="far fa-clock mr-1"></i>Chờ duyệt
                                        </label>
                                    </div>

                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="status_publish" name="status" value="publish"
                                            class="custom-control-input" {{ $page->status == 'publish' ? 'checked' : '' }}>
                                        <label class="custom-control-label text-success font-weight-bold" for="status_publish">
                                            <i class="far fa-check-circle mr-1"></i>Công khai ngay
                                        </label>
                                    </div>
                                    <hr>
                                    <p class="small text-muted">
                                        <i class="fas fa-calendar-alt mr-1"></i> Ngày tạo: {{ $page->created_at->format('d/m/Y H:i') }}<br>
                                        <i class="fas fa-history mr-1"></i> Sửa lần cuối: {{ $page->updated_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <div class="card-footer bg-light border-0">
                                    <button type="submit" class="btn btn-success btn-block shadow-sm">
                                        <i class="fas fa-save mr-1"></i> Lưu thay đổi
                                    </button>
                                </div>
                            </div>

                            {{-- Hộp thông tin cho RAG Agent --}}
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white font-weight-bold text-info small text-uppercase">
                                    Dữ liệu cho AI Agent
                                </div>
                                <div class="card-body p-3">
                                    <p class="small text-muted">Đảm bảo nội dung sử dụng các thẻ H2, H3 và Table để RAG Agent thu thập thông tin tốt nhất.</p>
                                    <div class="badge badge-info d-block p-2">Sẵn sàng huấn luyện</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        #content { background-color: #f8f9fc; min-height: 100vh; padding-top: 20px; }
        .form-control-lg { border-radius: 5px; font-size: 1.1rem; }
        .card-header { border-bottom: 1px solid #f1f1f1 !important; }
        .custom-control-label { cursor: pointer; }
    </style>
@endsection