{{-- Kế thừa layouts --}}
@extends('layouts.admin')

{{-- Định nghĩa nội dung --}}
@section('content')
    <div id="content" class="container-fluid">
        {{-- Hiển thị thông báo thêm thành công --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Thông báo !</h4>
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Danh sách Đơn hàng</h5>
                    <div class="form-search form-inline">
                        <form action="#">
                            <input type="text" name="keyword" value="{{ request()->input('keyword') }}"
                                class="form-control form-search" placeholder="Nhập từ khóa..." style="padding: 10px;">
                            <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                        </form>
                    </div>
            </div>
            <div class="card-body">
                {{-- status-order --}}
                <div class="analytic">
                    <a href="{{ url('admin/orders/list') }}" class="text-primary">Tất
                        cả<span class="text-muted"> ({{ $count['all'] }})</span></a>
                    <a href="{{ request()->fullUrlwithQuery(['status' => 'processing']) }}" class="text-primary">Đang xử
                        lí<span class="text-muted"> ({{ $count['processing'] }})</span></a>
                    <a href="{{ request()->fullUrlwithQuery(['status' => 'transport']) }}" class="text-primary">Đang vận
                        chuyển<span class="text-muted"> ({{ $count['transport'] }})</span></a>
                    <a href="{{ request()->fullUrlwithQuery(['status' => 'success']) }}" class="text-primary">Thành
                        công<span class="text-muted"> ({{ $count['success'] }})</span></a>
                    <a href="{{ request()->fullUrlwithQuery(['status' => 'cancel']) }}" class="text-primary">Hủy
                        <span class="text-muted"> ({{ $count['cancel'] }})</span>
                    </a>
                </div>
                <form action="{{ route('admin.order.action') }}" method="POST">
                    @csrf
                    {{-- Sort-asc-desc --}}
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" name="action">
                            <option value="">Chọn tác vụ</option>
                            @foreach ($list_action as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary"
                            onclick="return confirm('Xác nhận thay đổi trạng thái cho các bảng ghi đã check');">
                        <a href="{{ url('admin/orders/list') }}" class="btn btn-success ml-2"
                            title="Sắp xếp đơn hàng từ mới đến cũ">
                            Đơn hàng mới nhất <i class="fas fa-arrow-up"></i>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['orderby' => 'asc']) }}" class="btn btn-danger ml-2"
                            title="Sắp xếp đơn hàng từ cũ đến mới">
                            Đơn hàng cũ nhất <i class="fas fa-arrow-down"></i>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['orderby' => 'total_asc']) }}" class="btn btn-info ml-2"
                            title="Sắp xếp tổng tiền tăng dần">
                            Tổng tiền tăng dần <i class="fas fa-arrow-up"></i>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['orderby' => 'total_desc']) }}"
                            class="btn btn-warning ml-2" title="Sắp xếp tổng tiền giảm dần">
                            Tổng tiền giảm dần <i class="fas fa-arrow-down"></i>
                        </a>
                    </div>
                    <table class="table table-hover table-checkall">
                        <thead>
                            <tr class="bg-light">
                                <th class="text-center"><input name="checkall" type="checkbox"></th>
                                <th class="text-center text-font">STT</th>
                                <th class="text-center text-font">Mã đơn hàng</th>
                                <th class="text-center text-font">Khách hàng</th>
                                <th class="text-center text-font">Số lượng sản phẩm</th>
                                <th class="text-center text-font">Tổng tiền</th>
                                <th class="text-center text-font">Trạng thái</th>
                                <th class="text-center text-font">Thời gian cập nhập</th>
                                <th class="text-center text-font">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($list_orders) > 0)
                                @php
                                    !isset($_GET['page']) ? ($t = 0) : ($t = 6 * ($_GET['page'] - 1));
                                @endphp
                                @foreach ($list_orders as $order)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td class="text-center"><input type="checkbox" name="list_check[]" value="{{ $order->id }}"></td>
                                        <td class="text-center">{{ $t }}</td>
                                        <td class="text-center">{{ $order->order_code }}</td>
                                        <td class="text-center text-info">{{ $order->fullname }}</td>
                                        <td class="text-center">{{ $order->total_quantily }}</td>
                                        <td class="text-center">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                        <td class="text-center">
                                            {{-- Chỗ này nếu if thì phải ghi sát vào không chừa khoảng cách nếu ko sẽ ko lấy được class màu badge-primary --}}
                                            {{-- Nếu key status có giá trị trong mảng, $color tồn tại phần tử trong mảng thì: xuất ra giá trị trạng thái của list_order trong mảng color, mảng color truy xuất vào key được cấp từ value của list_order và lấy ra được value là chuỗi class màu tương ứng --}}
                                            <span class="badge badge-@if(array_key_exists($order->status, $color)){{$color[$order->status]}} @endif p-1">
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
                                                class="btn btn-primary btn-sm rounded-0 text-white"
                                                title="Chi tiết đơn hàng">
                                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                            </a>
                                            {{-- Nếu trạng thái là đang xử lí or đang vận chuyển thì hiển thị nút hủy đơn --}}
                                            @if ($order->status == 'Đang xử lí' || $order->status == 'Đang vận chuyển')
                                                <a href="{{ route('admin.order.cancel', $order->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Hủy đơn hàng"
                                                    onclick="return confirm('Xác nhận hủy đơn hàng {{ $order->order_code }}');">
                                                    <i class="fas fa-calendar-times"></i>
                                                </a>
                                            @endif
                                            {{-- Nếu hủy đơn hàng or thành công rồi thì hiện nút xóa --}}
                                            @if ($order->status == 'Hủy đơn' || $order->status == 'Thành công')
                                                <a href="{{ route('admin.order.delete', $order->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"
                                                    onclick="return confirm('Xác nhận xóa vĩnh viễn đơn hàng {{ $order->order_code }}');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @if ($keyword == '')
                                    <tr>
                                        <td colspan="9">
                                            <p class="text-danger">Không tìm thấy đơn hàng nào !</p>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="9">
                                            <p class="text-danger">Không tìm thấy kết quả cho từ khóa tìm kiếm: <strong>{{ $keyword }}</strong></p>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        {{-- Thanh phân trang --}}
        {{ $list_orders->links() }}
    </div>
@endsection
