<!-- List-product-content -->
<div class="col l-9">
    <!-- Product-sale-1 -->
    <div class="box">
        @if ($product_by_categorys->count() > 0)
            <div class="box-filter">
                <p class="desc-product">Hiển thị {{ $count_total_product_current }} trên {{ $count_total_product }} sản phẩm</p>
                <div class="form-filter">
                    <form method="" action="{{ url('danh-sach-san-pham') }}">
                        <select class="select" name="act">
                            <option value="">Sắp xếp</option>
                            @foreach($list_action as $k => $act)
                                <option value="{{ $k }}">{{ $act}}</option>
                            @endforeach
                        </select>
                        <button class="filter-button" type="submit">Lọc</button>
                    </form>
                </div>
            </div>
            <div class="box-heading">
                    <h3 class="box-title">PC - WORKSTATION</h3>
            </div>
            <div class="box-content">
                <div class="row sm-gutter">
                    <!-- Product-item  -->
                    @foreach($product_by_categorys as $item)
                        <div class="col l-3 me-4 c-6">
                            <div class="list-item">
                                <div class="item-info">
                                    <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-link">
                                        <img class="product-item-img"
                                            src="{{ asset($item->image) }}">
                                        <div class="product-item-new">
                                            <img class="item-new-img-new" src="public/images/new.png"
                                                alt="">
                                        </div>
                                        <div class="product-item-sale">
                                            <img class="item-new-img-sale" src="public/images/sale.png"
                                                alt="">
                                        </div>
                                    </a>
                                    <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-name">{{ $item->name_product }}</a>
                                    <div class="product-item-price">
                                        <span class="product-item-price-new">{{ number_format($item->price) }}đ</span>
                                        <span class="product-item-price-old">{{ number_format($item->price_old) }}đ</span>
                                    </div>
                                    <div class="product-item-info">
                                        <button data-id="{{ $item->id }}" class="product-item-add add-to-cart">Thêm giỏ hàng</button>
                                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                        <input type="hidden" id="url" url="{{ route('cart.add') }}"/>
                                        <a href="{{ route('cart.buyNow', $item->id) }}" title="" class="product-item-buy">Mua ngay</a>
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
    </div>
    <!-- Product-sale-2  -->
    <div class="box">
        @if ($product_by_categorys_sub->count() > 0)
            <div class="box-heading">
                <h3 class="box-title">CPU - BỘ VI XỬ LÝ</h3>
            </div>
            <div class="box-content">
                <div class="row sm-gutter">
                    <!-- Product-item  -->
                    @foreach($product_by_categorys_sub as $item)
                        <div class="col l-3 me-4 c-6">
                            <div class="list-item">
                                <div class="item-info">
                                    <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-link">
                                        <img class="product-item-img"
                                            src="{{ asset($item->image) }}">
                                        <div class="product-item-new">
                                            <img class="item-new-img-new" src="public/images/new.png"
                                                alt="">
                                        </div>
                                        <div class="product-item-sale">
                                            <img class="item-new-img-sale" src="public/images/sale.png"
                                                alt="">
                                        </div>
                                    </a>
                                    <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-name">{{ $item->name_product }}</a>
                                    <div class="product-item-price">
                                        <span class="product-item-price-new">{{ number_format($item->price) }}đ</span>
                                        <span class="product-item-price-old">{{ number_format($item->price_old) }}đ</span>
                                    </div>
                                    <div class="product-item-info">
                                        <button data-id="{{ $item->id }}" class="product-item-add add-to-cart">Thêm giỏ hàng</button>
                                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                        <input type="hidden" id="url" url="{{ route('cart.add') }}"/>
                                        <a href="#" title="" class="product-item-buy">Mua ngay</a>
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
    </div>
    <!-- Product-sale-3  -->
    <div class="box">
        @if ($product_by_categorys_sub_card->count() > 0)
            <div class="box-heading">
                <h3 class="box-title">VGA - CARD MÀN HÌNH</h3>
            </div>
            <div class="box-content">
                <div class="row sm-gutter">
                    <!-- Product-item  -->
                    @foreach($product_by_categorys_sub_card as $item)
                        <div class="col l-3 me-4 c-6">
                            <div class="list-item">
                                <div class="item-info">
                                    <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-link">
                                        <img class="product-item-img"
                                            src="{{ asset($item->image) }}">
                                        <div class="product-item-new">
                                            <img class="item-new-img-new" src="public/images/new.png"
                                                alt="">
                                        </div>
                                        <div class="product-item-sale">
                                            <img class="item-new-img-sale" src="public/images/sale.png"
                                                alt="">
                                        </div>
                                    </a>
                                    <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-name">{{ $item->name_product }}</a>
                                    <div class="product-item-price">
                                        <span class="product-item-price-new">{{ number_format($item->price) }}đ</span>
                                        <span class="product-item-price-old">{{ number_format($item->price_old) }}đ</span>
                                    </div>
                                    <div class="product-item-info">
                                        <button data-id="{{ $item->id }}" class="product-item-add add-to-cart">Thêm giỏ hàng</button>
                                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                        <input type="hidden" id="url" url="{{ route('cart.add') }}"/>
                                        <a href="#" title="" class="product-item-buy">Mua ngay</a>
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
    </div>
    <!-- Product-sale-4  -->
    <div class="box">
        @if ($product_by_categorys_sub_screen->count() > 0)
            <div class="box-heading">
                <h3 class="box-title">MÀN HÌNH MÁY TÍNH</h3>
            </div>
            <div class="box-content">
                <div class="row sm-gutter">
                    <!-- Product-item  -->
                    @foreach($product_by_categorys_sub_screen as $item)
                            <div class="col l-3 me-4 c-6">
                                <div class="list-item">
                                    <div class="item-info">
                                        <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-link">
                                            <img class="product-item-img"
                                                src="{{ asset($item->image) }}">
                                            <div class="product-item-new">
                                                <img class="item-new-img-new" src="public/images/new.png"
                                                    alt="">
                                            </div>
                                            <div class="product-item-sale">
                                                <img class="item-new-img-sale" src="public/images/sale.png"
                                                    alt="">
                                            </div>
                                        </a>
                                        <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-name">{{ $item->name_product }}</a>
                                        <div class="product-item-price">
                                            <span class="product-item-price-new">{{ number_format($item->price) }}đ</span>
                                            <span class="product-item-price-old">{{ number_format($item->price_old) }}đ</span>
                                        </div>
                                        <div class="product-item-info">
                                            <button data-id="{{ $item->id }}" class="product-item-add add-to-cart">Thêm giỏ hàng</button>
                                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                            <input type="hidden" id="url" url="{{ route('cart.add') }}"/>
                                            <a href="#" title="" class="product-item-buy">Mua ngay</a>
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
    </div>
    <!-- Product-sale-5  -->
    <div class="box">
        @if ($product_by_categorys_sub_keyboard->count() > 0)
            <div class="box-heading">
                <h3 class="box-title">BÀN PHÍM GAME</h3>
            </div>
            <div class="box-content">
                <div class="row sm-gutter">
                    <!-- Product-item  -->
                    @foreach($product_by_categorys_sub_keyboard as $item)
                            <div class="col l-3 me-4 c-6">
                                <div class="list-item">
                                    <div class="item-info">
                                        <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-link">
                                            <img class="product-item-img"
                                                src="{{ asset($item->image) }}">
                                            <div class="product-item-new">
                                                <img class="item-new-img-new" src="public/images/new.png"
                                                    alt="">
                                            </div>
                                            <div class="product-item-sale">
                                                <img class="item-new-img-sale" src="public/images/sale.png"
                                                    alt="">
                                            </div>
                                        </a>
                                        <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-name">{{ $item->name_product }}</a>
                                        <div class="product-item-price">
                                            <span class="product-item-price-new">{{ number_format($item->price) }}đ</span>
                                            <span class="product-item-price-old">{{ number_format($item->price_old) }}đ</span>
                                        </div>
                                        <div class="product-item-info">
                                            <button data-id="{{ $item->id }}" class="product-item-add add-to-cart">Thêm giỏ hàng</button>
                                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                            <input type="hidden" id="url" url="{{ route('cart.add') }}"/>
                                            <a href="#" title="" class="product-item-buy">Mua ngay</a>
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
    </div>
</div>
