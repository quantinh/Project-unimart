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
                <h6 class="m-0 font-weight-bold">Danh sách bài viết</h5>
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
                    <a href="{{ url('admin/posts/list') }}" class="text-primary">Kích hoạt <span
                            class="text-muted"> ({{ $num_post_active }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'disable']) }}" class="text-primary">Vô hiệu
                        hóa<span class="text-muted"> ({{ $num_post_disable }})</span></a>
                    </div>
                <form action="{{ url('admin/posts/action') }}" method="POST">
                    @csrf
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" name="action">
                            <option value="">Chọn tác vụ</option>
                            @foreach ($list_action_post as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    @if ($keyword != "")
                        <h5 class="text-success py-2">Tìm thấy {{$list_posts->count()}} bảng ghi cho từ khóa "<em>{{$keyword}}</em>"</h5>
                    @endif
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col" class="text-center">STT</th>
                                <th scope="col" class="text-center">Ảnh</th>
                                <th scope="col" class="text-center">Tiêu đề</th>
                                <th scope="col" class="text-center">Slug</th>
                                <th scope="col" class="text-center">Danh mục</th>
                                @if ($status_post == 'disable')
                                    <th scope="col" class="text-center">Ngày vô hiệu hóa</th>
                                @else
                                    <th scope="col" class="text-center">Người tạo - Ngày tạo</th>
                                @endif
                                <th scope="col" class="text-center">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($list_posts) > 0)
                                @php
                                    !isset($_GET['page']) ? ($t = 0) : ($t = 6 * ($_GET['page'] - 1));
                                @endphp
                                @foreach ($list_posts as $post)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="list_check[]" value="{{ $post->id }}">
                                        </td>
                                        <td scope="row" class="text-center">{{ $t }}</td>
                                        <td class="box_img_page text-center">
                                            <img src="{{ asset($post->thumbnail) }}" class="img-fluid" alt="Logo">
                                        </td>
                                        <td class="text-title text-center">
                                            <a href="">{{ Str::of($post->title)->limit(25) }}</a>
                                        </td>
                                        <td class="text-center">{{ Str::of($post->slug)->limit(20) }}</td>
                                        <td class="text-center">
                                            <span class="text-center badge badge-primary d-inline-block p-2">{{ $post->cat_name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-info">{{ $post->name }}</span><br>
                                            {{ $post->created_at->format('H:i d-m-Y') }}
                                        </td>
                                        </td>
                                        <td class="text-center">
                                            {{-- Nếu trạng thái bài viết là vô hiệu hóa (trong thùng rác thì hiển thị hai nút ) --}}
                                            @if ($status_post == 'disable')
                                                {{-- Khôi phục --}}
                                                <a href="{{ route('admin.post.restore', $post->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Khôi phục bài viết">
                                                    <i class="fas fa-trash-restore"></i>
                                                </a>
                                                {{-- Xóa vĩnh viễn --}}
                                                <a href="{{ route('admin.post.delete', $post->id) }}"
                                                    onclick="return confirm('Bạn có chắc xóa vĩnh viễn bài viết này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn bài viết">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            {{-- Nếu là trạng thái bên ngoài thì hiển thị 2 nút --}}
                                            @else
                                                {{-- Chỉnh sửa thông tin --}}
                                                <a href="{{ route('admin.post.edit', $post->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Chỉnh sửa thông tin">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                {{-- Vô hiệu hóa --}}
                                                <a href="{{ route('admin.post.disable', $post->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn muốn vô hiệu hóa bài viết này không ?')"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Vô hiệu hóa bài viết">
                                                    <i class="far fa-eye-slash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="7">
                                    <p class="text-danger">Không tìm thấy bài viết nào trong hệ thống !</p>
                                </td>
                            @endif
                        </tbody>
                    </table>
                    {{-- Thanh phân trang --}}
                    {{ $list_posts->links() }}
                </form>
            </div>
        </div>
    </div>
@endsection
