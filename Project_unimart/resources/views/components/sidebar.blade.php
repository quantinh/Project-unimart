{{-- Product-selling  --}}
<div class="col l-3 mobile-on-hide">
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
    </div>
    {{-- Đổ dữ liệu banner-2 --}}
    <div class="box-banner">
        <a href="" class="box-banner-link">
            <img src="{{ asset($banners_position_sidebar_one->image) }}" alt=""
                class="box-banner-link-img">
        </a>
    </div>
    {{-- Đổ dữ liệu banner-3 --}}
    <div class="box-banner">
        <a href="" class="box-banner-link">
            <img src="{{ asset($banners_position_sidebar_two->image) }}" alt=""
                class="box-banner-link-img">
        </a>
    </div>
    {{-- Đổ dữ liệu banner-4 --}}
    <div class="box-banner">
        <a href="" class="box-banner-link">
            <img src="{{ asset($banners_position_sidebar_tree->image) }}" alt=""
                class="box-banner-link-img">
        </a>
    </div>
    {{-- Đổ dữ liệu banner-5 --}}
    <div class="box-banner">
        <a href="" class="box-banner-link">
            <img src="{{ asset($banners_position_sidebar_five->image) }}" alt=""
                class="box-banner-link-img">
        </a>
    </div>
</div>
