<div class="col l-12" style="margin-right: -9px;">
    {{-- Nav-menu --}}
    <div class="section">
        <ul class="list-section">
            <i class="ml-0 item-section-icon fa-solid fa-house-chimney"></i>
            <li class="item-section">
                <a class="item-section-link" href="{{ url('/') }}" title="">Trang chủ</a>
            </li>
            <i class="item-section-icon fa-solid fa-angle-right"></i>
            <li class="item-section">
                <a class="item-section-link" href="{{ url('gio-hang') }}" title="">Giỏ hàng</a>
            </li>
        </ul>
    </div>
    @if (session('error'))
        <div class="alert alert-warning text-font">
            {{ session('error') }} <a href="{{ route('form.login') }}" class="alert-link">Click vào đây</a> để đăng nhập trước khi mua hàng.
        </div>
    @endif
    @if (Cart::count() > 0)
        <!-- Nav-payment  -->
        <div class="box-payment mobile-on-hide">
            <div class="arrow-steps clearfix">
                <div class="step current"><span class="number"><a href="{{ url('gio-hang') }}">1</a></span>Đặt hàng</div>
                <div class="step"><span class="number"><a href="{{ route('cart.order') }}">2</a></span>Vận chuyển</div>
                <div class="step"><span class="number"><a href="#">3</a></span>Đặt hàng thành công</div>
            </div>
        </div>
        <!-- End-nav-payment  -->
        <div id="wrapper" class="wp-inner">
            <div class="section-cart" id="info-cart-wp">
                <div class="section-detail-cart table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>STT</td>
                                    <td>Ảnh sản phẩm</td>
                                    <td>Tên sản phẩm</td>
                                    <td>Giá sản phẩm</td>
                                    <td>Số lượng</td>
                                    <td>Thành tiền</td>
                                    <td>Tác vụ</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $t = 0;
                                @endphp
                                @foreach (Cart::content() as $row)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td>{{ $t }}</td>
                                        <td>
                                            <a href="{{ route('product.detail', $row->options->slug) }}" title="" class="thumb">
                                                <img class="thumb-img" src="{{ asset($row->options->image) }}" alt="">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('product.detail', $row->options->slug) }}" title="" class="name-product">{{ $row->name }}</a>
                                        </td>
                                        <td>{{ number_format($row->price, 0, ',', '.') }}đ</td>
                                        <td>
                                            <button class="minus increment" id="minus"><i class="fa-solid fa-minus"></i></button>
                                            <input class="num-order" type="number" name="num-order" value="{{ $row->qty }}" data-id="{{ $row->rowId }}" id="num-order" min="1" max="{{ $row->options->warehouse }}">
                                            <button class="plus decrement" id="plus"><i class="fa-solid fa-plus"></i></button>
                                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                            <input type="hidden" id="url" url="{{ route('cart.update') }}" />
                                        </td>
                                        <td id="sub-total" class="sub{{$row->rowId}}">{{ number_format($row->total, 0, ',', '.') }}đ</td>
                                        <td>
                                            <a href="{{ route('cart.remove', $row->rowId) }}" title="" class="del-product">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">
                                        <div class="clearfix">
                                            <p id="total-price" class="float-right">Tổng giá:
                                                <span>{{ number_format(Cart::total(), 0, ',', '.') }} đ</span>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7">
                                        <div class="clearfix">
                                            <div class="float-right">
                                                <a href="{{ route('cart.destroy') }}" title="" id="update-cart">
                                                    Xóa toàn bộ giỏ hàng
                                                </a>
                                                {{-- Nếu khách hàng đã đăng nhập thì hiển thị tên và đăng xuất  --}}
                                                <?php $customer_id = session()->get('id');
                                                    if($customer_id != null) {
                                                ?>
                                                    <a href="{{ route('cart.order') }}" class="next pull-right" id="checkout-cart">
                                                        Thanh toán
                                                    </a>
                                                {{-- Ngược lại nếu khách hàng chưa đăng nhập thì hiển thị đăng nhập và đăng kí  --}}
                                                <?php } else { ?>
                                                    <a href="{{ route('form.login') }}" class="next pull-right" id="checkout-cart">
                                                        Thanh toán
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
            </div>
            <div class="section" id="action-cart-wp">
                <div class="section-detail-cart">
                    <p class="title">Click vào <span>“Cập nhật giỏ hàng”</span> để cập nhật số lượng.
                        Nhập vào số lượng <span>0</span> để xóa sản phẩm khỏi giỏ hàng. Nhấn vào thanh
                        toán để hoàn tất mua hàng.</p>
                    <a href="{{ url('/') }}" id="buy-more">Mua tiếp</a><br />
                </div>
            </div>
        </div>
    @else
        <div class="cart-empty" style="margin-top: 40px; margin-bottom: 60px">
            <img src="{{ asset('images/backgrounds/cart-empty-2.png') }}" style="max-height: 200px; width: auto;">
        </div>
    @endif
</div>
