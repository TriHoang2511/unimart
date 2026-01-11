@extends('layouts.admin')
@section('title', 'Danh sách vai trò')
@section('content')
    <div id="content" class="container-fluid py-4">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-shield mr-2"></i>Danh sách vai trò
                </h5>
                <div class="form-search">
                    <form action="#" class="form-inline">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small"
                                placeholder="Tìm tên vai trò..." style="border-radius: 20px 0 0 20px;">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" style="border-radius: 0 20px 20px 0;">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="form-action form-inline">
                        <select class="form-control form-control-sm mr-2" id="bulk-action" style="width: 150px;">
                            <option value="">-- Tác vụ --</option>
                            <option value="delete">Xóa vĩnh viễn</option>
                        </select>
                        <button type="submit" class="btn btn-secondary btn-sm px-3">Áp dụng</button>
                    </div>
                    <a href="{{ route('admin.role.create') }}" class="btn btn-success btn-sm shadow-sm">
                        <i class="fas fa-plus mr-1"></i> Thêm vai trò mới
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle border-bottom">
                        <thead class="thead-light">
                            <tr>
                                <th width="40" class="pl-4">
                                    <input name="checkall" type="checkbox" id="checkall">
                                </th>
                                <th scope="col" width="60">#</th>
                                <th scope="col" width="200">Vai trò</th>
                                <th scope="col">Mô tả nhiệm vụ</th>
                                <th scope="col" width="180">Ngày tạo</th>
                                <th scope="col" width="120" class="text-right pr-4">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Row 1: Admin --}}
                            @forelse ($roles as $role)
                                <tr>
                                    <td class="pl-4">
                                        <input type="checkbox" name="list_check[]" value="{{ $role->id }}">
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('admin.role.edit', $role->id) }}" class="font-weight-bold text-dark">{{ $role->name }}</a>
                                        <span class="d-block small text-muted">ID:
                                            #ROLE_{{ str_pad($role->id, 2, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td><span class="text-dark">{{ $role->description }}</span></td>
                                    <td class="text-muted small">{{ $role->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-right pr-4">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.role.edit', $role->id) }}"
                                                class="btn btn-outline-light text-primary border-0" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.role.destroy', $role->id) }}"
                                                onclick="return confirm('Xác nhận xóa vai trò này?')"
                                                class="btn btn-outline-light text-danger border-0" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Không tìm thấy vai trò nào.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Nâng cấp thẩm mỹ bảng */
        .table thead th {
            background-color: #f8f9fc;
            border-bottom: 2px solid #e3e6f0;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            color: #5a5c69;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .btn-group .btn:hover {
            background-color: #f8f9fc;
            transform: translateY(-1px);
        }

        .font-weight-bold {
            letter-spacing: -0.02em;
        }

        /* Hiệu ứng hover cho hàng */
        .table-hover tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.03);
        }

        /* Chỉnh lại link vai trò */
        a.font-weight-bold:hover {
            text-decoration: underline;
        }
    </style>
@endsection
