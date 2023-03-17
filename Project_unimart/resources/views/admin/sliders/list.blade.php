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
                        Thêm ảnh slider
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('admin/sliders/store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                {{-- Thumbnail-sliders --}}
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="file">Ảnh slider</label><br>
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
                        Danh sách ảnh slider
                    </div>
                    @if (count($sliders) > 0)
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
                                        <th scope="col" class="text-center">Ảnh slider</th>
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
                                    @foreach ($sliders as $slider)
                                        <form method="POST" action="">
                                            @php
                                                $t++;
                                            @endphp
                                            <tr>
                                                <td scope="row" class="text-center">{{ $t }}</td>
                                                <td class="text-center">
                                                    <img src="{{ asset($slider->image) }}" class="img-fluid"
                                                        alt="Logo" style="max-width: 150px;">
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge badge-{{ $slider->status == 'Công khai' ? 'primary' : 'warning' }} d-inline-block p-2">{{ $slider->status }}</span>
                                                </td>
                                                <td class="text-info text-center">{{ $slider->name }}</td>
                                                <td class="text-center">{{ $slider->created_at->format('H:i d-m-Y') }}
                                                </td>
                                                {{-- Nếu là trạng thái chờ duyệt thì hiển thị hai nút thao tác bên dưới --}}
                                                @if (isset($wait))
                                                    <td class="text-center">
                                                        {{-- Thay đổi trạng thái --}}
                                                        <a href="{{ route('admin.slider.change', $slider->id) }}"
                                                            class="btn btn-success btn-sm rounded-0 text-white"
                                                            type="button" data-toggle="tooltip" data-placement="top"
                                                            title="Thay đổi trạng thái">
                                                            <i class="fas fa-sync"></i>
                                                        </a>
                                                        {{-- Xóa vĩnh viễn --}}
                                                        <a href="{{ route('admin.slider.delete', $slider->id) }}"
                                                            onclick="return confirm('Bạn có chắc chắn xóa slider này không ?')"
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
                                                        <a href="{{ route('admin.slider.change', $slider->id) }}"
                                                            class="btn btn-success btn-sm rounded-0 text-white"
                                                            type="button" data-toggle="tooltip" data-placement="top"
                                                            title="Thay đổi trạng thái">
                                                            <i class="fas fa-sync"></i>
                                                        </a>
                                                        {{-- Nếu vị trí là 0 thì sẽ bị vô hiệu hóa không cho di chuyển lên --}}
                                                        @if ($slider->position == 1)
                                                            <a href="{{ route('admin.slider.up', $slider->id) }}"
                                                                class="btn btn-info btn-sm rounded-0" type="button"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Không thể di chuyển slider tiếp vì hiện tại đang là slider đầu tiên"
                                                                style="pointer-events: none; opacity: 0.5;">
                                                                <i class="fas fa-arrow-up"></i>
                                                            </a>
                                                            {{-- Còn lại các vị trí khác sẽ được di chuyển lên --}}
                                                        @else
                                                            <a href="{{ route('admin.slider.up', $slider->id) }}"
                                                                class="btn btn-info btn-sm rounded-0" type="button"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Di chuyển slider lên" title="up"><i
                                                                    class="fas fa-arrow-up"></i>
                                                            </a>
                                                        @endif
                                                        {{-- Nếu slider có vị trí hiện tại bằng tổng slider đang có thì ko cho di chuyển xuống --}}
                                                        @if ($slider->position == $num_open)
                                                            <a href="{{ route('admin.slider.down', $slider->id) }}"
                                                                class="btn btn-info btn-sm rounded-0" type="button"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Không thể di chuyển xuống tiếp vì đã là slider cuối"
                                                                style="pointer-events: none; opacity: 0.5;">
                                                                <i class="fas fa-arrow-down"></i>
                                                            </a>
                                                            {{-- Còn lại các vị trí khác sẽ được di chuyển lên --}}
                                                        @else
                                                            <a href="{{ route('admin.slider.down', $slider->id) }}"
                                                                class="btn btn-info btn-sm rounded-0" type="button"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Di chuyển slider lên" title="up"><i
                                                                    class="fas fa-arrow-down"></i>
                                                            </a>
                                                        @endif
                                                        {{-- Xóa vĩnh viễn --}}
                                                        <a href="{{ route('admin.slider.delete', $slider->id) }}"
                                                            onclick="return confirm('Bạn có chắc chắn xóa slider này không ?')"
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
                        </div>
                    @else
                        <td colspan="7" class="bg-white">
                            <p class="text-danger">Không có ảnh slider nào trong hệ thống !</p>
                        </td>
                    @endif
                </div>
            </div>
            </table>
        </div>
    </div>
    {{-- Thanh phân trang --}}
    {{ $sliders->links() }}
@endsection
