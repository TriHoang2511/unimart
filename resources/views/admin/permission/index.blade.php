@extends('layouts.admin')
@section('title', 'Quản lý quyền')

@section('content')
    <div id="content" class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h4 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-shield-alt mr-2 text-primary"></i>Quản lý quyền (Permissions)
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 p-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quyền</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thêm quyền mới</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.permission.store') }}" method="POST">
                            @csrf
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold" for="name">Tên định danh (Slug)</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text"
                                    name="name" id="name" placeholder="Ví dụ: product.create"
                                    value="{{ old('name') }}">
                                <small class="form-text text-muted">Sử dụng dấu chấm để phân cách (module.action).</small>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label font-weight-bold" for="description">Mô tả chi tiết</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                                    rows="3" placeholder="Mô tả chức năng của quyền này...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <input type="hidden" name="guard_name" value="web">

                            <button type="submit" class="btn btn-primary btn-block shadow-sm">
                                <i class="fas fa-plus-circle mr-1"></i> Lưu quyền mới
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-dark">Danh sách các quyền hiện có</h6>
                        <span class="badge badge-info">{{ count($permissions) }} Modules</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-top-0 pl-4" width="5%">#</th>
                                        <th class="border-top-0">Mô tả / Tên quyền</th>
                                        <th class="border-top-0">Mã định danh (Slug)</th>
                                        <th class="border-top-0 text-right pr-4">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $moduleName => $modulePermissions)
                                        {{-- Group Header Row --}}
                                        <tr class="bg-light-primary border-left-primary">
                                            <td colspan="2" class="pl-4">
                                                <span class="text-uppercase font-weight-bold text-primary small">
                                                    <i class="fas fa-folder-open mr-2"></i>Module {{ $moduleName }}
                                                </span>
                                            </td>
                                            <td></td>
                                            <td class="text-right pr-4 text-muted small">{{ count($modulePermissions) }}
                                                quyền</td>
                                        </tr>

                                        {{-- Sub-items --}}
                                        @foreach ($modulePermissions as $permission)
                                            <tr>
                                                <td class="pl-4 text-muted small">{{ $loop->iteration }}</td>
                                                <td>
                                                    <span
                                                        class="d-block font-weight-600 text-dark">{{ $permission->description }}</span>
                                                </td>
                                                <td>
                                                    <code class="badge badge-light border text-primary px-2 py-1"
                                                        style="font-size: 90%;">
                                                        {{ $permission->name }}
                                                    </code>
                                                </td>
                                                <td class="text-right pr-4">
                                                    <div class="btn-group btn-group-sm">
                                                        @can('permission.edit')
                                                            <a href="{{ route('admin.permission.edit', $permission) }}"
                                                                class="btn btn-outline-light text-primary border-0"
                                                                title="Sửa">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan
                                                        @can('permission.delete')
                                                            <form action="{{ route('admin.permission.delete', $permission) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Xác nhận xóa quyền này?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-outline-light text-danger border-0"
                                                                    title="Xóa">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom styles để giao diện trông sang trọng hơn */
        .bg-light-primary {
            background-color: #f8faff;
        }

        .border-left-primary {
            border-left: 4px solid #4e73df !important;
        }

        .table thead th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #5a5c69;
        }

        .font-weight-600 {
            font-weight: 600;
        }

        .btn-outline-light:hover {
            background-color: #f8f9fc;
        }

        .card {
            border-radius: 0.5rem;
        }

        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
        }
    </style>
@endsection
