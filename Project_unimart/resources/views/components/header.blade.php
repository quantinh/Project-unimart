<header class="header">
    <!-- Header-top  -->
    <div class="header-top">
        <div class="grid wide">
            <nav class="header-navbar">
                <ul class="header-navbar-list">
                    <li class="header-navbar-item header-navbar-item--separate">
                        {{-- lấy giá trị link cấu hình từ admin  --}}
                        {!! getConfigValueFromSettingTable('link_support_online') !!}
                    </li>
                    <li class="header-navbar-item">
                        {{-- lấy giá trị link cấu hình từ admin  --}}
                        {!! getConfigValueFromSettingTable('link_buid_config') !!}
                    </li>
                </ul>
                <ul class="header-navbar-list">
                    {{-- Nếu khách hàng đã đăng nhập thì hiển thị tên và đăng xuất  --}}
                    <?php $customer_id = session()->get('id');
                        $customer_name = session()->get('username');
                        if($customer_id != null) {
                    ?>
                        <li class="header-navbar-item header-navbar-item--separate">
                            <a class="header-navbar-item-link" href="{{ url('dang-ki') }}">Xin chào {{ $customer_name }}</i></a>
                        </li>
                        <li class="header-navbar-item">
                            <a class="header-navbar-item-link" href="{{ route('user.logout') }}">Đăng xuất</a>
                        </li>
                    {{-- Ngược lại nếu khách hàng chưa đăng nhập thì hiển thị đăng nhập và đăng kí  --}}
                    <?php } else { ?>
                        <li class="header-navbar-item header-navbar-item--separate">
                            <a class="header-navbar-item-link" href="{{ url('dang-ki') }}">Đăng ký</a>
                        </li>
                        <li class="header-navbar-item">
                            <a class="header-navbar-item-link" href="{{ url('dang-nhap') }}">Đăng nhập</a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Header-body  -->
    <div class="grid wide">
        <div class="header-width-search">
            <label for="mobile-search-checkbox" class="header-mobile-search">
                <i class="header-mobile-search-icon fas fa-search"></i>
            </label>
            <!-- Header-logo -->
            <div class="header-logo">
                <a href="{{ url('/') }}" class="header-logo-link">
                    <img src="{{ asset('images/logos/unimart.png')}}" class="header-logo-img" alt="">
                </a>
            </div>
            <!-- Header-search -->
            <input type="checkbox" hidden class="header-search-checkbox">
            <form class="header-search" method="POST" action="{{ url('tim-kiem') }}">
                @csrf
                <div class="header-search-input-wrap">
                    <input type="text" class="header-search-input" name="keyword" placeholder="Nhập để tìm kiếm sản phẩm">
                </div>
                <button class="header-search-btn" type="submit" name="search" value="search">Tìm kiếm</button>
            </form>
            <!-- Header-service -->
            <div class="wrapper-header-support">
                <img class="header-support-icon" src="{{ asset('images/icons/giphy.gif')}}" alt="">
                <div class="header-support">
                    <span class="header-support-sub">Tư vấn</span>
                    <span class="header-support-phone">037.795.3849</span>
                </div>
            </div>
            <!-- Header-cart -->
            <div class="header-cart">
                <div class="header-cart-wrap">
                    <i class="header-cart-icon fa fa-shopping-cart"></i>
                    {{-- Số lượng sản phẩm có trong giỏ hàng   --}}
                    <span class="header-cart-notice" id="num">{{Cart::count()}}</span>
                    @if (Cart::count() > 0)
                        <div class="header-cart-list">
                            @if(Cart::count() > 0)
                                <h4 class="header-cart-heading">Sản phẩm đã thêm</h4>
                                <ul class="header-cart-list-item">
                                    @foreach (Cart::content() as $item)
                                        <li class="header-cart-item">
                                            <a href="{{ route('product.detail', $item->options->slug) }}" title="" class="header-cart-item-link">
                                                <img src="{{ asset($item->options->image) }}" class="header-cart-img">
                                            </a>
                                            <div class="header-cart-item-info">
                                                <div class="header-cart-item-head">
                                                    <a href="{{ route('product.detail', $item->options->slug) }}" title="" class="header-cart-item-link">
                                                        <h5 class="header-cart-item-name">{{ Str::of($item->name)->limit(45) }}</h5>
                                                    </a>
                                                    <div class="header-cart-item-price-wrap">
                                                        <span class="header-cart-item-price">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                                                        <span class="header-cart-item-multiply">x</span>
                                                        <span class="header-cart-item-qnt">{{ $item->qty }}</span>
                                                    </div>
                                                </div>
                                                <div class="header-cart-item-body">
                                                    <span class="header-cart-item-description"></span>
                                                    <a class="del-cart" href="{{ route('cart.remove', $item->rowId)}}">
                                                        <span class="header-cart-item-remove">xóa</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    <span class="header-cart-item-total mr-3">
                                        <p class="total-price">Tổng: {{ number_format(Cart::total(), 0, ',', '.') }}đ</p>
                                    </span>
                                </ul>
                                @if($customer_id != null)
                                    <div class="header-cart-btn">
                                        <a href="{{ url('gio-hang') }}" class="header-cart-view-cart btn btn--primary">Giỏ hàng</a>
                                        <a href="{{ route('cart.order') }}" class="header-cart-view-cart-pay btn btn--primary">Thanh toán</a>
                                    </div>
                                @else
                                    <div class="header-cart-btn">
                                        <a href="{{ url('gio-hang') }}" class="header-cart-view-cart btn btn--primary">Giỏ hàng</a>
                                        <a href="{{ route('form.login') }}" class="header-cart-view-cart-pay btn btn--primary">Thanh toán</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @else
                        <div class="header-cart-list">
                            <h4 class="header-cart-heading">Không có sản phẩm nào trong giỏ hàng !</h4>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Header-icon-respon -->
            <i id="btn-responsive" class="fa fa-bars menu-toggle mobile-on-hide"></i>
        </div>
    </div>
</header>
<!-- Header-bottom  -->
<div class="header-bottom">
    <div class="grid wide">
        <nav class="header-bottom-navbar">
            <ul class="header-bottom-list">
                <li class="header-bottom-item">
                    <a class="header-bottom-item-link" href="{{ url('/') }}">{{ $list_name_menu_one->name_menu }}</a>
                </li>
                <li class="header-bottom-item">
                    <a class="header-bottom-item-link" href="{{ url('danh-sach-san-pham') }}">{{ $list_name_menu_two->name_menu }}</a>
                </li>
                @foreach($list_menus as $item)
                <li class="header-bottom-item">
                    <a class="header-bottom-item-link" href="{{ route('page', $item->slug) }}">{{ $item->title }}</a>
                </li>
                @endforeach
                <li class="header-bottom-item">
                    <a class="header-bottom-item-link" href="{{ url('bai-viet')}}">{{ $list_name_menu_tree->name_menu }}</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
