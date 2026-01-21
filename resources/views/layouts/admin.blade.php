<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/solid.min.css">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <title>Admintrator</title>
     {{-- STACK STYLES --}}
    @stack('styles')

</head>

<body>
    <div id="warpper" class="nav-fixed">
        <nav class="topnav shadow navbar-light bg-white d-flex">
            <div class="navbar-brand"><a href="?">UNIMART ADMIN</a></div>
            <div class="nav-right ">
                <div class="btn-group mr-auto">
                    <button type="button" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="plus-icon fas fa-plus-circle"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ url('admin/post/add') }}">Thêm bài viết</a>
                        <a class="dropdown-item" href="{{ url('admin/product/add') }}">Thêm sản phẩm</a>
                        <a class="dropdown-item" href="{{ url('admin/order/add') }}">Thêm đơn hàng</a>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        {{ Auth::user()->name }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Tài khoản</a>

                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Thoát
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <!-- end nav  -->
        {{-- @php
            $module_active = session('module_active');
        @endphp --}}
        <div id="page-body" class="d-flex">
            <div id="sidebar" class="bg-white">
                {{-- {{$module_active}} --}}
                <ul id="sidebar-menu">
                    <li class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ url('admin/dashboard') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Dashboard
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                    </li>
                    <li class="nav-link {{ request()->routeIs('admin.page.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.page.index') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Trang
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            <li><a href="{{ route('admin.page.create') }}">Thêm mới</a></li>
                            <li><a href="{{ route('admin.page.index') }}">Danh sách</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ request()->routeIs('admin.post.*') ? 'active' : '' }}">
                        <a href="{{ url('admin/post/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Bài viết
                        </a>
                        <i
                            class="arrow fas {{ request()->routeIs('admin.post.*') ? 'fa-angle-down' : 'fa-angle-right' }}">
                        </i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/post/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/post/list') }}">Danh sách</a></li>
                            <li><a href="{{ url('admin/post/cat/add') }}">Danh mục</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ request()->routeIs('admin.product.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.product.index') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Sản phẩm
                        </a>

                        <i
                            class="arrow fas {{ request()->routeIs('admin.product.*') ? 'fa-angle-down' : 'fa-angle-right' }}"></i>

                        <ul class="sub-menu">
                            <li><a href="{{ route('admin.product.index') }}">Danh sách</a></li>
                            <li><a href="{{ route('admin.product.create') }}">Thêm mới</a></li>

                            <hr style="border-top: 1px solid #444; margin: 5px 15px;">
                            <li><a href="{{ route('admin.product.cat.index') }}">Danh mục</a></li>

                            <li>
                                <a href="{{ route('admin.product.attributes.index') }}">
                                    Thuộc tính (Màu, Size...)
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-link {{ request()->routeIs('admin.order.list') ? 'active' : '' }}">
                        <a href="{{ url('admin/order/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Bán hàng
                        </a>
                        <i
                            class="arrow fas {{ request()->routeIs('admin.order.*') ? 'fa-angle-down' : 'fa-angle-right' }}">
                        </i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/order/list') }}">Đơn hàng</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                        <a href="{{ url('admin/user/') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Users
                        </a>
                        {{-- Mẹo: Đổi icon mũi tên khi menu đang mở --}}
                        <i
                            class="arrow fas {{ request()->routeIs('admin.user.*') ? 'fa-angle-down' : 'fa-angle-right' }}">
                        </i>

                        <ul class="sub-menu">
                            <li>
                                <a href="{{ url('admin/user/create') }}"
                                    class="{{ request()->routeIs('admin.user.create') ? 'text-primary' : '' }}">Thêm
                                    mới</a>
                            </li>
                            <li>
                                <a href="{{ url('admin/user/') }}"
                                    class="{{ request()->routeIs('admin.user.index') ? 'text-primary' : '' }}">Danh
                                    sách</a>
                            </li>
                        </ul>
                    </li>

                    @canany(['permission.view', 'role.view', 'role.create', 'role.edit', 'role.delete'])
                        <li
                            class="nav-link {{ request()->routeIs('admin.permission.*', 'admin.role.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.permission.index') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Phân quyền
                            </a>

                            <i
                                class="arrow fas {{ request()->routeIs('admin.permission.*', 'admin.role.*') ? 'fa-angle-down' : 'fa-angle-right' }}"></i>

                            <ul class="sub-menu">
                                {{-- Danh sách quyền (list + create) --}}
                                @can('permission.view')
                                    <li>
                                        <a href="{{ route('admin.permission.index') }}"
                                            class="{{ request()->routeIs('admin.permission.*') ? 'text-primary' : '' }}">
                                            Danh sách quyền
                                        </a>
                                    </li>
                                @endcan

                                {{-- Danh sách vai trò --}}
                                @can('role.view')
                                    <li>
                                        <a href="{{ route('admin.role.index') }}"
                                            class="{{ request()->routeIs('admin.role.index') ? 'text-primary' : '' }}">
                                            Danh sách vai trò
                                        </a>
                                    </li>
                                @endcan

                                {{-- Thêm vai trò --}}
                                @can('role.create')
                                    <li>
                                        <a href="{{ route('admin.role.create') }}"
                                            class="{{ request()->routeIs('admin.role.create') ? 'text-primary' : '' }}">
                                            Thêm vai trò
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                </ul>
            </div>
            <div id="wp-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin/js/app.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    {{-- STACK SCRIPTS --}}
    @stack('scripts')
</body>

</html>
