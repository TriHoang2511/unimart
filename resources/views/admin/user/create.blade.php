@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-plus mr-2"></i>Thêm người dùng mới
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ url('admin/user/store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="name" class="font-weight-600">Họ và tên</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text"
                                            name="name" id="name" value="{{ old('name') }}"
                                            placeholder="VD: Nguyễn Văn A">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="email" class="font-weight-600">Email</label>
                                        <input class="form-control @error('email') is-invalid @enderror" type="email"
                                            name="email" id="email" value="{{ old('email') }}"
                                            placeholder="example@gmail.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="password" class="font-weight-600">Mật khẩu</label>
                                        <div class="input-group">
                                            <input class="form-control @error('password') is-invalid @enderror"
                                                type="password" name="password" id="password"
                                                placeholder="Tối thiểu 8 ký tự">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="password-confirm" class="font-weight-600">Xác nhận mật khẩu</label>
                                        <input class="form-control" type="password" name="password_confirmation"
                                            id="password-confirm" placeholder="Nhập lại mật khẩu">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="role" class="font-weight-600">Nhóm quyền</label>
                                @foreach ($roles as $role)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="roles[]"
                                            value="{{ $role->id }}" id="role_{{ $role->id }}"
                                            {{ is_array(old('roles')) && in_array($role->id, old('roles')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end">
                                <a href="{{ url('admin/user/list') }}" class="btn btn-light border mr-2 px-4">Hủy bỏ</a>
                                <button type="submit" name="btn_add" value="Thêm mới"
                                    class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-save mr-2"></i>Thêm mới
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* CSS bổ trợ cho form */
        .font-weight-600 {
            font-weight: 600;
            color: #4e73df;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            border: 1px solid #d1d3e2;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
        }

        .card-header {
            background-color: #f8f9fc !important;
        }

        .btn {
            border-radius: 8px;
            font-weight: 600;
        }

        .custom-select {
            height: calc(1.5em + 1.2rem + 2px);
        }
    </style>
@endsection
