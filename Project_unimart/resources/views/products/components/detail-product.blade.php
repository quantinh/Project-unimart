<!-- List-product-content -->
<div class="col l-9">
    <div class="main-content-detail">
        <div class="section-head">
            <h3 class="section-title-paragraph">Thông tin sản phẩm</h3>
        </div>
        <!-- Detail-product  -->
        <div class="section-detail" id="detail-product-wp">
            <div class="thumb-wp">
                @foreach ($list_images_info as $image)
                    @if ($image->product_id == $product->id)
                        <a href="" title="" id="main-thumb">
                            <img id="zoom" src="{{ asset($image->image_desc) }}"
                                data-zoom-image="{{ asset($image->image_desc) }}"
                                style="height: auto; max-width: 350px; border: none;" />
                        </a>
                    @endif
                @break
                @endforeach
                <div id="list-thumb">
                    @foreach ($list_images_info as $image)
                        @if ($image->product_id == $product->id)
                            <a href="" data-image="{{ asset($image->image_desc) }}"
                                data-zoom-image="{{ asset($image->image_desc) }}">
                                <img id="zoom" src="{{ asset($image->image_desc) }}"
                                    style="height: auto; max-width: 50px;" />
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="thumb-respon-wp">
                @foreach ($list_images_info as $image)
                    @if ($image->product_id == $product->id)
                        <img src="{{ asset($image->image_desc) }}" alt="">
                    @endif
                @break
                @endforeach
            </div>
            <div class="info-detail">
                <h3 class="detail-product-name">{{ $product->name_product }}</h3>
                <div class="desc-detail">
                    <p>{!! $product->detail !!}</p>
                </div>
                <div class="num-product">
                    <span class="title-product">Sản phẩm: </span>
                    <span
                        class="status-product">{{ $product->quantily > 0 ? 'Còn hàng (' . $product->quantily . ')' : 'Hết hàng' }}
                    </span>
                </div>
                <p class="product-price">
                    @if ($product->price_old)
                        {{ number_format($product->price_old, 0, ',', '.') }}đ
                    @else
                        {{ number_format($product->price, 0, ',', '.') }}đ
                    @endif
                </p>
                <div id="num-order-wp">
                    <p>Số lượng:</p>
                    <span class="increase" id="minus"><i class="fa-solid fa-minus"></i></span>
                    <input class="value" id="num-order" type="text" name="num-order" value="1">
                    <span class="reduction" id="plus"><i class="fa-solid fa-plus"></i></span>
                </div>
                <button data-id="{{ $product->id }}" class="add-cart add-to-cart" title="Thêm giỏ hàng">Thêm giỏ hàng</button>
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" id="url" url="{{ route('cart.add') }}"/>
            </div>
        </div>
        <!-- Paragraph-product  -->
        <div class="section" id="post-product-wp">
            <div class="section-head">
                <h3 class="section-title-paragraph">Mô tả sản phẩm</h3>
            </div>
            <div id="section_detail_product" class="section-detail-paragraph">
                <p class="paragraph">
                    {!! $product->description !!}
                </p>
                <div class="bg-article"></div>
                <button id="see-more">Xem thêm</button>
            </div>
        </div>
    </div>
    <!-- Product-highlight -->
    <div class="box">
        <div class="box-heading">
            <h3 class="box-title">Cùng chuyên mục</h3>
        </div>
        <div class="box-content">
            <div class="row sm-gutter">
                <!-- Product-item  -->
                <div id="list-featured-post" class="list-item owl-carousel owl-theme">
                    @foreach ($list_product_same_cats as $item)
                        <div class="item item-info">
                            <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-link">
                                <img class="product-item-img" src="{{ asset($item->image) }}">
                                <div class="product-item-new">
                                    <img class="item-new-img-new" src="public/images/new.png" alt="">
                                </div>
                                <div class="product-item-sale">
                                    <img class="item-new-img-sale" src="public/images/sale.png" alt="">
                                </div>
                            </a>
                            <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-name">
                                {{ $item->name_product }}
                            </a>
                            <div class="product-item-price">
                                <span class="product-item-price-new">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                                <span class="product-item-price-old">{{ number_format($item->price_old, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="product-item-info">
                                <button data-id="{{ $item->id }}" class="product-item-add add-to-cart">Thêm giỏ hàng</button>
                                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                <input type="hidden" id="url" url="{{ route('cart.add') }}"/>
                                <a href="{{ route('cart.buyNow', $item->id) }}" title="" class="product-item-buy">Mua ngay</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
