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

            {{-- FORM POST --}}
            <form action="{{ url('/admin/post') }}" method="POST">
                @csrf

                {{-- Thông báo --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-alt mr-2"></i>Thêm bài viết
                    </h5>
                </div>

                <div class="row">
                    {{-- Nội dung chính --}}
                    <div class="col-md-9">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">

                                {{-- Title --}}
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold">Tiêu đề bài viết</label>
                                    <input type="text" name="title"
                                        class="form-control form-control-lg @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}">
                                    @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Excerpt --}}
                                <div class="form-group">
                                    <label class="font-weight-bold">Mô tả ngắn</label>
                                    <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt') }}</textarea>
                                </div>

                                {{-- Content --}}
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold">Nội dung bài viết</label>
                                    <textarea name="content" id="textarea-content">
                                        {{ old('content') }}
                                    </textarea>
                                    @error('content')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="col-md-3">
                    <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white font-weight-bold">
        Danh mục
    </div>
    <div class="card-body">
        <select name="post_category_id" class="form-control">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>


                        
                        {{-- STATUS --}}
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white font-weight-bold">
                                Trạng thái
                            </div>
                            <div class="card-body">

                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="status_pending" name="status" value="pending"
                                        class="custom-control-input" checked>
                                    <label class="custom-control-label" for="status_pending">Chờ duyệt</label>
                                </div>

                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="status_publish" name="status" value="publish"
                                        class="custom-control-input">
                                    <label class="custom-control-label text-success" for="status_publish">Công khai</label>
                                </div>

                                <div class="custom-control custom-radio">
                                    <input type="radio" id="status_trash" name="status" value="trash"
                                        class="custom-control-input">
                                    <label class="custom-control-label text-danger" for="status_trash">Thùng rác</label>
                                </div>

                            </div>

                            <div class="card-footer bg-light border-0">
                                <button class="btn btn-primary btn-block">
                                    Đăng bài viết
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
