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
                        <a class="item-section-link" href="{{ url('gio-hang') }}" title="">Đặt hàng</a>
                    </li>
                    <i class="item-section-icon fa-solid fa-angle-right"></i>
                    <li class="item-section">
                        <a class="item-section-link" href="{{ route('cart.success') }}" title="">Đặt hàng thành công</a>
                    </li>
                </ul>
            </div>
            <!-- Nav-payment  -->
            <div class="box-payment mobile-on-hide">
                <div class="arrow-steps clearfix">
                    <div class="step done"><span class="number"><a href="{{ url('gio-hang') }}"><img src="{{ asset('images/icons/icon-checked.png') }}" alt=""></a></span>Đặt hàng</div>
                    <div class="step done"><span class="number"><a href="{{ route('cart.order') }}"><img src="{{ asset('images/icons/icon-checked.png') }}" alt=""></a></span>Vận chuyển</div>
                    <div class="step done"><span class="number"><a href="{{ route('cart.success') }}"><img src="{{ asset('images/icons/icon-checked.png') }}" alt=""></a></span>Đặt hàng thành công</div>
                </div>
            </div>
        </div>
    </div>
        <div class="row sm-gutter app-content">
            <!-- Table-history-order  -->
            <div class="col l-12 me-12 mb-5">
                <!-- Table-history  -->
                <h2 class="box-head-title mb-4">
                    THÔNG TIN KHÁCH HÀNG
                </h2>
                <div class="table">
                    <table class="table table-bordered table-hover">
                        <thead class="table-success">
                            <tr>
                                <th class="table-title text-center" scope="col">Mã đơn hàng</th>
                                <th class="table-title text-center" scope="col">Tên khách hàng</th>
                                <th class="table-title text-center" scope="col">Địa chỉ</th>
                                <th class="table-title text-center" scope="col">Email</th>
                                <th class="table-title text-center" scope="col">Số điện thoại</th>
                                <th class="table-title text-center" scope="col">Tình trạng</th>
                                <th class="table-title text-center" scope="col">Ngày đặt</th>
                                <th class="table-title text-center" scope="col">Xuất đơn hàng</th>
                                <th class="table-title text-center" scope="col">Hình thức thanh toán</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($info_customer as $info)
                                <tr>
                                    <td class="table-title">{{ $info->order_code }}</td>
                                    <td class="table-title">{{ $info->fullname }}</td>
                                    <td class="table-title info-address">{{  Str::of($info->address)->limit(45) }}</td>
                                    <td class="table-title">{{ $info->email }}</td>
                                    <td class="table-title">{{ $info->phone }}</td>
                                    <td class="table-title"><span class="badge badge-primary d-inline-block p-1">{{ $info->status }}</span></td>
                                    <td class="table-title">{{ $info->created_at->format('H:i d-m-Y') }}</td>
                                    {{-- Thêm phần này vào để tránh reload lại toàn bộ trang qua tab mới trên duyệt thì an toàn bảo mật hơn  --}}
                                    <td class="table-title"><a target="_blank" rel="noopener noreferrer" href="{{ route('cart.print', $info->order_code) }}"><i class="fa-solid fa-print"></i> In PDF</a></td>
                                    <td class="table-title">{{ $info->payment }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col l-12 me-12 mb-5">
                <!-- Table-history  -->
                <h1 class="box-head-title mb-4">
                    Thông tin sản phẩm
                </h1>
                <table class="table table-bordered table-hover">
                    <thead class="table-success">
                        <tr>
                            <th class="table-title text-center" scope="col">Tên sản phẩm</th>
                            <th class="table-title text-center" scope="col">Số lượng</th>
                            <th class="table-title text-center" scope="col">Giá</th>
                            <th class="table-title text-center" scope="col">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail_order as $info)
                            <tr>
                                <td class="table-title">{{ $info->name_product }}</td>
                                <td class="table-title">{{ $info->quantily }}</td>
                                <td class="table-title">{{ number_format($info->price, 0,',','.') }} đ</td>
                                <td class="table-title">{{ number_format($info->sub_total, 0,',','.') }} đ</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th scope="col" colspan="1" class="table-title text-center">Tổng tiền</th>
                            <td colspan="4" class="table-title">{{ number_format($total_order, 0,',','.') }} đ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col l-12 me-12 mb-5">
                <div class="back-home">
                    <a href="{{ url('/') }}">Về trang chủ</a>
                </div>
            </div>
        </div>
    </div>
