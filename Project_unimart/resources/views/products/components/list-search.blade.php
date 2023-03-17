<!-- List-product-content -->
<div class="col l-9">
    <!-- Product-sale-1 -->
    <div class="main-content-search">
        <div class="box">
            {{-- <div class="box-heading"> --}}
            <div class="section-blog-head">
                @if ($list_items->count() > 0)
                    <h3 class="section-blog-title">Tìm thấy {{ $list_items->count() }} sản phẩm cho từ khóa "{{ $keyword }}"</h3>
                @else
                    <h3 class="section-blog-title">Kết quả tìm kiếm</h3>
                @endif
            </div>
            <div class="box-content">
                <div class="row sm-gutter">
                    @foreach ($list_items as $item)
                        <!-- Product-item  -->
                        <div class="col l-3 c-6">
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
                                    <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-name">
                                        {{ $item->name_product }}
                                    </a>
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
        </div>
    </div>
</div>
