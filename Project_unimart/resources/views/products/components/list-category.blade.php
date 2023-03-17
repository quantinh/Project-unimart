<!-- List-product-content -->
<div class="col l-9">
    <!-- Product-sale-1 -->
    <div class="box">
        @if ($list_items->count() > 0)
            <div class="box-filter">
                <p class="desc-product">Hiển thị {{ $list_items->count() }} trên {{ $count_total_product }} sản phẩm</p>
            </div>
            <div class="form-filter">
                <div class="dropdown">
                    <button class="select btn dropdown-toggle" type="button" id="dropdownMenu1"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sắp xếp sản phẩm
                    </button>
                    <div class="option2 dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['orderby' => 'asc']) }}">Tên A - Z
                        </a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['orderby' => 'desc']) }}">Tên Z - A
                        </a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['orderby' => 'price_asc']) }}">Giá thấp đến cao
                        </a>
                        <a class="dropdown-item"
                            href="{{ request()->fullUrlWithQuery(['orderby' => 'price_desc']) }}">Giá cao đến thấp
                        </a>
                    </div>
                </div>
                <div class="dropdown mr-4">
                    <button class="select btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Lọc theo giá
                    </button>
                    <div class="option1 dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenu2">
                        <a class="dropdown-item" href="{{ url()->current() }}">Tất cả</a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['orderby' => 1]) }}">Dưới 5
                            triệu</a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['orderby' => 2]) }}">5 - 10
                            triệu</a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['orderby' => 3]) }}">10 - 20
                            triệu</a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['orderby' => 4]) }}">Trên 20
                            triệu</a>
                    </div>
                </div>
            </div>
            <div class="box-heading">
                <h3 class="box-title">{{ $cat_name }}</h3>
            </div>
            <div class="box-content">
                <div class="row sm-gutter">
                    <!-- Product-item  -->
                    @foreach ($list_items as $item)
                        <div class="col l-3 c-6">
                            <div class="list-item">
                                <div class="item-info">
                                    <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-link">
                                        <img class="product-item-img" src="{{ asset($item->image) }}">
                                        <div class="product-item-new">
                                            <img class="item-new-img-new" src="public/images/new.png" alt="">
                                        </div>
                                        <div class="product-item-sale">
                                            <img class="item-new-img-sale" src="public/images/sale.png" alt="">
                                        </div>
                                    </a>
                                    <a href="{{ route('product.detail', $item->slug) }}" title="" class="product-item-name">PC Intel I5 10500/16GB
                                        Ram/ VGA RX 550 4GB</a>
                                    <div class="product-item-price">
                                        <span class="product-item-price-new">9,990,000đ</span>
                                        <span class="product-item-price-old">9,990,000đ</span>
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
                <!-- Phân trang các sản phẩm -->
                {{ $list_items->links() }}
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
