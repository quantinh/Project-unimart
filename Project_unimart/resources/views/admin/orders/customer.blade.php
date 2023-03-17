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
                <h6 class="m-0 font-weight-bold">Danh sách khách hàng</h6>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="text" name="keyword" value="{{ request()->input('keyword') }}"
                            class="form-control form-search" placeholder="Nhập từ khóa..." style="padding: 10px;">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlwithQuery(['status' => 'active']) }}" class="text-primary">Kích
                        hoạt<span class="text-muted"> ({{ $count[0] }})</span></a>
                    <a href="{{ request()->fullUrlwithQuery(['status' => 'trash']) }}" class="text-primary">Vô hiệu
                        hóa<span class="text-muted"> ({{ $count[1] }})</span></a>
                </div>
                <form action="{{ url('admin/orders/customer/action') }}" method="">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" name="act" id="">
                            <option>Chọn tác vụ</option>
                            @foreach ($list_act as $k => $act)
                                <option value="{{ $k }}">{{ $act }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-hover table-checkall">
                        <thead>
                            <tr class="bg-light">
                                <th class="text-center"><input type="checkbox" name="checkall"></th>
                                <th class="text-center">STT</th>
                                <th class="text-center">Khách hàng</th>
                                <th class="text-center">Số điện thoại</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Địa chỉ</th>
                                <th class="text-center">Ngày tạo</th>
                                <th class="text-center">Ghi chú</th>
                                <th class="text-center">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($customers) > 0)
                                @php
                                    !isset($_GET['page']) ? ($t = 0) : ($t = 6 * ($_GET['page'] - 1));
                                @endphp
                                @foreach ($customers as $customer)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td class="text-center"><input type="checkbox" name="list_check[]" value="{{ $customer->id }}"></td>
                                        <td class="text-center">{{ $t }}</td>
                                        <td class="text-info text-center">{{ $customer->fullname }}</td>
                                        <td class="text-center">{{ $customer->phone }}</td>
                                        <td class="text-center">{{ $customer->email }}</td>
                                        <td class="text-center">{{ Str::of($customer->address)->limit(15) }}</td>
                                        <td class="text-center">{{ date('H:i d-m-Y', strtotime($customer->created_at)) }}</td>
                                        <td class="text-center">{{ Str::of($customer->note)->limit(15) }}</td>
                                        <td class="text-center">
                                            @if ($status == 'trash')
                                                {{-- Hiển thị nút khôi phục --}}
                                                <a href="{{ route('admin.customer.restore', $customer->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Khôi phục khách hàng bị vô hiệu hóa">
                                                    <i class="fas fa-trash-restore-alt"></i>
                                                </a>
                                                {{-- Hiển thị nút xóa vĩnh viễn --}}
                                                <a href="{{ route('admin.customer.delete', $customer->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn khách hàng này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Xóa vĩnh viễn khách hàng">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @else
                                                {{-- Nút xóa tạm vô hiệu hóa  --}}
                                                <a href="{{ route('admin.customer.disable', $customer->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Vô hiệu hóa khách hàng">
                                                    <i class="fas fa-user-times"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="bg-white">
                                        <p class="text-danger">Không tìm thấy khách hàng nào trong hệ thống !</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        {{-- Thanh phân trang --}}
        {{ $customers->links() }}
    </div>
@endsection
