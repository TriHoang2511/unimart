@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">
    <div class="card shadow-sm border-0">

        {{-- HEADER --}}
        <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h5 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-newspaper me-2"></i> Danh sách bài viết
            </h5>

            <form action="{{ url('/admin/post') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control bg-light border-0 small"
                           placeholder="Tìm tiêu đề bài viết..." value="{{ request('keyword') }}">
                    <button class="btn btn-primary">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            {{-- STATUS FILTER --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <ul class="status-filter">
                    @foreach ($list_status as $key => $label)
                        <li class="{{ request('status') === $key ? 'active' : '' }}">
                            <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}">
                                {{ $label }}
                                <span class="count">{{ $count[$key] ?? 0 }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ url('/admin/post/create') }}" class="btn btn-success btn-sm px-3">
                    <i class="fas fa-plus me-1"></i> Thêm bài viết
                </a>
            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="60">STT</th>
                            <th>Tiêu đề</th>
                            <th>Danh mục</th>
                            <th>Trạng thái</th>
                            <th>Người viết</th>
                            <th>Ngày tạo</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($posts as $index => $post)
                        <tr>
                            <td>{{ ($posts->currentPage()-1)*$posts->perPage()+$index+1 }}</td>

                            <td class="fw-bold">{{ $post->title }}</td>

                            <td>{{ $post->category->name ?? '---' }}</td>

                            <td>
                                @switch($post->status)
                                    @case('publish') <span class="badge badge-success">Công khai</span> @break
                                    @case('pending') <span class="badge badge-warning text-white">Chờ duyệt</span> @break
                                    @default <span class="badge badge-secondary">Thùng rác</span>
                                @endswitch
                            </td>

                            <td>{{ $post->author->name ?? '---' }}</td>

                            <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>

                            <td class="text-right">

                             {{-- Sửa --}}
                         <a href="{{ url('/admin/post/'.$post->id.'/edit') }}"
                              class="btn btn-outline-light text-primary border-0"
                              title="Sửa">
                             <i class="fas fa-edit"></i>
                         </a>

                     {{-- Xóa  --}}
                         <a href="{{ url('/admin/post/'.$post->id.'/delete') }}"
                             onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')"
                             class="btn btn-outline-light text-danger border-0"
                             title="Xóa">
                             <i class="fas fa-trash"></i>
                        </a>

                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Không có bài viết
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-3">
                {{ $posts->appends(request()->input())->links() }}
            </div>

        </div>
    </div>
</div>

{{-- CSS giữ nguyên --}}
<style>
.status-filter {
    list-style: none;
    display: flex;
    gap: 10px;
    padding: 0;
}
.status-filter li a {
    padding: 8px 14px;
    border-radius: 999px;
    background: #f1f3f5;
    color: #2d3436;
    text-decoration: none;
}
.status-filter li.active a {
    background: #0984e3;
    color: #fff;
}
</style>
@endsection
