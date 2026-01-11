@extends('layouts.admin')
@section('title', 'Thêm mới vai trò')

@section('content')
    <div id="content" class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-tag mr-2"></i>Thêm mới vai trò</h5>
                <a href="{{ route('admin.role.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                    <i class="fas fa-list mr-1"></i> Danh sách vai trò
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.role.store') }}">
                    @csrf

                    {{-- Thông tin cơ bản --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark" for="name">Tên vai trò</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text"
                                    name="name" id="name" placeholder="Ví dụ: Quản lý đơn hàng"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark" for="description">Mô tả vai trò</label>
                                <textarea class="form-control" name="description" id="description" rows="3"
                                    placeholder="Mô tả ngắn gọn chức năng của vai trò này">{{ old('description') }}</textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="mb-3">
                        <h6 class="font-weight-bold text-dark"><i class="fas fa-shield-alt mr-2 text-primary"></i>Phân quyền
                            cho vai trò</h6>
                        <small class="text-muted">Tích vào tiêu đề Module để chọn tất cả các quyền trong nhóm đó.</small>
                    </div>

                    {{-- Danh sách quyền động --}}
                    <div class="row">
                        @foreach ($permissions as $moduleName => $modulePermissions)
                            <div class="col-12 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-light py-2">
                                        <div class="form-check">
                                            <input class="form-check-input check-all-module" type="checkbox"
                                                id="mod-{{ $moduleName }}">
                                            <label class="form-check-label fw-bold text-primary text-uppercase"
                                                for="mod-{{ $moduleName }}">
                                                Module {{ $moduleName }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="card-body py-3">
                                        <div class="row">
                                            @foreach ($modulePermissions as $permission)
                                                <div class="col-md-3">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input permission-checkbox" type="checkbox"
                                                            name="permissions[]" value="{{ $permission->id }}"
                                                            data-module="{{ $moduleName }}"
                                                            id="p-{{ $permission->id }}">
                                                        <label class="form-check-label" for="p-{{ $permission->id }}">
                                                            {{ $permission->description }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 border-top pt-3 text-right">
                        <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold">
                            <i class="fas fa-plus-circle mr-1"></i> Tạo vai trò mới
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .card-header {
            border-bottom: 1px solid #edf2f9;
        }

        .form-check-input {
            cursor: pointer;
            padding-top: 2px;
        }

        .check-all-module:checked+.form-check-label {
            color: #4e73df !important;
        }

        .card {
            border-radius: 0.5rem;
        }
    </style>

    <script>
        document.querySelectorAll('.check-all-module').forEach(moduleCheckbox => {
            moduleCheckbox.addEventListener('change', function() {
                const card = this.closest('.card');
                card.querySelectorAll('.permission-checkbox')
                    .forEach(cb => cb.checked = this.checked);
            });
        });

        document.querySelectorAll('.permission-checkbox').forEach(permissionCheckbox => {
            permissionCheckbox.addEventListener('change', function() {
                const card = this.closest('.card');
                const all = card.querySelectorAll('.permission-checkbox');
                const checked = card.querySelectorAll('.permission-checkbox:checked');
                card.querySelector('.check-all-module').checked = all.length === checked.length;
            });
        });
    </script>

@endsection
