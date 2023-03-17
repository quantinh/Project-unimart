<!-- Category  -->
<div class="col l-3 mobile-on-hide">
    {{-- Category-home --}}
    <div class="category">
        <h3 class="category-heading">Danh mục sản phẩm</h3>
        <ul class="category-list">
            {{-- Lấy danh mục sản phẩm cha --}}
            @foreach($categorys as $indexCategory => $category)
                <li class="category-item category-item--{{ $indexCategory == 0 ? 'active' : '' }}">
                <a href="{{ route('product.category', $category->slug) }}" class="category-item-link">
                    <img class="category-img" src="{{ asset($category->icon_cat) }}" alt="">
                    {{ $category->cat_name }}
                </a>
                @if($category->categoryChildrent->count())
                    <i class="category-item-link-icon fa-solid fa-angle-right"></i>
                @endif
                <ul class="category-list-sub">
                    {{-- Lấy danh muc con --}}
                    @foreach($category->categoryChildrent as $indexcategoryChildrent => $categoryChildrent)
                        <li class="category-list-sub-item category-sub-item{{ $indexcategoryChildrent == 0 ? '--active' : '' }}">
                            <a href="{{ route('product.category', $categoryChildrent->slug) }}" class="category-list-sub-link" title="">
                                {{ $categoryChildrent->cat_name }}
                            </a>
                            @if($categoryChildrent->categoryChildrent->count())
                                <i class="category-item-sub-icon fa-solid fa-angle-right"></i>
                            @endif
                            <ul class="category-list-sub-child">
                            {{-- Lấy danh muc cháu --}}
                                @foreach($categoryChildrent->categoryChildrent as $categoryGrandChildrent)
                                    <li class="category-list-sub-item">
                                        <a href="{{ route('product.category', $categoryGrandChildrent->slug) }}" class="category-list-sub-link" title="">
                                            {{ $categoryGrandChildrent->cat_name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
                </li>
            @endforeach
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
        {{-- Đổ dữ liệu banner --}}
        <div class="box-banner">
            <a href="" class="box-banner-link">
                <img src="{{ asset($banners_position_sidebar_two->image) }}" alt=""
                    class="box-banner-link-img">
            </a>
        </div>
    </div>
</div>
