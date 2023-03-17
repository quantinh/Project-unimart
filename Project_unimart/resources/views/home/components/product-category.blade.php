<div class="col l-9">
    <!-- Method-payment  -->
    <div class="box-payment">
        <div class="box-payment-content">
            <ul class="list-payment">
                <li class="item-payment">
                    <div class="item-payment-thumb">
                        <img class="item-payment-img" src="{{ asset('images/icons/icon-1.png') }}">
                    </div>
                    <h3 class="item-title">Miễn phí vận chuyển</h3>
                    <p class="item-desc">Tới tận tay khách hàng</p>
                </li>
                <li class="item-payment">
                    <div class="item-payment-thumb">
                        <img class="item-payment-img" src="{{ asset('images/icons/icon-2.png') }}">
                    </div>
                    <h3 class="item-title">Tư vấn 24/7</h3>
                    <p class="item-desc">1900.9999</p>
                </li>
                <li class="item-payment">
                    <div class="item-payment-thumb">
                        <img class="item-payment-img" src="{{ asset('images/icons/icon-3.png') }}">
                    </div>
                    <h3 class="item-title">Tiết kiệm hơn</h3>
                    <p class="item-desc">Với nhiều ưu đãi cực lớn</p>
                </li>
                <li class="item-payment">
                    <div class="item-payment-thumb">
                        <img class="item-payment-img" src="{{ asset('images/icons/icon-4.png') }}">
                    </div>
                    <h3 class="item-title">Thanh toán nhanh</h3>
                    <p class="item-desc">Hỗ trợ nhiều hình thức</p>
                </li>
                <li class="item-payment">
                    <div class="item-payment-thumb">
                        <img class="item-payment-img" src="{{ asset('images/icons/icon-5.png') }}">
                    </div>
                    <h3 class="item-title">Đặt hàng online</h3>
                    <p class="item-desc">Thao tác đơn giản</p>
                </li>
            </ul>
        </div>
    </div>
    <!-- Product-featured -->
    @include('home.components.product-featured')
    <!-- Product-sale  -->
    <div class="box">
        @isset($product_by_categorys)
            @if (count($product_by_categorys) > 0)
                <div class="box-heading">
                    <h3 class="box-title">PC - WORKSTATION</h3>
                </div>
                <div class="box-content">
                    <div class="row sm-gutter" id="post-product-wp">
                        {{-- Đổ dữ liệu sản phẩm theo danh mục PC- WORKSTATION  --}}
                        @foreach($product_by_categorys as $product_category)
                            <div class="col l-3 me-4 c-6">
                                <div class="list-item">
                                    <div class="item-info">
                                        <a href="{{ route('product.detail', $product_category->slug) }}" title="" class="product-item-link">
                                            <img class="product-item-img"
                                                src="{{ asset($product_category->image) }}">
                                            <div class="product-item-new">
                                                <img class="item-new-img-new" src="{{ asset('images/icons/new.png') }}"
                                                    alt="">
                                            </div>
                                            <div class="product-item-sale">
                                                <img class="item-new-img-sale"
                                                    src="{{ asset('images/icons/sale.png') }}" alt="">
                                            </div>
                                        </a>
                                        <a href="{{ route('product.detail', $product_category->slug) }}" title="" class="product-item-name">
                                            {{ $product_category->name_product }}
                                        </a>
                                        <div class="product-item-price">
                                            <span class="product-item-price-new">{{ number_format($product_category->price) }}đ</span>
                                            <span class="product-item-price-old">{{ number_format($product_category->price_old) }}đ</span>
                                        </div>
                                        <div class="product-item-info">
                                            <button data-id="{{ $product_category->id }}" class="product-item-add add-to-cart">Thêm giỏ hàng</button>
                                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                            <input type="hidden" id="url" url="{{ route('cart.add') }}"/>
                                            <a href="{{ route('cart.buyNow', $product_category->id) }}" title="" class="product-item-buy">Mua ngay</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="bg-article"></div>
                        <button id="see-more">Xem thêm</button>
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-font">
                    Hiện tại chưa có sản phẩm cho mục này, bạn vui lòng <a href="{{ url('/') }}" class="alert-link">Click vào
                        đây</a> để trở về trang
                    chủ.
                </div>
            @endif
        @endisset
    </div>
    <!-- Product-sale-2  -->
    <div class="box">
        @isset($product_by_categorys_sub)
            @if (count($product_by_categorys_sub) > 0)
                <div class="box-heading">
                    <h3 class="box-title">CPU - BỘ VI XỬ LÝ</h3>
                </div>
                <div class="box-content">
                    <div class="row sm-gutter">
                        {{-- Đổ dữ liệu sản phẩm theo danh mục PC- WORKSTATION  --}}
                        @foreach($product_by_categorys_sub as $product_category_sub)
                            <!-- Product-item  -->
                            <div class="col l-3 me-4 c-6">
                                <div class="list-item">
                                    <div class="item-info">
                                        <a href="{{ route('product.detail', $product_category_sub->slug) }}" title="" class="product-item-link">
                                            <img class="product-item-img"
                                                src="{{ asset($product_category_sub->image) }}">
                                            <div class="product-item-new">
                                                <img class="item-new-img-new" src="{{ asset('images/icons/new.png') }}"
                                                    alt="">
                                            </div>
                                            <div class="product-item-sale">
                                                <img class="item-new-img-sale"
                                                    src="{{ asset('images/icons/sale.png') }}" alt="">
                                            </div>
                                        </a>
                                        <a href="{{ route('product.detail', $product_category_sub->slug) }}" title="" class="product-item-name">
                                            {{ $product_category_sub->name_product }}
                                        </a>
                                        <div class="product-item-price">
                                            <span class="product-item-price-new">{{ number_format($product_category_sub->price) }}đ</span>
                                            <span class="product-item-price-old">{{ number_format($product_category_sub->price_old) }}đ</span>
                                        </div>
                                        <div class="product-item-info">
                                            <button data-id="{{ $product_category_sub->id }}" class="product-item-add add-to-cart">Thêm giỏ hàng</button>
                                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                            <input type="hidden" id="url" url="{{ route('cart.add') }}"/>
                                            <a href="{{ route('cart.buyNow', $product_category_sub->id) }}" title="" class="product-item-buy">Mua ngay</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-font">
                    Hiện tại chưa có sản phẩm cho mục này, bạn vui lòng <a href="{{ url('/') }}" class="alert-link">Click vào
                        đây</a> để trở về trang
                    chủ.
                </div>
            @endif
        @endisset
    </div>
    <!-- Product-sale-3  -->
    <div class="box">
        @isset($product_by_categorys_sub_card)
            @if (count($product_by_categorys_sub_card) > 0)
                <div class="box-heading">
                    <h3 class="box-title">VGA - CARD MÀN HÌNH</h3>
                </div>
                <div class="box-content">
                    <div class="row sm-gutter">
                        {{-- Đổ dữ liệu sản phẩm theo danh mục VGA - CARD MÀN HÌNH  --}}
                        @foreach($product_by_categorys_sub_card as $product_category_sub_card)
                            <div class="col l-3 me-4 c-6">
                                <div class="list-item">
                                    <div class="item-info">
                                        <a href="{{ route('product.detail', $product_category_sub_card->slug) }}" title="" class="product-item-link">
                                            <img class="product-item-img"
                                                src="{{ asset($product_category_sub_card->image) }}">
                                            <div class="product-item-new">
                                                <img class="item-new-img-new" src="{{ asset('images/icons/new.png') }}"
                                                    alt="">
                                            </div>
                                            <div class="product-item-sale">
                                                <img class="item-new-img-sale"
                                                    src="{{ asset('images/icons/sale.png') }}" alt="">
                                            </div>
                                        </a>
                                        <a href="{{ route('product.detail', $product_category_sub_card->slug) }}" title="" class="product-item-name">
                                            {{ $product_category_sub_card->name_product }}
                                        </a>
                                        <div class="product-item-price">
                                            <span class="product-item-price-new">{{ number_format($product_category_sub_card->price) }}đ</span>
                                            <span class="product-item-price-old">{{ number_format($product_category_sub_card->price_old) }}đ</span>
                                        </div>
                                        <div class="product-item-info">
                                            <button data-id="{{ $product_category_sub->id }}" class="product-item-add add-to-cart">Thêm giỏ hàng</button>
                                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                            <input type="hidden" id="url" url="{{ route('cart.add') }}"/>
                                            <a href="{{ route('cart.buyNow', $product_category_sub->id) }}" title="" class="product-item-buy">Mua ngay</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-font">
                    Hiện tại chưa có sản phẩm cho mục này, bạn vui lòng <a href="{{ url('/') }}" class="alert-link">Click vào
                        đây</a> để trở về trang
                    chủ.
                </div>
            @endif
        @endisset
    </div>
    <!-- Product-sale-4  -->
    <div class="box">
        @isset($product_by_categorys_sub_screen)
            @if (count($product_by_categorys_sub_screen) > 0)
                <div class="box-heading">
                    <h3 class="box-title">MÀN HÌNH MÁY TÍNH</h3>
                </div>
                <div class="box-content">
                    <div class="row sm-gutter">
                        {{-- Đổ dữ liệu sản phẩm theo danh mục VGA - CARD MÀN HÌNH  --}}
                        @foreach($product_by_categorys_sub_screen as $product_category_sub_screen)
                            <div class="col l-3 me-4 c-6">
                                <div class="list-item">
                                    <div class="item-info">
                                        <a href="{{ route('product.detail', $product_category_sub_screen->slug) }}" title="" class="product-item-link">
                                            <img class="product-item-img"
                                                src="{{ asset($product_category_sub_screen->image) }}">
                                            <div class="product-item-new">
                                                <img class="item-new-img-new" src="{{ asset('images/icons/new.png') }}"
                                                    alt="">
                                            </div>
                                            <div class="product-item-sale">
                                                <img class="item-new-img-sale"
                                                    src="{{ asset('images/icons/sale.png') }}" alt="">
                                            </div>
                                        </a>
                                        <a href="{{ route('product.detail', $product_category_sub_screen->slug) }}" title="" class="product-item-name">
                                            {{ $product_category_sub_screen->name_product }}
                                        </a>
                                        <div class="product-item-price">
                                            <span class="product-item-price-new">{{ number_format($product_category_sub_screen->price) }}đ</span>
                                            <span class="product-item-price-old">{{ number_format($product_category_sub_screen->price_old) }}đ</span>
                                        </div>
                                        <div class="product-item-info">
                                            <button data-id="{{ $product_category_sub_screen->id }}" class="product-item-add add-to-cart">Thêm giỏ hàng</button>
                                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                            <input type="hidden" id="url" url="{{ route('cart.add') }}"/>
                                            <a href="{{ route('cart.buyNow', $product_category_sub_screen->id) }}" title="" class="product-item-buy">Mua ngay</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-font">
                    Hiện tại chưa có sản phẩm cho mục này, bạn vui lòng <a href="{{ url('/') }}" class="alert-link">Click vào
                        đây</a> để trở về trang
                    chủ.
                </div>
            @endif
        @endisset
    </div>
</div>
