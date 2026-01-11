@extends('layouts.admin')

@section('content')

    <head>
        <script src="https://cdn.tiny.cloud/1/gy0w6zmrjbzf7udvwhd52fevt0wqiz64hjkxwjmvf608cbh4/tinymce/8/tinymce.min.js"
            referrerpolicy="origin"></script>
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
                <form action="{{ route('admin.page.store') }}" method="POST">
                    @csrf

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
                            <i class="fas fa-file-alt mr-2"></i>Thêm trang mới
                        </h5>   
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
                                            placeholder="Nhập tiêu đề trang..." value="{{ old('title') }}">
                                        @error('title')
                                            <small class="text-danger font-weight-bold">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Content --}}
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold text-dark">Nội dung trang</label>
                                        <textarea name="content" id="textarea-content" rows="15">
                                            {{ old('content') }}
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
                                    Trạng thái xuất bản
                                </div>
                                <div class="card-body">

                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="status_draft" name="status" value="draft"
                                            class="custom-control-input" checked>
                                        <label class="custom-control-label text-secondary" for="status_draft">
                                            <i class="fas fa-pencil-alt mr-1"></i>Bản nháp
                                        </label>
                                    </div>

                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="status_pending" name="status" value="pending"
                                            class="custom-control-input">
                                        <label class="custom-control-label text-muted" for="status_pending">
                                            <i class="far fa-clock mr-1"></i>Chờ duyệt
                                        </label>
                                    </div>

                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="status_publish" name="status" value="publish"
                                            class="custom-control-input">
                                        <label class="custom-control-label text-success font-weight-bold"
                                            for="status_publish">
                                            <i class="far fa-check-circle mr-1"></i>Công khai ngay
                                        </label>
                                    </div>

                                </div>

                                <div class="card-footer bg-light border-0">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Thêm trang mới
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <style>
        #content {
            background-color: #f8f9fc;
            min-height: 100vh;
            padding-top: 20px;
        }

        .form-control-lg {
            border-radius: 5px;
            font-size: 1.1rem;
        }

        .card-header {
            border-bottom: 1px solid #f1f1f1 !important;
        }

        .custom-control-label {
            cursor: pointer;
        }
    </style>
@endsection
