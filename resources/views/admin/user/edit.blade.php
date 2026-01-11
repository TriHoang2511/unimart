@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa người dùng
            </div>
            <div class="card-body">
                {{-- Giả sử route cập nhật là admin/user/update/{id} --}}
                <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        {{-- Hiển thị dữ liệu cũ và dữ liệu nhập lại sau lỗi (old()) --}}
                        <input class="form-control" type="text" name="name" id="name"
                            value="{{ old('name', $user->name) }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        {{-- Hiển thị dữ liệu cũ và không cho phép thay đổi nếu không cần thiết --}}
                        <input class="form-control" type="text" name="email" id="email"
                            value="{{ old('email', $user->email) }}" readonly>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Nhóm quyền</label>

                        @foreach ($roles as $role)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="roles[]" value="{{ $role->id }}"
                                    id="role_{{ $role->id }}"
                                    {{ in_array($role->id, $selectedRoles) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        @endforeach

                    </div>

                    <button type="submit" name="btn_update" value="Cập nhật" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
