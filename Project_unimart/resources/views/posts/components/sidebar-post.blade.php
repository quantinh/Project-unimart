<!-- Category  -->
<div class="col l-3 mobile-on-hide">
    <!-- Nav-home  -->
    <div class="section">
        <ul class="list-section">
            <i class="ml-0 item-section-icon fa-solid fa-house-chimney"></i>
            <li class="item-section">
                <a class="item-section-link" href="{{ url('/') }}" title="">Trang chủ</a>
            </li>
            <i class="item-section-icon fa-solid fa-angle-right"></i>
            <li class="item-section">
                <a class="item-section-link" href="{{ url('bai-viet') }}" title="">Blog công nghệ</a>
            </li>
        </ul>
    </div>
    {{-- Product-selling  --}}
    <div class="box-product">
        <h3 class="box-product-title">Sản phẩm bán chạy</h3>
        <div class="box-product-content">
            <ul class="list-product">
                {{-- Đổ dữ liệu sản phẩm bán chạy  --}}
                @foreach($product_sellings as $product)
                    <li class="product-item">
                        <a href="{{ route('product.detail', $product->slug) }}" title="" class="thumb">
                            <img src="{{ asset($product->image) }}" alt=""
                                class="product-img">
                        </a>
                        <div class="info">
                            <a href="{{ route('product.detail', $product->slug) }}" class="product-name">{{ $product->name_product }}</a>
                            <div class="price">
                                <span class="new">{{ number_format($product->price) }}đ</span>
                                <span class="old">{{ number_format($product->price_old) }}đ</span>
                            </div>
                            <a href="{{ route('cart.buyNow', $product->id) }}" title="" class="buy-now">Mua ngay</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        {{-- Đổ dữ liệu banner --}}
        <div class="box-banner">
            <a href="" class="box-banner-link">
                <img src="{{ asset($banners_position_sidebar_one->image) }}" alt=""
                    class="box-banner-link-img">
            </a>
        </div>
    </div>
</div>
