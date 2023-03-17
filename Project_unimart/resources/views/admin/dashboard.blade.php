{{-- Kế thừa layouts --}}
@extends('layouts.admin');

{{-- Định nghĩa nội dung --}}
@section('content')
    <div class="container-fluid">

        {{-- Thông báo không có quyền truy cập --}}
        @if (session('status'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Cảnh báo !</h4>
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row d-flex flex-wrap">

            <div class="col pr-0 ">
                <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
                    <div class="card-header">DOANH SỐ</div>
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 16px;">
                            {{ number_format($statictis['total_sale'], 0, ',', '.') }}đ</h5>
                        <p class="card-text">Doanh số hệ thống</p>
                    </div>
                </div>
            </div>

            <div class="col pr-0 pl-2">
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐƠN YÊU CẦU XỬ LÝ</div>
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 16px;">{{ $num_orders['processing'] }}</h5>
                        <p class="card-text">Số đơn đang xử lý</p>
                    </div>
                </div>
            </div>

            <div class="col pr-0 pl-2">
                <div class="card text-dark bg-warning mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐƠN ĐANG VẬN CHUYỂN</div>
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 16px;">{{ $num_orders['transport'] }}</h5>
                        <p class="card-text">Số đơn đang vận chuyển</p>
                    </div>
                </div>
            </div>

            <div class="col pr-0 pl-2">
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐƠN THÀNH CÔNG</div>
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 16px;">{{ $num_orders['success'] }}</h5>
                        <p class="card-text">Số đơn gửi đi thành công</p>
                    </div>
                </div>
            </div>

            <div class="col pl-2">
                <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐƠN BỊ HỦY</div>
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 16px;">{{ $num_orders['cancel'] }}</h5>
                        <p class="card-text">Số đơn bị hủy</p>
                    </div>
                </div>
            </div>

            {{-- <div class="col-3">
                <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                    <div class="card-header">LỢI NHUẬN ƯỚC TÍNH</div>
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 16px;">
                            {{ number_format($statictis['total_sale'], 0, ',', '.') }}đ</h5>
                        <p class="card-text">20% Tổng doanh số</p>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="col pr-0">
                <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                    <div class="card-header">TỔNG KHO</div>
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 16px;">{{ $statictis['total_quantily'] }} SẢN PHẨM</h5>
                        <p class="card-text">Tổng số hàng trong kho</p>
                    </div>
                </div>
            </div>

            <div class="col pr-0">
                <div class="card text-dark bg-muted mb-3" style="max-width: 18rem;">
                    <div class="card-header">TỔNG SẢN PHẨM BÁN RA</div>
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 16px;">{{ $statictis['total_sold'] }} SẢN PHẨM</h5>
                        <p class="card-text">Tổng sản phẩm xuất kho</p>
                    </div>
                </div>
            </div> --}}
        </div>
        {{-- List-order --}}
        <div class="card">
            <div class="card-header font-weight-bold">
                ĐƠN HÀNG MỚI NHẤT
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr class="bg-light">
                            <th class="text-center">STT</th>
                            <th class="text-center">Mã đơn hàng</th>
                            <th class="text-center">Khách hàng</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-center">Tổng tiền</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thời gian cập nhập</th>
                            <th class="text-center">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($list_order_dashboards) > 0)
                            @php
                                !isset($_GET['page']) ? ($t = 0) : ($t = 6 * ($_GET['page'] - 1));
                            @endphp
                            @foreach ($list_order_dashboards as $order)
                                @php
                                    $t++;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $t }}</td>
                                    <td class="text-center">{{ $order->order_code }}</td>
                                    <td class="text-info text-center">{{ $order->fullname }}</td>
                                    <td class="text-center">{{ $order->total_quantily }}</td>
                                    <td class="text-center">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                    <td class="text-center">
                                        {{-- Chỗ này nếu if thì phải ghi sát vào không chừa khoảng cách nếu ko sẽ ko lấy được class màu badge-primary --}}
                                        {{-- Nếu key status có giá trị trong mảng, $color tồn tại phần tử trong mảng thì: xuất ra giá trị trạng thái của list_order trong mảng color, mảng color truy xuất vào key được cấp từ value của list_order và lấy ra được value là chuỗi class màu tương ứng --}}
                                        <span
                                            class="badge badge-@if(array_key_exists($order->status, $color)){{$color[$order->status] }} @endif p-1">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{-- Nếu trạng thái là hủy đơn thì xuất ra ngày xóa ngược lại xuất ra ngày cập nhập --}}
                                        {{ $order->status == 'Hủy đơn' ? $order->deleted_at->format('H:i d-m-Y') : $order->updated_at->format('H:i d-m-Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{-- Nút chi tiết đơn hàng --}}
                                        <a href="{{ route('admin.order.detail', $order->id) }}"
                                            class="btn btn-success btn-sm rounded-0 text-white" title="Chi tiết đơn hàng"
                                            type="button" data-toggle="tooltip" data-placement="top">
                                            <i class="far fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="bg-white">
                                    <span class="text-danger">
                                        Không tìm thấy kết quả cho từ khóa tìm kiếm của bạn !
                                    </span>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Thanh phân trang --}}
        {{ $list_order_dashboards->links() }}
    </div>
@endsection
