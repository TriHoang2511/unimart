@extends('layouts.admin')

@section('content')

<head>
    <script src="https://cdn.tiny.cloud/1/gy0w6zmrjbzf7udvwhd52fevt0wqiz64hjkxwjmvf608cbh4/tinymce/8/tinymce.min.js"></script>
    <script>
        tinymce.init({
             selector: '#textarea-content',
             height: 500,
            plugins: 'advlist lists link image media code table',
             toolbar: 'undo redo | styles | bold italic | bullist numlist | link image media | code'
            });
    </script>
</head>

<div class="container-fluid">
<form action="{{ url('/admin/post/'.$post->id) }}" method="POST">
@csrf
@method('PUT')

<div class="row">
<div class="col-md-9">
<div class="card shadow-sm border-0 mb-4">
<div class="card-body">

<label>Tiêu đề</label>
<input type="text" name="title" class="form-control mb-3" value="{{ $post->title }}">

<label>Mô tả ngắn (Excerpt)</label>
<textarea name="excerpt" class="form-control mb-3" rows="3">{{ $post->excerpt }}</textarea>

<label>Nội dung</label>
<textarea id="textarea-content" name="content">{{ $post->content }}</textarea>

</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm border-0 mb-4">
<div class="card-body">

<label>Danh mục</label>
<select name="post_category_id" class="form-control mb-3">
@foreach($categories as $cat)
    <option value="{{ $cat->id }}" {{ $post->post_category_id == $cat->id ? 'selected' : '' }}>
        {{ $cat->name }}
    </option>
@endforeach
</select>

<label>Trạng thái</label>
<select name="status" class="form-control mb-3">
    <option value="publish" {{ $post->status=='publish'?'selected':'' }}>Công khai</option>
    <option value="pending" {{ $post->status=='pending'?'selected':'' }}>Chờ duyệt</option>
    <option value="trash" {{ $post->status=='trash'?'selected':'' }}>Thùng rác</option>
</select>

<button class="btn btn-success btn-block">Cập nhật</button>

</div>
</div>

<div class="card border-info">
<div class="card-header text-info">Dữ liệu cho AI (RAG)</div>
<div class="card-body small text-muted">
Nội dung HTML sẽ được chunk & index cho chatbot.
</div>
</div>

</div>
</div>
</form>
</div>
@endsection
