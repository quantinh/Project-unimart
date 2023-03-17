<!DOCTYPE html>
<html>

<head>
    <title>Unimart Store</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="image/x-icon" rel="shortcut icon" href="{{ asset('images/reloads/unimart1.png') }}">
    <!-- Bootstrap-Css -->
    <link href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="{{ asset('responsive.css') }}">
    <!-- Font-Awesome -->
    <link href="{{ asset('css/font-awesome/fontawesome-free-6.1.1-web/css/all.min.css') }}" rel="stylesheet"
        type="text/css" />
    <!-- JQuery first, then Popper.js, then Bootstrap JS -->
    <link rel="stylesheet" href="{{ asset('css/owlcarousel/owl.theme.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/owlcarousel/assets/owl.carousel.css') }}">
    <script src="{{ asset('css/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('css/bootstrap/js/jquery-3.2.1.slim.min.js') }}"></script>
    <script src="{{ asset('css/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('css/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('css/bootstrap/js/bootstrap.min4.js') }}"></script>
    <!-- Carousel-Slide -->
    <link rel="stylesheet" href="{{ asset('css/owlcarousel/owl.theme.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/owlcarousel/assets/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owlcarousel/assets/owl.theme.default.min.css') }}">
    <!-- Import-Javascript  -->
    <script src="{{ asset('js/jquery-3.2.1.js') }}"></script>
    <script src="{{ asset('css/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('css/elevatezoom-master/jquery.elevatezoom.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- Import-Sweetalert --}}
    <script src="{{ asset('js/sweetalert1.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.js') }}" type="text/javascript"></script>
</head>

<body>
    <!-- Wrapper -->
    <div class="app">
        <!-- Header-lấy nội dung được chia nhỏ vào  -->
        @include('components.header')
        {{-- Content -- Tạo cổng lấy các nội dung được định nghĩa --}}
        @yield('content')
        <!-- Footer-lấy nộp dung được chia nhỏ vào -->
        @include('components.footer')
    </div>
    <!-- Menu-responsive-mobile -->
    <div class="menu-responsive desktop-on-hide">
        <!-- menu-responsive lấy nộp dung được chia nhỏ vào -->
        @include('components.menu-respon')
    </div>
    <!-- Nút scroll back-top -->
    <script src="{{ asset('js/scroll.js') }}"></script>
</body>

</html>
