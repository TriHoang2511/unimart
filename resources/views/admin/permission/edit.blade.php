@extends('layouts.admin')
@section('title', 'Cập nhật quyền')

@section('content')
<div id="content" class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 mb-0 text-gray-800 font-weight-bold">
            <i class="fas fa-edit mr-2 text-primary"></i>Cập nhật quyền
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.permission.index') }}">Quyền</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm border-0 mb-4 border-top-primary">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin quyền</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.permission.update', $permission->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark" for="name">Tên định danh (Slug)</label>
                            <input class="form-control @error('name') is-invalid @enderror" 
                                   type="text" name="name" id="name" 
                                   placeholder="Ví dụ: product.create"
                                   value="{{ old('name', $permission->name) }}">
                            <small class="form-text text-muted font-italic">Định dạng: module.action (ví dụ: user.add)</small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold text-dark" for="description">Mô tả chi tiết</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" id="description" 
                                      rows="4" placeholder="Mô tả chức năng của quyền này...">{{ old('description', $permission->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" name="guard_name" value="web">

                        <div class="d-flex align-items-center justify-content-between">
                            <a href="{{ route('admin.permission.index') }}" class="btn btn-light btn-sm font-weight-bold">
                                <i class="fas fa-arrow-left mr-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Cập nhật ngay
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-dark">Các quyền hiện có</h6>
                    <span class="badge badge-primary badge-pill px-3">{{ count($permissions) }} Nhóm</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light sticky-top" style="z-index: 1;">
                                <tr>
                                    <th class="border-top-0 pl-4" width="5%">#</th>
                                    <th class="border-top-0">Mô tả / Tên quyền</th>
                                    <th class="border-top-0">Mã (Slug)</th>
                                    <th class="border-top-0 text-right pr-4">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $moduleName => $modulePermissions)
                                    <tr class="bg-light-primary border-left-primary">
                                        <td colspan="2" class="pl-4">
                                            <span class="text-uppercase font-weight-bold text-primary small">
                                                <i class="fas fa-folder mr-2"></i>MODULE {{ $moduleName }}
                                            </span>
                                        </td>
                                        <td></td>
                                        <td class="text-right pr-4">
                                            <span class="badge badge-white border text-muted small">{{ count($modulePermissions) }}</span>
                                        </td>
                                    </tr>

                                    @foreach ($modulePermissions as $p)
                                        <tr class="{{ $p->id == $permission->id ? 'bg-yellow-light' : '' }}">
                                            <td class="pl-4 text-muted small">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="d-block font-weight-600 {{ $p->id == $permission->id ? 'text-primary' : 'text-dark' }}">
                                                    {{ $p->description }}
                                                </span>
                                            </td>
                                            <td>
                                                <code class="text-primary small">{{ $p->name }}</code>
                                            </td>
                                            <td class="text-right pr-4">
                                                @if($p->id != $permission->id)
                                                <a href="{{ route('admin.permission.edit', $p->id) }}" class="text-primary mr-2" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @else
                                                <span class="badge badge-warning">Đang sửa</span>
                                                @endif
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
    .bg-light-primary { background-color: #f0f4ff; }
    .bg-yellow-light { background-color: #fffdf0; border-right: 3px solid #f6c23e; }
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .border-top-primary { border-top: 4px solid #4e73df !important; }
    .table thead th { font-size: 0.7rem; color: #858796; }
    .font-weight-600 { font-weight: 600; }
    .form-control { border-radius: 0.35rem; font-size: 0.9rem; }
</style>
@endsection