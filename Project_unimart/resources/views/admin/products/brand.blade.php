{{-- Kế thừa layouts --}}
@extends('layouts.admin')

{{-- Định nghĩa nội dung --}}
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm thương hiệu
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/products/brand/store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">Tên thương hiệu</label>
                                <input class="form-control" type="text" name="name_brand" id="name_brand"
                                    value="{{ old('name_brand') }}">
                                @error('name_brand')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
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
                    <div class="card-header font-weight-bold">
                        Danh sách thương hiệu sản phẩm
                    </div>
                    @if (count($brands) > 0)
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="bg-light">
                                        <th scope="col" class="text-center">STT</th>
                                        <th scope="col" class="text-center">Thương hiệu</th>
                                        <th scope="col" class="text-center">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($brands as $item)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $t }}</td>
                                            <td class="text-center">{{ $item->name_brand }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.product.brand.edit', $item->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Chỉnh sửa tên thương hiệu">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.product.brand.delete', $item->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn thương hiệu này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0" type="button"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Xóa thương hiệu">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <td colspan="7">
                            <span class="text-danger">Không có danh mục thương hiệu nào trong hệ thống !</span>
                        </td>
                    @endif
                </div>
                {{-- Thanh phân trang --}}
                {{ $brands->links() }}
            </div>
        </div>
    </div>
@endsection
