@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card shadow-sm border-0">

            {{-- HEADER --}}
            <div
                class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-alt me-2"></i> Danh sách trang
                </h5>

                <form action="{{ route('admin.page.index') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control bg-light border-0 small"
                            placeholder="Tìm tiêu đề trang..." value="{{ request('keyword') }}">
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

                    <a href="{{ route('admin.page.create') }}" class="btn btn-success btn-sm px-3">
                        <i class="fas fa-plus me-1"></i> Thêm trang mới
                    </a>
                </div>

                {{-- BULK ACTION --}}
                <form action="{{ route('admin.page.action') }}" method="POST">
                    @csrf

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="form-inline mb-3">
                        <select name="act" class="form-control form-control-sm mr-2" style="width:160px">
                            <option value="">-- Chọn tác vụ --</option>
                            @foreach ($list_act as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-secondary btn-sm">
                            Áp dụng
                        </button>
                    </div>

                    {{-- TABLE --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th width="40">
                                        <input type="checkbox" name="checkall">
                                    </th>
                                    <th width="60">STT</th>
                                    <th>Tiêu đề</th>
                                    <th>Slug</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($pages as $index => $page)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $page->id }}">
                                        </td>

                                        <td>
                                            {{ ($pages->currentPage() - 1) * $pages->perPage() + $index + 1 }}
                                        </td>

                                        <td>
                                            <a href="{{ route('admin.page.edit', $page->id) }}"
                                                class="font-weight-bold text-dark">
                                                {{ $page->title }}
                                            </a>
                                        </td>

                                        <td><code>/{{ $page->slug }}</code></td>

                                        <td>
                                            @if ($page->trashed())
                                                <span class="badge badge-danger">Đã xóa</span>
                                            @else
                                                @switch($page->status)
                                                    @case('publish')
                                                        <span class="badge badge-success">Công khai</span>
                                                    @break

                                                    @case('pending')
                                                        <span class="badge badge-warning text-white">Chờ duyệt</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-secondary">Bản nháp</span>
                                                @endswitch
                                            @endif
                                        </td>

                                        <td>{{ $page->created_at->format('d/m/Y H:i') }}</td>

                                        <td class="text-right">
                                            @if (!$page->trashed())
                                                <a href="{{ route('admin.page.edit', $page->id) }}"
                                                    class="btn btn-outline-light text-primary border-0">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.page.destroy', $page->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Xác nhận xóa?')"
                                                        class="btn btn-outline-light text-danger border-0">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted fst-italic">Đã xóa</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Không có dữ liệu
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                    {{-- PAGINATION --}}
                    <div class="mt-3">
                        {{ $pages->appends(request()->input())->links() }}
                    </div>

                </div>
            </div>
        </div>

        {{-- GIỮ NGUYÊN CSS --}}
        <style>
            /* STATUS FILTER */
            .status-filter {
                list-style: none;
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                padding: 0;
                margin: 0;
            }

            .status-filter li a {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 14px;
                border-radius: 999px;
                background: #f1f3f5;
                color: #2d3436;
                font-weight: 500;
                text-decoration: none;
                transition: all .25s ease;
            }

            .status-filter li a .count {
                background: #dfe6e9;
                padding: 2px 8px;
                border-radius: 999px;
                font-size: 15px;
            }

            .status-filter li.active a,
            .status-filter li a:hover {
                background: #0984e3;
                color: #fff;
                box-shadow: 0 5px 15px rgba(9, 132, 227, .35);
                transform: translateY(-2px);
            }

            .status-filter li.active a .count,
            .status-filter li a:hover .count {
                background: rgba(255, 255, 255, .25);
                color: #fff;
            }
        </style>
    @endsection
