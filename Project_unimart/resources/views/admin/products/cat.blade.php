{{-- Kế thừa layouts --}}
@extends('layouts.admin')

{{-- Định nghĩa nội dung --}}
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-12">
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
                        Thêm danh mục sản phẩm
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('admin/products/cat/store') }}" enctype="multipart/form-data">
                            @csrf
                            {{-- Form-cat-icon --}}
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="file">Icon danh mục</label><br>
                                    <input type="file" name="file" id="icon_cat"
                                        value="{{ old('icon_cat') }}"><br>
                                    @error('file')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            {{-- Form-name-cat --}}
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="cat_name">Tên danh mục</label>
                                    <input class="form-control" type="text" name="cat_name" id="cat_name"
                                        value="{{ old('cat_name') }}"
                                        placeholder="">
                                    @error('cat_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            {{-- Category-status --}}
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="parent_id">Thuộc danh mục <i>(Không bắt buộc nếu bạn muốn tạo danh mục
                                            cha)</i>
                                    </label>
                                    <select class="form-control" name="parent_id" id="parent_id">
                                        <option value="0">Chọn danh mục cha</option>
                                        @foreach ($list_cat as $cate)
                                            @if ($cate->status == 'Công khai')
                                                <option value="{{ $cate->id }}">
                                                    @php
                                                        echo str_repeat('--', $cate->level) . $cate->cat_name;
                                                    @endphp
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <small class='text-danger'>{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Trạng thái</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="public"
                                            value="Công khai">
                                        <label class="form-check-label" for="public">
                                            Công khai
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="waiting"
                                            value="Chờ duyệt" checked>
                                        <label class="form-check-label" for="waiting">
                                            Chờ duyệt
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Thêm mới</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách danh mục sản phẩm
                    </div>
                    @if (count($list_cat) > 0)
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Tên danh mục</th>
                                        <th scope="col">Slug</th>
                                        <th scope="col">Số lượng sản phẩm</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Người tạo</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_cat as $cat)
                                        <form method="POST" action="{{ route('admin.product.cat.update', $cat->id) }}">
                                            <tr>
                                                <td>
                                                    @php
                                                        echo str_repeat('--', $cat->level) . Str::of($cat->cat_name)->limit(10);
                                                    @endphp
                                                </td>
                                                <td>
                                                    {{ Str::of($cat->slug)->limit(10) }}
                                                </td>
                                                <td>
                                                    @foreach ($num_product_by_cat as $item)
                                                        @if ($item->cat_id == $cat->id)
                                                            {{ $item->number_products }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $cat->status == 'Công khai' ? 'primary' : 'warning' }} d-inline-block p-2">{{ $cat->status }}
                                                    </span>
                                                </td>
                                                <td class="text-info">{{ $cat->name }}</td>
                                                <td>{{ date('H:i d-m-Y', strtotime($cat->created_at)) }}</td>
                                                <td>
                                                    {{-- Thay đổi trạng thái --}}
                                                    <a href="{{ route('admin.product.cat.change', $cat->id) }}"
                                                        class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="Thay đổi trạng thái">
                                                        <i class="fas fa-sync"></i>
                                                    </a>
                                                    {{-- Chỉnh sửa --}}
                                                    <a href="{{ route('admin.product.cat.edit', $cat->id) }}"
                                                        class="btn btn-success btn-sm rounded-0" type="button"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="Chỉnh sửa danh mục">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    {{-- Xóa --}}
                                                    <a href="{{ route('admin.product.cat.delete', $cat->id) }}"
                                                        onclick="return confirm('Bạn có chắc chắn xóa danh mục bài viết này không ?')"
                                                        class="btn btn-danger btn-sm rounded-0" type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </form>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <td colspan="7">
                            <p class="text-danger">Không có danh mục sản phẩm nào trong hệ thống !</p>
                        </td>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
