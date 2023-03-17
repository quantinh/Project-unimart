{{-- Kế thừa layouts --}}
@extends('layouts.admin')

{{-- Định nghĩa nội dung --}}
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thông tin đơn hàng
                    </div>
                    <div class="card-body ">
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
                        <h6 class="text-primary"><i class="fas fa-address-card"></i> Thông tin khách hàng</h6>
                        <table class="table table-bordered mt-3 shadow-sm">
                            <tr class="bg-light">
                                <th class="text-center">Họ và tên</th>
                                <th class="text-center">Mã đơn</th>
                                <th class="text-center">Địa chỉ</th>
                                <th class="text-center">Số điện thoại</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Ngày đặt hàng</th>
                                <th class="text-center">Chú thích</th>
                            </tr>
                            @foreach ($info_customer as $value)
                                <tr>
                                    <td class="text-center">{{ $value->fullname }}</td>
                                    <td class="text-center">{{ $value->order_code }}</td>
                                    <td class="text-center">{{ $value->address }}</td>
                                    <td class="text-center">{{ $value->phone }}</td>
                                    <td class="text-center">{{ $value->email }}</td>
                                    <td class="text-center">{{ $value->time }}</td>
                                    <td class="text-center">{{ $value->note }}</td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="row mt-4">
                            <div class="col-6">
                                <h6 class="text-primary mb-3"><i class="fas fa-list-alt"></i> Tình trạng đơn hàng:
                                    <span class="badge badge-@if($status_order == 'Đang xử lí'){{$color[$status_order]}}@elseif($status_order == 'Đang vận chuyển'){{$color[$status_order]}}@elseif($status_order == 'Hủy đơn'){{$color[$status_order]}}@elseif($status_order == 'Thành công'){{$color[$status_order]}}@endif p-1">{{ $status_order }}</span>
                                </h6>
                                <form  method="POST" action="{{ route('admin.order.update', $id_order) }}">
                                    @csrf
                                    <div class="form-inline">
                                        <select class="form-control mr-1" name="action">
                                            <option value="0">Chọn tình trạng đơn hàng</option>
                                            @foreach ($list_action as $value)
                                                <option value="{{ $value }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <input type="submit" name="btn-search" value="Cập nhật" class="btn btn-primary"
                                            onclick="return confirm('Xác nhận chuyển trạng thái');">
                                    </div>
                                </form>
                            </div>
                            <div class="col-6">
                                <table class="table table-bordered" style="table-layout: fixed;">
                                    <tr class="bg-light">
                                        <th class="text-center">Tổng số lượng</th>
                                        <th class="text-center">Tổng tiền</th>
                                    </tr>
                                    <tr>
                                        @foreach ($info_customer as $value)
                                            <td class="text-center">{{ $value->total_quantily }} Sản phẩm</td>
                                            <td class="text-center">{{ number_format($value->total_price, 0, ',', '.') }}đ</td>
                                        @endforeach
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2">
                <div class="card">
                    <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                        Chi tiết đơn hàng
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-center">Ảnh</th>
                                    <th class="text-center">Tên sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center">Giá</th>
                                    <th class="text-center">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_detail_products as $item)
                                    <tr>
                                        <td class="text-center"><img src="{{ asset($item->image) }}" alt="hình ảnh sản phẩm" style="max-width: 100px; height: auto;"></td>
                                        <td class="text-center">{{ $item->name_product }}</td>
                                        <td class="text-center">{{ $item->quantily }}</td>
                                        <td class="text-center">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                        <td class="text-center">{{ number_format($item->sub_total, 0, ',', '.') }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
