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
                <h6 class="m-0 font-weight-bold">Danh sách sản phẩm</h5>
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
                <form action="{{ url('admin/products/action') }}" method="">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" name="act">
                            <option>Chọn tác vụ</option>
                            @foreach ($list_act as $k => $act)
                                <option value="{{ $k }}">{{ $act }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th><input name="checkall" type="checkbox"></th>
                                <th class="text-center text-font">STT</th>
                                <th class="text-center text-font">Ảnh</th>
                                <th class="text-center text-font">Tên sản phẩm</th>
                                <th class="text-center text-font">Giá</th>
                                <th class="text-center text-font">Giá cũ</th>
                                <th class="text-center text-font">Danh mục</th>
                                <th class="text-center text-font">Người tạo</th>
                                <th class="text-center text-font">Trạng thái</th>
                                <th class="text-center text-font">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($list_products) > 0)
                                @php
                                    !isset($_GET['page']) ? ($t = 0) : ($t = 6 * ($_GET['page'] - 1));
                                @endphp
                                @foreach ($list_products as $product)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $product->id }}">
                                        </td>
                                        <td scope="row" class="text-center">{{ $t }}</td>
                                        <td class="box_img_page text-center">
                                            <img src="{{ asset($product->image) }}" class="img-fluid" alt="Logo">
                                        </td>
                                        <td class="text-title text-center">
                                            <a href="#">{{ Str::of($product->name_product)->limit(15) }}</a>
                                        </td>
                                        <td class="text-center">{{ number_format($product->price, 0, ',', '.') }}đ</td>
                                        <td class="text-center">{{ number_format($product->price_old, 0, ',', '.') }}đ</td>
                                        <td class="text-center">{{ Str::of($product->cat_name)->limit(10) }}</td>
                                        <td class="text-center"><span class="text-info">{{ $product->name }}</span><br>
                                            {{ $product->created_at->format('H:i d-m-Y') }}
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-{{ $product->status == 'Còn hàng' ? 'primary' : 'warning' }} d-inline-block p-1">{{ $product->status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            {{-- Nếu trong thùng rác thì có các thao tác --}}
                                            @if (request()->status == 'trash')
                                                {{-- Khôi phục --}}
                                                <a href="{{ route('admin.product.restore', $product->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Khôi phục">
                                                    <i class="fas fa-trash-restore-alt"></i>
                                                </a>
                                                {{-- Xóa --}}
                                                <a href="{{ route('admin.product.delete', $product->id) }}"
                                                    onclick="return confirm('Bạn có chắc xóa sản phẩm này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Xóa">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                {{-- Ngược lại thì có các thao tác --}}
                                            @else
                                                {{-- Vô hiệu hóa --}}
                                                <a href="{{ route('admin.product.disable', $product->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Vô hiệu hóa">
                                                    <i class="far fa-eye-slash"></i>
                                                </a>
                                                {{-- Chỉnh sửa thông tin --}}
                                                <a href="{{ route('admin.product.edit', $product->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Chỉnh sửa thông tin">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                {{-- Xóa --}}
                                                <a href="{{ route('admin.product.delete', $product->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa sản phẩm này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Xóa sản phẩm">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="10">
                                    <p class="text-danger">Không tìm thấy sản phẩm nào trong hệ thống !</p>
                                </td>
                            @endif
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        {{-- Thanh phân trang --}}
        {{ $list_products->links() }}
    </div>
@endsection
