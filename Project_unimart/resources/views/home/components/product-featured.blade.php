<div class="box mobile-on-hide">
    <div class="box-heading">
        <h3 class="box-title">Sản phẩm nổi bật</h3>
    </div>
    <div class="box-content">
        <div class="row sm-gutter">
            <!-- Product-item  -->
            <div id="list-featured-post" class="list-item owl-carousel owl-theme">
                {{-- Đổ dữ liệu phần sản phẩm nổi bật  --}}
                @foreach($product_featureds as $product)
                    <div class="item item-info">
                        <a href="{{ route('product.detail', $product->slug) }}" title="" class="product-item-link">
                            <img class="product-item-img"
                                src="{{ asset($product->image) }}">
                            <div class="product-item-new">
                                <img class="item-new-img-new" src="{{ asset('images/icons/new.png') }}"
                                    alt="">
                            </div>
                            <div class="product-item-sale">
                                <img class="item-new-img-sale" src="{{ asset('images/icons/sale.png') }}"
                                    alt="">
                            </div>
                        </a>
                        <a href="{{ route('product.detail', $product->slug) }}" title="" class="product-item-name">
                            {{ $product->name_product }}
                        </a>
                        <div class="product-item-price">
                            <span class="product-item-price-new">{{ number_format($product->price) }}đ</span>
                            <span class="product-item-price-old">{{ number_format($product->price_old) }}đ</span>
                        </div>
                        <div class="product-item-info">
                            <button data-id="{{ $product->id }}" class="product-item-add add-to-cart">Thêm giỏ hàng</button>
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <input type="hidden" id="url" url="{{ route('cart.add') }}"/>
                            <a href="{{ route('cart.buyNow', $product->id) }}" title="" class="product-item-buy">Mua ngay</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
