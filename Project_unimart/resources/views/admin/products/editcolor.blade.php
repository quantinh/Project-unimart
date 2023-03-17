{{-- Kế thừa layouts --}}
@extends('layouts.admin')

{{-- Định nghĩa nội dung --}}
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Cập nhập màu sản phẩm
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.product.color.update', $color->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                {{-- Name-color --}}
                                <label for="name_color">Tên màu sản phẩm</label>
                                <input class="form-control" type="text" name="name_color" id="name_color"
                                    value="{{ $color->name_color }}">
                                @error('name_color')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                {{-- select_color --}}
                                <label for="color_select">Chọn màu</label>
                                <img style="min-width: 16px; min-height: 16px; box-sizing: unset; box-shadow: none; background: unset; padding: 0 6px 0 0; cursor: pointer;"
                                    src="chrome-extension://ohcpnigalekghcmgcdcenkpelffpdolg/img/icon16.png"
                                    class="colorpick-eyedropper-input-trigger" alt="">
                                <input class="form-control" type="color" name="color_select" value="{{ $color->code_color}}"
                                    id="color_select" colorpick-eyedropper-active="true">
                                @error('color_select')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                {{-- code_color --}}
                                <label for="">Mã màu</label>
                                <input id="hexcolor" name="code_color" value="{{ $color->code_color}}"
                                    class="form-control text-center" style="font-weight: 700; text-transform: uppercase" disabled>
                                @error('code_color')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhập</button>
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
                        Danh sách màu hiện có trên sản phẩm
                    </div>
                    @if (count($colors) > 0)
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="bg-light">
                                        <th scope="col" class="text-center">STT</th>
                                        <th scope="col" class="text-center">Tên</th>
                                        <th scope="col" class="text-center">Mã màu</th>
                                        <th scope="col" class="text-center">Hiển thị</th>
                                        <th scope="col" class="text-center">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($colors as $item)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <th scope="row" class="text-center">{{ $t }}</th>
                                            <td class="text-center">{{ $item->name_color }}</td>
                                            <td class="text-center">{{ $item->code_color }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-warning d-inline-block p-2" style="border: 1px solid gray; background-color: {{ $item->code_color }}"></span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.product.color.edit', $item->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Chỉnh sửa">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.product.color.delete', $item->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn mã màu này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Xóa">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <tr>
                            <span class="text-danger">Không có danh mục mã màu nào trong hệ thống !</span>
                        </tr>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
