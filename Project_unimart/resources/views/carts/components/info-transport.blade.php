<div class="grid wide">
    <div class="row sm-gutter app-content">
        <div class="col l-12" style="margin-right: -9px;">
            {{-- Nav-menu   --}}
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
                    <i class="item-section-icon fa-solid fa-angle-right"></i>
                    <li class="item-section">
                        <a class="item-section-link" href="{{ route('cart.order') }}" title="">Đặt hàng</a>
                    </li>
                </ul>
            </div>
            <!-- Nav-payment  -->
            <div class="box-payment mobile-on-hide">
                <div class="arrow-steps clearfix">
                    <div class="step done"><span class="number"><a href="{{ url('gio-hang') }}"><img src="{{ asset('images/icons/icon-checked.png') }}" alt=""></a></span>Đặt hàng</div>
                    <div class="step current"><span class="number"><a href="{{ route('cart.order') }}">2</a></span>Vận chuyển</div>
                    <div class="step"><span class="number"><a href="">3</a></span>Đặt hàng thành công</div>
                </div>
            </div>
        </div>
    </div>
        <form action="{{ route('cart.store') }}" method="POST" name="btn-add" enctype="multipart/form-data">
            @csrf
            <div class="row sm-gutter app-content">
                <!-- Form-info  -->
                <div class="col l-7 me-12 c-12 mb-5">
                    <div class="box-info">
                        <div class="box-info-head">
                            <h1 class="box-head-title">
                                Thông tin vận chuyển
                            </h1>
                        </div>
                        <div class="box-info-body">
                            {{-- form-fullname  --}}
                            @error('fullname')
                                <small class="form-error text-danger">{{ $message }}</small>
                            @enderror
                            <div class="form-fullname">
                                <label class="label" for="fullname">Họ và tên:</label>
                                <input type="text" name="fullname" id="fullname" placeholder="Ví dụ: Nguyễn Văn A" value="{{ old('fullname') }}">
                            </div>
                            {{-- form-email  --}}
                            @error('email')
                                <small class="form-error text-danger">{{ $message }}</small>
                            @enderror
                            <div class="form-email">
                                <label class="label" for="email">Email:</label>
                                <input type="email" name="email" id="email" placeholder="supportxyz@gmail.com" value="{{ old('email') }}">
                            </div>
                            {{-- form-phone  --}}
                            @error('phone')
                                <small class="form-error text-danger">{{ $message }}</small>
                            @enderror
                            <div class="form-phone">
                                <label class="label" for="phone">Số điện thoại:</label>
                                <input type="text" name="phone" id="phone" pattern="[0-9]{5,20}"  placeholder="Ví dụ: 0963449261" value="{{ old('phone') }}">
                            </div>
                            {{-- form-address  --}}
                            @error('address')
                                <small class="form-error text-danger">{{ $message }}</small>
                            @enderror
                            <div class="form-address">
                                <label class="label" for="address">Địa chỉ:</label>
                                <input type="text" name="address" id="address" placeholder="Ví dụ: Số 10, ngõ 50, đường ABC" required maxlength="255" value="{{ old('address') }}">
                            </div>
                            {{-- form-note  --}}
                            @error('note')
                                <small class="form-error text-danger">{{ $message }}</small>
                            @enderror
                            <div class="form-note">
                                <label class="label" for="note">Ghi chú (Không bắt buộc):</label>
                                <textarea name="note" id="note" placeholder="Ví dụ: Chuyển hàng ngoài giờ hành chính">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- form-product-payment -->
                <div class="col l-5 me-12 c-12">
                    <div class="box-info">
                        <div class="box-info-head">
                            <h1 class="box-head-title">
                                Thông tin đơn hàng
                            </h1>
                        </div>
                        <div class="info-product-payment">
                            <div class="box-info-product">
                                <div class="heading-product">Sản phẩm :</div>
                                <div class="heading-total">Tổng</div>
                            </div>
                            @if (Cart::count() > 0)
                                @foreach (Cart::content() as $item)
                                    <div class="box-info-product">
                                        <div class="text-product">{{ Str::of($item->name)->limit(50) }}<strong class="product-quantity">x
                                            {{ $item->qty }}</strong>
                                        </div>
                                        <div class="text-total">{{ number_format($item->total, 0,'','.') }} đ</div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="box-info-footer">
                                <div class="text-product">Tổng đơn hàng:</div>
                                <div class="text-total"><strong class="total-price">{{ number_format(Cart::total(), 0, ',', '.') }} Đ</strong></div>
                            </div>
                        </div>
                    </div>
                    {{-- form-payment  --}}
                    <div class="box-info mt-5">
                        <div class="box-info">
                            <div class="box-info-head">
                                <h1 class="box-head-title">
                                    Phương thức thanh toán
                                </h1>
                            </div>
                        </div>
                        <div class="info-product-payment">
                            <ul class="list-methods-item">
                                <li class="methods-item">
                                    <input type="radio" id="direct-payment" name="payment"
                                        value="payment-home" checked>
                                    <img class="methods-item-img" src="{{ asset('images/icons/payhome.png')}}" alt="">
                                    <label class="label-payment" for="payment-home">Thanh toán tại nhà</label>
                                </li>
                                <li class="methods-item">
                                    <input type="radio" id="payment-home" name="payment"
                                        value="payment-transfer">
                                    <img class="methods-item-img" src="{{ asset('images/icons/cash2.png') }}" alt="">
                                    <label class="label-payment" for="payment-transfer">Thanh toán chuyển khoản</label>
                                </li>
                                <!-- <li class="methods-item">
                                    <input type="radio" id="payment-home" name="payment-method"
                                        value="payment-vnpay">
                                    <img class="methods-item-img" src="public/images/vnpay.png" alt="">
                                    <label class="label-payment" for="payment-vnpay">Thanh toán qua Vnpay</label>
                                </li>
                                <li class="methods-item">
                                    <input type="radio" id="payment-paypal" name="payment-method"
                                        value="payment-paypal">
                                    <img class="methods-item-img" src="public/images/paypal2.png" alt="">
                                    <label class="label-payment" for="payment-paypal">Thanh toán qua Paypal</label>
                                </li>
                                <li class="methods-item">
                                    <input type="radio" id="payment-momo" name="payment-method"
                                        value="payment-momo">
                                    <img class="methods-item-img" src="public/images/momo.png" alt="">
                                    <label class="label-payment" for="payment-momo">Thanh toán qua Momo</label>
                                </li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="float-right mt-4 mb-4">
                        <input type="submit" class="next pull-right" id="checkout-cart" value="Đặt hàng">
                    </div>
                </div>
            </div>
        </form>
    </div>
