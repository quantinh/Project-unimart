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
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Cập nhập danh mục bài viết
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('admin/posts/cat/update', $editcat->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            {{-- Form-name-cat --}}
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="cat_name">Tên danh mục</label>
                                    <input class="form-control" type="text" name="cat_name" id="cat_name"
                                        @isset($editcat->cat_name) value="{{ $editcat->cat_name }}" @endisset
                                        value="{{ old('cat_name') }}" placeholder="">
                                    @error('cat_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            {{-- Category-status --}}
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="parent_id">Thuộc danh mục</label>
                                    <select class="form-control" name="parent_id" id="parent_id" disabled>
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
                                <button type="submit" class="btn btn-primary">Cập nhập</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách danh mục bài viết
                    </div>
                    @if (count($list_cat) > 0)
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Tên danh mục</th>
                                        <th scope="col">Slug</th>
                                        <th scope="col">Số lượng bài viết</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Người tạo</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_cat as $cat)
                                        <form method="POST" action="{{ route('admin.post.cat.update', $cat->id) }}">
                                            <tr>
                                                <td>
                                                    @php
                                                        echo str_repeat('--', $cat->level) . $cat->cat_name;
                                                    @endphp
                                                </td>
                                                <td>{{ $cat->slug }}</td>
                                                <td>
                                                    @foreach ($num_post_by_cat as $item)
                                                        @if ($item->cat_id == $cat->id)
                                                            {{ $item->number_posts }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $cat->status == 'Công khai' ? 'success' : 'warning' }} d-inline-block p-1">{{ $cat->status }}
                                                    </span>
                                                </td>
                                                <td class="text-info">
                                                    {{ $cat->name }}
                                                </td>
                                                <td>{{ date('H:i d-m-Y', strtotime($cat->created_at)) }}</td>
                                                <td>
                                                    {{-- Thay đổi trạng thái --}}
                                                    <a href="{{ route('admin.post.cat.change', $cat->id) }}"
                                                        class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="Thay đổi trạng thái">
                                                        <i class="fas fa-sync"></i>
                                                    </a>
                                                    {{-- Chỉnh sửa --}}
                                                    <a href="{{ route('admin.post.cat.edit', $cat->id) }}"
                                                        class="btn btn-success btn-sm rounded-0" type="button"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="Chỉnh sửa danh mục">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    {{-- Xóa --}}
                                                    <a href="{{ route('admin.post.cat.delete', $cat->id) }}"
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
                        <tr>
                            <span class="text-danger">Không có danh mục bài viết nào trong hệ thống !</span>
                        </tr>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
