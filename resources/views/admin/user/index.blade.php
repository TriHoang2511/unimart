@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card shadow-sm border-0">

            {{-- FLASH MESSAGE --}}
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif  

            {{-- HEADER --}}
            <div
                class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-users me-2"></i> Danh sách thành viên
                </h5>

                <form action="{{ route('admin.user.index') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control bg-light border-0 small"
                            placeholder="Tìm họ tên, email..." value="{{ request('keyword') }}">
                        <button class="btn btn-primary">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- BODY --}}
            <div class="card-body">

                {{-- STATUS FILTER --}}
                <ul class="status-filter mb-4">
                    <li class="{{ !request('status') ? 'active' : '' }}">
                        <a href="{{ route('admin.user.index') }}">
                            Kích hoạt
                            <span class="count">{{ $count['active'] }}</span>
                        </a>
                    </li>

                    <li class="{{ request('status') === 'trash' ? 'active' : '' }}">
                        <a href="{{ route('admin.user.index', ['status' => 'trash']) }}">
                            Vô hiệu hóa
                            <span class="count">{{ $count['trash'] }}</span>
                        </a>
                    </li>
                </ul>

                {{-- BULK ACTION FORM --}}
                <form action="{{ route('admin.user.action') }}" method="POST">
                    @csrf

                    <div class="form-inline mb-3">
                        <select name="act" class="form-control form-control-sm mr-2" style="width:180px">
                            <option value="">-- Chọn tác vụ --</option>
                            @foreach ($list_act as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
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
                                    <th width="50">#</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Quyền</th>
                                    <th>Ngày tạo</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr class="{{ Auth::id() == $user->id ? 'bg-light' : '' }}">
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $user->id }}">
                                        </td>

                                        <td>
                                            {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                                        </td>

                                        <td>
                                            <strong>{{ $user->name }}</strong>
                                            @if (Auth::id() === $user->id)
                                                <span class="badge badge-secondary ml-1">Tôi</span>
                                            @endif
                                        </td>

                                        <td>{{ $user->email }}</td>

                                        <td>
                                            @foreach ($user->roles as $role)
                                                <span class="badge badge-light border text-primary">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </td>

                                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>

                                        <td class="text-right">
                                            @if (!$user->trashed())
                                                <a href="{{ route('admin.user.edit', $user) }}"
                                                    class="btn btn-outline-light text-primary border-0">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @if (Auth::id() !== $user->id)
                                                    <button type="button"
                                                        class="btn btn-outline-light text-danger border-0 btn-delete"
                                                        data-id="{{ $user->id }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-muted fst-italic">Đã vô hiệu</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            Không có thành viên nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

                {{-- PAGINATION --}}
                <div class="mt-3 d-flex justify-content-center">
                    {{ $users->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!confirm('Xác nhận vô hiệu hóa user này?')) return;

                    const id = this.dataset.id;
                    const form = document.createElement('form');

                    form.method = 'POST';
                    form.action = '/admin/user/' + id;

                    form.innerHTML = `
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        });
    </script>

    {{-- CSS --}}
    <style>
        .status-filter {
            list-style: none;
            display: flex;
            gap: 10px;
            padding: 0;
            margin-bottom: 20px;
        }

        .status-filter li a {
            padding: 8px 14px;
            border-radius: 999px;
            background: #f1f3f5;
            text-decoration: none;
        }

        .status-filter li.active a {
            background: #0984e3;
            color: #fff;
        }   

        .status-filter .count {
            margin-left: 6px;
            background: rgba(255, 255, 255, .3);
            padding: 2px 8px;
            border-radius: 999px;
        }
    </style>
@endsection
