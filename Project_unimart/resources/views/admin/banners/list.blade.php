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
                        Thêm ảnh quảng cáo mới
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('admin/banners/store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                {{-- Thumbnail-banners --}}
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="file">Ảnh banner</label><br>
                                        <input type="file" name="file" id="file" value="{{ old('file') }}"><br>
                                        @error('file')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div><br>
                                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                                </div>
                                {{-- Category-status --}}
                                <div class="col-6">
                                    <div class="form-group col-6">
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
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách ảnh quảng cáo
                    </div>
                    @if (count($banners) > 0)
                        <div class="card-body">
                            <div class="analytic mb-2">
                                <a href="{{ request()->fullUrlwithQuery(['status' => 'Công khai']) }}"
                                    class="text-primary">Công khai ({{ $num_open }})<span class="text-muted"></span></a>
                                <a href="{{ request()->fullUrlwithQuery(['status' => 'Chờ duyệt']) }}"
                                    class="text-primary">Chờ duyệt ({{ $num_wait }})<span class="text-muted"></span></a>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">STT</th>
                                        <th scope="col" class="text-center">Ảnh banner</th>
                                        <th scope="col" class="text-center">Trạng thái</th>
                                        <th scope="col" class="text-center">Người tạo</th>
                                        <th scope="col" class="text-center">Ngày tạo</th>
                                        <th scope="col" class="text-center">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($banners as $banner)
                                        <form method="POST" action="">
                                        @php
                                                $t++;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $t }}</td>
                                                <td class="text-center">
                                                    <img src="{{ asset($banner->image) }}" class="img-fluid"
                                                        alt="Logo" style="max-width: 100px;">
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge badge-{{ $banner->status == 'Công khai' ? 'primary' : 'warning' }} d-inline-block p-2">{{ $banner->status }}</span>
                                                </td>
                                                <td class="text-info text-center">{{ $banner->name }}</td>
                                                <td class="text-center">{{ $banner->created_at->format('H:i d-m-Y') }}</td>
                                                {{-- Nếu là trạng thái chờ duyệt thì hiển thị hai nút thao tác bên dưới --}}
                                                @if (isset($wait))
                                                    <td class="text-center">
                                                        {{-- Thay đổi trạng thái --}}
                                                        <a href="{{ route('admin.banner.change', $banner->id) }}"
                                                            class="btn btn-success btn-sm rounded-0 text-white"
                                                            type="button" data-toggle="tooltip" data-placement="top"
                                                            title="Thay đổi trạng thái">
                                                            <i class="fas fa-sync"></i>
                                                        </a>
                                                        {{-- Xóa vĩnh viễn --}}
                                                        <a href="{{ route('admin.banner.delete', $banner->id) }}"
                                                            onclick="return confirm('Bạn có chắc chắn xóa banner này không ?')"
                                                            class="btn btn-danger btn-sm rounded-0" type="button"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Xóa vĩnh viễn">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                @else
                                                    {{-- Ngược lại thì hiển thị đầy đủ thao tác công khai --}}
                                                    <td class="text-center">
                                                        {{-- Thay đổi trạng thái --}}
                                                        <a href="{{ route('admin.banner.change', $banner->id) }}"
                                                            class="btn btn-success btn-sm rounded-0 text-white"
                                                            type="button" data-toggle="tooltip" data-placement="top"
                                                            title="Thay đổi trạng thái">
                                                            <i class="fas fa-sync"></i>
                                                        </a>
                                                        {{-- Nếu vị trí là 0 thì sẽ bị vô hiệu hóa không cho di chuyển lên --}}
                                                        @if ($banner->position == 1)
                                                            <a href="{{ route('admin.banner.up', $banner->id) }}"
                                                                class="btn btn-info btn-sm rounded-0" type="button"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Không thể di chuyển banner tiếp vì hiện tại đang là banner đầu tiên"
                                                                style="pointer-events: none; opacity: 0.5;">
                                                                <i class="fas fa-arrow-up"></i>
                                                            </a>
                                                            {{-- Còn lại các vị trí khác sẽ được di chuyển lên --}}
                                                        @else
                                                            <a href="{{ route('admin.banner.up', $banner->id) }}"
                                                                class="btn btn-info btn-sm rounded-0" type="button"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Di chuyển banner lên" title="up"><i
                                                                    class="fas fa-arrow-up"></i>
                                                            </a>
                                                        @endif
                                                        {{-- Nếu banner có vị trí hiện tại bằng tổng banner đang có thì ko cho di chuyển xuống --}}
                                                        @if ($banner->position == $num_open)
                                                            <a href="{{ route('admin.banner.down', $banner->id) }}"
                                                                class="btn btn-info btn-sm rounded-0" type="button"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Không thể di chuyển xuống tiếp vì đã là banner cuối"
                                                                style="pointer-events: none; opacity: 0.5;">
                                                                <i class="fas fa-arrow-down"></i>
                                                            </a>
                                                            {{-- Còn lại các vị trí khác sẽ được di chuyển lên --}}
                                                        @else
                                                            <a href="{{ route('admin.banner.down', $banner->id) }}"
                                                                class="btn btn-info btn-sm rounded-0" type="button"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Di chuyển banner lên" title="up"><i
                                                                    class="fas fa-arrow-down"></i>
                                                            </a>
                                                        @endif
                                                        {{-- Xóa vĩnh viễn --}}
                                                        <a href="{{ route('admin.banner.delete', $banner->id) }}"
                                                            onclick="return confirm('Bạn có chắc chắn xóa banner này không ?')"
                                                            class="btn btn-danger btn-sm rounded-0" type="button"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Xóa vĩnh viễn">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                @endif
                                            </tr>
                                        </form>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <td colspan="8" class="bg-white">
                            <p class="text-danger">Không có ảnh banner nào trong hệ thống !</p>
                        </td>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
