<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel=icon href="{{ asset('images/reloads/admin.jpg') }}" type="image/png" sizes="32x32" type="image/png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/solid.min.css">
    {{-- Phần 24: bài 266 --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Admintrator</title>
</head>
<body>
    <div id="warpper" class="nav-fixed">
        <nav class="topnav shadow navbar-light bg-white d-flex">
            <div class="navbar-brand"><a href="{{url('admin')}}}" style="font-size: 1.2rem;">UNIMART ADMIN</a></div>
            <div class="nav-right ">
                <div class="btn-group mr-auto">
                    <button type="button" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="plus-icon fas fa-plus-circle"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ url('admin/posts/add') }}">Thêm bài viết</a>
                        <a class="dropdown-item" href="{{ url('admin/products/add') }}">Thêm sản phẩm</a>
                        <a class="dropdown-item" href="{{ url('admin/users/add') }}">Thêm thành viên</a>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{-- Phần 24 bài 266: hiển thị user khi đăng nhập --}}
                        {{ Auth::User()->name }}
                        <div id="thumb-circle" class="float-left">
                            <img class="thumb-img-avt img-fluid img-thumbnail" src="{{ asset(Auth::User()->avatar) }}">
                        </div>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('user.edit', Auth::id()) }}">Tài khoản</a>
                        <a class="dropdown-item" href="{{ url('/') }}">Trang chủ</a>
                        <a class="dropdown-item" href="{{ route('logout') }} "onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <!-- End nav  -->
        @php
            //Gọi session
            $module_active = session('module_active');
        @endphp
        <div id="page-body" class="d-flex">
            <div id="sidebar" class="bg-white">
                {{-- {{$module_active}} --}}
                <ul id="sidebar-menu">
                    {{-- Sidebar-dashboard --}}
                    <li class="nav-link {{ $module_active == 'dashboard' ? 'active' : '' }}">
                        <a href="{{ url('admin') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            Dashboard
                        </a>
                    </li>
                    {{-- Sidebar-user --}}
                    <li class="nav-link {{ $module_active == 'user' ? 'active' : '' }}">
                        <a href="{{ url('admin/users/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fas fa-users"></i>
                            </div>
                            Thành viên
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/users/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/users/list') }}">Danh sách</a></li>
                        </ul>
                    </li>
                    {{-- Sidebar-role --}}
                    <li class="nav-link {{ $module_active == 'role' ? 'active' : '' }}">
                        <a href="{{ url('admin/roles/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            Quyền admin
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/roles/list') }}">Danh sách</a></li>
                        </ul>
                    </li>
                    {{-- Sidebar-page --}}
                    <li class="nav-link {{ $module_active == 'page' ? 'active' : '' }}">
                        <a href="{{ url('admin/pages/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fas fa-map"></i>
                            </div>
                            Trang
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/pages/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/pages/list') }}">Danh sách</a></li>
                        </ul>
                    </li>
                    {{-- Sidebar-post --}}
                    <li class="nav-link {{ $module_active == 'post' ? 'active' : '' }}">
                        {{-- Phần 24 bài 267: Thiết lập đường dẫn cho các tác vụ --}}
                        <a href="{{ url('admin/posts/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fas fa-edit"></i>
                            </div>
                            Bài viết
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/posts/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/posts/list') }}">Danh sách</a></li>
                            <li><a href="{{ url('admin/posts/cat/list') }}">Danh mục</a></li>
                        </ul>
                    </li>
                    {{-- Sidebar-product --}}
                    <li class="nav-link {{ $module_active == 'product' ? 'active' : '' }}">
                        <a href="{{ url('admin/products/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fas fa-box-open"></i>
                            </div>
                            Sản phẩm
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/products/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/products/list') }}">Danh sách</a></li>
                            <li><a href="{{ url('admin/products/cat/list') }}">Danh mục</a></li>
                            <li><a href="{{ url('admin/products/brand/add') }}">Thương hiệu</a></li>
                            <li><a href="{{ url('admin/products/color/add') }}">Danh sách màu</a></li>
                        </ul>
                    </li>
                    {{-- Sidebar-sale --}}
                    <li class="nav-link {{ $module_active == 'order' ? 'active' : '' }}">
                        <a href="{{ url('admin/orders/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fas fa-cart-plus"></i>
                            </div>
                            Bán hàng
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/orders/list') }}">Đơn hàng</a></li>
                            <li><a href="{{ url('admin/orders/customer/list') }}">Khách hàng</a></li>
                        </ul>
                    </li>
                    {{-- Sidebar-slider --}}
                    <li class="nav-link {{ $module_active == 'slider' ? 'active' : '' }}">
                        <a href="{{ url('admin/sliders/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fas fa-images"></i>
                            </div>
                            Slider
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/sliders/add') }}">Thêm mới</a></li>
                        </ul>
                    </li>
                    {{-- Sidebar-banner --}}
                    <li class="nav-link {{ $module_active == 'banner' ? 'active' : '' }}">
                        <a href="{{ url('admin/banners/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fas fa-ad"></i>
                            </div>
                            Quảng cáo
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/banners/add') }}">Thêm mới</a>
                            </li>
                        </ul>
                    </li>
                    {{-- Sidebar-video --}}
                    <li class="nav-link {{ $module_active == 'video' ? 'active' : '' }}">
                        <a href="{{ url('admin/videos/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fas fa-photo-video"></i>
                            </div>
                            Video
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/videos/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/videos/list') }}">Danh sách</a></li>
                        </ul>
                    </li>
                    {{-- Sidebar-menu --}}
                    <li class="nav-link {{ $module_active == 'menu' ? 'active' : '' }}">
                        <a href="{{ url('admin/menus/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fab fa-elementor"></i>
                            </div>
                            Menu
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/menus/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/menus/list') }}">Danh sách</a></li>
                        </ul>
                    </li>
                    {{-- Sidebar-setting --}}
                    <li class="nav-link {{ $module_active == 'setting' ? 'active' : '' }}">
                        <a href="{{ url('admin/settings/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fas fa-cog"></i>
                            </div>
                            Setting
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/settings/add'.'?type=text') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/settings/list') }}">Danh sách</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            {{-- Tạo công để kết nối tới các trang --}}
            <div id="wp-content">
                {{-- Phần 24: bài 266 --}}
                @yield('content')
            </div>
        </div>
    </div>
    {{-- Xử lí javascript --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    {{-- Phần 24 bài: 266 --}}
    <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    {{-- Thư viện bootstrap qua link:cdn --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>
